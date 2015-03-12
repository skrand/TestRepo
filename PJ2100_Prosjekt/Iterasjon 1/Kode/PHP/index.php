<?php
require 'config.php';
//include_once('libs/DB.php');
//$db = new DB();
?>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<h1>Book et rom</h1>
<h2>Filter</h2>
<form method="post">
    <label><input type="checkbox" name="size1" value="2" checked>2 personer</label>
    <label><input type="checkbox" name="size2" value="3" checked>3 personer</label>
    <label><input type="checkbox" name="size3" value="4" checked>4 personer</label>
    <label><input type="checkbox" name="projector" value="j">Må ha prosjektor</label>
    <input type="submit" value="Filtrer" name="filter">
</form>

<h2>Dato</h2>
<!--<form method="get">
    <input type="submit" value="<-" name="prevDay">
    <input type="submit" value="->" name="nextDay">
</form>-->


    <?php
    // Get all filter variables
    $querySizes = array(2, 3, 4);

    $queryProjector = "%";

    $queryDate = date("Y-m-d");//"2015-03-12"; // TODO Replace with current date (from datepicker)

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
    // Query
    /*
     * Få Rom oversikt og LeieAvRom oversikt (for nåværende dato) i to forskjellige spørringer
     * Så bruk rom oversikten til å vise rom, og LeieAvRom til å vise ledighet
     * foreach (room)
     *      display room info
     *      foreach (rent of this room) [query to get the rents uses the date]
     *          display red or green element
     * */
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
        $rentSql = $db->prepare("SELECT * FROM LeieAvRom WHERE RomId LIKE :roomId AND Dato LIKE :date;");
        $rentSql->setFetchMode(PDO::FETCH_OBJ);
        $rentSql->bindParam(':roomId', $roomId, PDO::PARAM_STR);
        $rentSql->bindParam(':date', $queryDate, PDO::PARAM_STR);
        $rentSql->execute();

        $rented = array_fill(0, 12, false);
        while($rent = $rentSql->fetch())
        {
            $hasDone = false;
            for ($i = 8; $i <= 20; $i ++)
            {
                $startTime = $rent->Tidspunkt;
                $hour = $i * 3600;
                $hours = $rent->AntallTimer;

                $start = strtotime($rent->Tidspunkt);
                $end = $start + (3600 * $hours);
                $cur = strtotime(date('H:i:s', $hour));

                if ($cur > $start + 1 && $cur < $end + 1)
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

        <form method="post" action="bekreftelse.php">
            <input type="time" placeholder="Tidspunkt (f.eks: 10:00)" name ="timeinput">
            <input type="number" min="0" max="8" placeholder="Antall timer" name ="hourinput">
            <?php
                echo "<input type='text' value='" . $roomId . "' name ='idinput'>" // Hack to send roomId with the form to POST
            ?>
            <input type="submit" value="Book rom" name="booking">
        </form>

        <?php
        /*$startTime = "";
        $hourCount = 0;
        if (isset($_POST['booking']))
        {

            if (isset($_POST["timeinput"]))
            {
                $startTime = $_POST['timeinput'];
                //$_SESSION['book_time'] = $_POST['timeinput'];
            }
            if (isset($_POST["hourinput"]))
            {
                $hourCount = $_POST['hourinput'];
                //$_SESSION['book_hours'] = $_POST['hourinput'];
            }
            $insertSql = $db->prepare("INSERT INTO LeieAvRom VALUES (:roomId, 1, '2015-03-12', :time, :hourcount);");
            $insertSql->bindParam(':roomId', $roomId, PDO::PARAM_INT);
            $insertSql->bindParam(':time', $startTime, PDO::PARAM_STR);
            $insertSql->bindParam(':hourcount', $hourCount, PDO::PARAM_STR);
            $insertSql->execute();

            // Refresh page
            echo "RoomID: " . $roomId;
            header("Location: index.php");
            die();
        }*/

        /* // Display rent buttons
        //echo "<div class='button' onclick='rentRoom(" . $roomId . ");'>Rent room</div>";
        $clickevent = "onclick='rentRoom(" . $rom->RomId . ");'";
        echo "<div class='button' " . $clickevent . ">Rent</div>";*/

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