<?php
require 'config.php';
?>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<a href="../html/index.html">Tilbake til forsiden</a>
<h1>Book et rom</h1>
<h2>Filter</h2>
<form method="post">
    <label><input type="checkbox" name="size1" value="2" checked>2 personer</label>
    <label><input type="checkbox" name="size2" value="3" checked>3 personer</label>
    <label><input type="checkbox" name="size3" value="4" checked>4 personer</label>
    <label><input type="checkbox" name="projector" value="j">Må ha prosjektor</label>
    <input type="submit" value="Filtrer" name="filter">
</form>

<!--<h2>Datepicker</h2>
<form method="get">
    <input type="submit" value="<-" name="prevDay">
    <input type="submit" value="->" name="nextDay">
</form>-->

    <?php
    // Get all filter variables
    $querySizes = array(2, 3, 4);

    $queryProjector = "%";

    $queryDate = date("Y-m-d"); // TODO Replace with date from datepicker

    if (isset($_POST['filter']))
    {
        if (isset($_POST["size1"]))
            $querySizes[0] = 2;
        else
            $querySizes[0] = 0;
        if (isset($_POST["size2"]))
            $querySizes[1] = 3;
        else
            $querySizes[1] = 0;
        if (isset($_POST["size3"]))
            $querySizes[2] = 4;
        else
            $querySizes[2] = 0;
        if (isset($_POST['projector']))
        {
            $queryProjector = 'j';
        }

    }

    //remove sql leieavrom on expire

    // Query to get all rooms
    $roomSql = $db->prepare("SELECT * FROM Rom WHERE Storrelse IN (:size1, :size2, :size3) AND Prosjektor LIKE :projector;");
    $roomSql->setFetchMode(PDO::FETCH_OBJ);
    $roomSql->bindParam(':size1', $querySizes[0], PDO::PARAM_STR);
    $roomSql->bindParam(':size2', $querySizes[1], PDO::PARAM_STR);
    $roomSql->bindParam(':size3', $querySizes[2], PDO::PARAM_STR);
    $roomSql->bindParam(':projector', $queryProjector, PDO::PARAM_STR);
    $roomSql->execute();


    // Run through results of query
    while($rom = $roomSql->fetch())
    {
        // Display room
        $roomId = $rom->RomId;

        echo "<div class='roomBlock'>";
        $prosjektor = "JA";
        if ($rom->Prosjektor === "n")
        {
            $prosjektor = "NEI";
        }
        echo "<h3>" . $rom->Beskrivelse . "</h3>";
        echo "<p>Størrelse <span class='infoBlock'>" . $rom->Storrelse . "</span>" . " Prosjektor <span class='infoBlock'>" . $prosjektor . "</span></p>";

        // Query to get all rents of the set date
        $sql = $db->prepare("SELECT * FROM LeieAvRom WHERE RomId LIKE :roomId AND Dato LIKE :date;");
        $sql->setFetchMode(PDO::FETCH_OBJ);
        $sql->execute(array(
            'roomId' => $roomId,
            'date' => $queryDate
        ));

        // Populate array with boolean for whether the room is occupied for each hour
        $rented = array_fill(0, 13, false);
        while($rent = $sql->fetch())
        {
            for ($i = 8; $i <= 20; $i ++)
            {
                $startTime = $rent->Tidspunkt;
                $hour = $i * 3600;
                $hours = $rent->AntallTimer;

                $start = strtotime($rent->Tidspunkt);
                $end = $start + (3600 * $hours);
                $cur = strtotime(date('H:i:s', $hour));

                if ($cur > $start - 1 && $cur < $end - 1)
                {
                    $rented[$i - 8] = true;
                }
            }
        }

        // Display hours (un)available
        $i = 0;
        foreach($rented as $isRented)
        {
            $bgColor = "0fa";
            $rentedVal = 0; // Must use an int, when using a boolean the function would mess up for some reason. Yay for never having touched javascript before...
            if ($isRented)
            {
                $bgColor = "f55";
                $rentedVal = 1;
            }

            // TODO Replace this with a PHP implementation for iteration 2
            // This won't work (to my limited knowledge)/be difficult to get to communicate correctly with a database...
            $mouseClickEvent = "onclick='clickedTimeElement(this, " . $i . ", " . $rentedVal . ", " . $roomId . ");' ";
            $mouseOverEvent = "onmouseover='addHoverToSelection(this, " . $i . ", " . $rentedVal . ", " . $roomId . ");'";
            echo "<div class='timeBlock' " . $mouseClickEvent . $mouseOverEvent . " style='background-color: #" . $bgColor . ";'>" . str_pad($i + 8, 2, '0', STR_PAD_LEFT) . ":00</div>";
            $i ++;
        }
        ?>
        <form method="get" action="bekreftelse.php">
            <br />
            <label>Fra tidspunkt (f.eks. 10:00)<input type="time" placeholder="Tidspunkt (f.eks: 10:00)" name ="timeinput"></label>
            <label>Antall timer <input type="number" min="0" max="8" placeholder="Antall timer" name ="hourinput"></label>
            <input type="submit" value="Book rom" name="booking<?php echo $roomId ?>"> <!-- Sleng på tilsvarende RomId på knappen -->
        </form>

        <?php
        echo "</div>";
    }

    // Check if any rows were returned
    if ($roomSql->rowCount() <= 0)
    {
        echo "<br /><br />No search results...";
    }
    ?>

<script src="main.js"></script>
</body>
</html>