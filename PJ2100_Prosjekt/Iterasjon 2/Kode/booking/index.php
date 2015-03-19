<?php
require 'config.php';
require 'verifysession.php';
?>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="main.js"></script>
</head>
<body>
<?php require 'header.php'; ?>

<div id="content">
    <div id="blockFilter" class="mainBlock">
        <h3>Filtrering</h3>
        <div class="center">
            <form method="post">
                <label><input type="checkbox" name="size1" value="2" checked>2 personer</label>
                <label><input type="checkbox" name="size2" value="3" checked>3 personer</label>
                <label><input type="checkbox" name="size3" value="4" checked>4 personer</label>
                <label><input type="checkbox" name="projector" value="j">Må ha prosjektor</label>
                <input type="submit" value="Filtrer" name="filter">

                <?php
                    // Set datoen
                    $queryDate = date('Y-m-d');
                    $dateButtonTagDay = "deactivated";
                    $dateButtonTagWeek = "deactivated";
                    $dateButtonTagMonth = "deactivated";
                    if (isset($_GET['dato']))
                    {
                        $queryDate = $_GET['dato'];
                    }
                    // Begrens slik at man ikke kan gå lengre tilbake en dags dato
                    if (strtotime($queryDate) < strtotime(date('Y-m-d') . ' +1 day')) $dateButtonTagDay = "deactivated";
                    else $dateButtonTagDay = "";
                    if (strtotime($queryDate) < strtotime(date('Y-m-d') . ' +1 week')) $dateButtonTagWeek = "deactivated";
                    else $dateButtonTagMonth = "";
                    if (strtotime($queryDate) < strtotime(date('Y-m-d') . ' +1 month')) $dateButtonTagMonth = "deactivated";
                    else $dateButtonTagMonth = "";

                    $prevDay = date('Y-m-d', strtotime($queryDate .' -1 day'));
                    $nextDay = date('Y-m-d', strtotime($queryDate .' +1 day'));
                    $prevWeek = date('Y-m-d', strtotime($queryDate .' -1 week'));
                    $nextWeek = date('Y-m-d', strtotime($queryDate .' +1 week'));
                    $prevMonth = date('Y-m-d', strtotime($queryDate .' -1 month'));
                    $nextMonth = date('Y-m-d', strtotime($queryDate .' +1 month'));
                ?>

                <a href="?dato=<?=$prevMonth;?>" class="<?php echo $dateButtonTagDay ?> button">- 30</a>
                <a href="?dato=<?=$prevWeek;?>" class="button <?php echo $dateButtonTagWeek ?>">- 7</a>
                <a href="?dato=<?=$prevDay;?>" class="button <?php echo $dateButtonTagMonth ?>">- 1</a>
                <a href="index.php" class="button"><?php echo $queryDate; ?></a>
                <a href="?dato=<?=$nextDay;?>" class="button">+ 1</a>
                <a href="?dato=<?=$nextWeek;?>" class="button">+ 7</a>
                <a href="?dato=<?=$nextMonth;?>" class="button">+ 30</a>
            </form>
        </div>
    </div>





    <div class="mainBlock">

        <p><i>Trykk på et tidspunkt for å booke rom.</i></p>
        <?php

        // Get all filter variables
        $querySizes = array(2, 3, 4);

        $queryProjector = "%";

        /*if (isset($_POST['prevDay'])) // Previous day
        {
            $date = date('Y-m-d', strtotime(' +1 day'));
        }
        if (isset($_POST['nextDay'])) // Previous day
        {
            $date = date('Y-m-d', strtotime(' +1 day'));
        }*/


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
                $bgColor = "ddd";
                $rentedVal = 0; // Must use an int, when using a boolean the function would mess up for some reason. Yay for never having touched javascript before...
                if ($isRented)
                {
                    $bgColor = "999";
                    $rentedVal = 1;
                }
                $renterIsLoggedIn = false;
                $timeInfo = "Ledig!";
                if ($isRented)
                {
                    if ($isRented->BrukerId == getUserIdFromName($_SESSION['user'], $db)) // Rented by logged in user
                    {
                        $timeInfo = "Du leier her!";
                        $renterIsLoggedIn = true;
                        $bgColor = "7fc79a";
                    }
                    else // Rented by someone else
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
                                echo "<a href='avbestill.php?date=" . $queryDate . "&roomid=" . $roomId . "&time=" . $isRented->Tidspunkt . "' class='button'>Avbestill</a>";
                            }
                            else if (!$isRented)
                            {
                                ?>
                                <form method="post" action="bekreftelse.php?date=<?php echo $queryDate . "&time=" . $timestamp . ":00&roomid=" . $roomId ?>">
                                    <label>Antall timer <input type="number" min="1" max="8" value="1" name ="hourinput" required=""></label>
                                    <input type="submit" value="Book rom" name="booking">
                                </form>
                                <?php
                            } ?>
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
            echo "<p id='noResults'>Ingen resultater passer til søket... Prøv igjen med et breder søk.</p>";
        }
        ?>
    </div>
</div>

</body>
</html>