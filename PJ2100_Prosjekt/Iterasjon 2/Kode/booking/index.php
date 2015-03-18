<?php
require 'config.php';
require 'verifysession.php';
/* ITERASJON 2
 * - Dato velger
 * - Begrense bestilling til ledige timer
 * */
?>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<header>
<a href="../index.php"><img src="../images/logo4.png"/></a>
</header>
<h3>Filter</h3>
<form method="post">
    <label><input type="checkbox" name="size1" value="2" checked>2 personer</label>
    <label><input type="checkbox" name="size2" value="3" checked>3 personer</label>
    <label><input type="checkbox" name="size3" value="4" checked>4 personer</label>
    <label><input type="checkbox" name="projector" value="j">Må ha prosjektor</label>
    <input type="submit" value="Filtrer" name="filter">
</form>

<?php
    // Set datoen
    $queryDate = date('Y-m-d');
    $dateButtonTag = "deactivated";
    if (isset($_GET['dato']))
    {
        $queryDate = $_GET['dato'];
    }
    // Begrens slik at man ikke kan gå lengre tilbake en dags dato
    if (strtotime($queryDate) < strtotime(date('Y-m-d') . ' +1 day'))
    {
        $queryDate = date('Y-m-d');
        $dateButtonTag = "deactivated";
    }
    else
    {
        $dateButtonTag = "";
    }
    $prevDay = date('Y-m-d', strtotime($queryDate .' -1 day'));
    $nextDay = date('Y-m-d', strtotime($queryDate .' +1 day'));
    $prevWeek = date('Y-m-d', strtotime($queryDate .' -1 week'));
    $nextWeek = date('Y-m-d', strtotime($queryDate .' +1 week'));
    $prevMonth = date('Y-m-d', strtotime($queryDate .' -1 month'));
    $nextMonth = date('Y-m-d', strtotime($queryDate .' +1 month'));
?>

<h3>Velg dato</h3>
<a href="?dato=<?=$prevMonth;?>" class="<?php echo $dateButtonTag ?> datePickerButton">- 30</a>
<a href="?dato=<?=$prevWeek;?>" class="datePickerButton <?php echo $dateButtonTag ?>">- 7</a>
<a href="?dato=<?=$prevDay;?>" class="datePickerButton <?php echo $dateButtonTag ?>">- 1</a>
<b><?php echo $queryDate; ?></b>
<a href="?dato=<?=$nextDay;?>" class="datePickerButton">+ 1</a>
<a href="?dato=<?=$nextWeek;?>" class="datePickerButton">+ 7</a>
<a href="?dato=<?=$nextMonth;?>" class="datePickerButton">+ 30</a>
<br><br>

    <?php

    // Get all filter variables
    $querySizes = array(2, 3, 4);

    $queryProjector = "%";

    if (isset($_POST['prevDay'])) // Previous day
    {
        $date = date('Y-m-d', strtotime(' +1 day'));
        echo "prev day shit";
    }
    if (isset($_POST['nextDay'])) // Previous day
    {
        $date = date('Y-m-d', strtotime(' +1 day'));
        echo "next day shit";
    }


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
        $prosjektor = "Ja";
        if ($rom->Prosjektor === "n")
        {
            $prosjektor = "Nei";
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
        /*$rented = array_fill(0, 13, false);
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
        }*/
        $rented = array_fill(0, 13, null);
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
                    $rented[$i - 8] = $rent;
                }
            }
        }

        // Display hours (un)available
        $i = 0;
        foreach($rented as $isRented)
        {
            $bgColor = "FFF";
            $rentedVal = 0; // Must use an int, when using a boolean the function would mess up for some reason. Yay for never having touched javascript before...
            if ($isRented)
            {
                $bgColor = "999";
                $rentedVal = 1;
            }

            // TODO Replace this with a PHP implementation for iteration 2
            // This won't work (to my limited knowledge)/be difficult to get to communicate correctly with a database...
            /*$mouseClickEvent = "onclick='clickedTimeElement(this, " . $i . ", " . $rentedVal . ", " . $roomId . ");' ";
            $mouseOverEvent = "onmouseover='addHoverToSelection(this, " . $i . ", " . $rentedVal . ", " . $roomId . ");'";
            echo "<div class='timeBlock' " . $mouseClickEvent . $mouseOverEvent . " style='background-color: #" . $bgColor . ";'>" . str_pad($i + 8, 2, '0', STR_PAD_LEFT) . ":00</div>";*/

            $renterIsLoggedIn = false;
            $timeInfo = "Ledig!";
            if ($isRented)
            {

                if ($isRented->BrukerId == getUserIdFromName($_SESSION['user'], $db))
                {
                    $timeInfo = "Du leier her!";
                    $renterIsLoggedIn = true;
                    $bgColor = "7fc79a";
                }
                else
                {
                    $timeInfo = "Opptatt, leies av " . getUserFromId($isRented->BrukerId, $db)['Brukernavn'];
                }
            }
            $timestamp = str_pad($i + 8, 2, '0', STR_PAD_LEFT) . ":00";
            echo "<div class='timeBlock' onclick='clicked(this);' style='background-color: #" . $bgColor . ";'>" . $timestamp;

            ?>
                    <div class='timeChild'>
                        <p><?php echo $timeInfo ?></p>
                        <?php
                        if ($renterIsLoggedIn)
                        {
                            echo "<a href='avbestill.php?date=" . $queryDate . "&roomid=" . $roomId . "&time=" . $isRented->Tidspunkt . "'>Avbestill</a>";
                        }
                        else if (!$isRented)
                        {
                        ?>
                            <form method="post" action="bekreftelse.php?date=<?php echo $queryDate . "&time=" . $timestamp . ":00&roomid=" . $roomId ?>">
                                <label>Antall timer <input type="number" min="1" max="8" placeholder="Antall timer" name ="hourinput" required=""></label>
                                <input type="submit" value="Book rom" name="booking">
                            </form>
                            <?php } ?>
                    </div>
                </div>

                <?php
            $i ++;
        }

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