<?php
require_once 'config.php';
require 'verifysession.php';
$db = new DB();
?>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="libs/stylesheet.css">
    <script src="libs/main.js"></script>
</head>
<body>

<!-- Header for siden -->
<?php require 'header.php'; ?>

<!-- Hovedinnholdet -->
<div id="content">
    <!-- Filtrering -->
    <div id="blockFilter" class="mainBlock">
        <h3>Filtrering</h3>
        <div class="center">
            <div class="filterblock">
                <form method="post">
                    <label><input type="checkbox" name="size1" value="2" checked>2 personer</label>
                    <label><input type="checkbox" name="size2" value="3" checked>3 personer</label>
                    <label><input type="checkbox" name="size3" value="4" checked>4 personer</label>
                    <label><input type="checkbox" name="projector" value="j">Må ha prosjektor</label>
                    <input type="submit" value="Filtrer" name="filter">
                </form>
            </div>
                <?php
                    // Set datoen
                    $queryDate = date('Y-m-d');
                    $dateButtonTagDay = "deactivated";
                    if (isset($_GET['dato']))
                    {
                        $queryDate = $_GET['dato'];
                    }
                    // Begrens slik at man ikke kan gå lengre tilbake enn dags dato
                    if (strtotime($queryDate) < strtotime(date('Y-m-d') . ' +1 day'))
                        $dateButtonTagDay = "deactivated";
                    else
                        $dateButtonTagDay = "";

                    $prevDay = date('Y-m-d', strtotime($queryDate .' -1 day'));
                    $nextDay = date('Y-m-d', strtotime($queryDate .' +1 day'));
                    $prevWeek = date('Y-m-d', strtotime($queryDate .' -1 week'));
                    $nextWeek = date('Y-m-d', strtotime($queryDate .' +1 week'));
                    $prevMonth = date('Y-m-d', strtotime($queryDate .' -1 month'));
                    $nextMonth = date('Y-m-d', strtotime($queryDate .' +1 month'));
                ?>
            <div class="filterblock">
                <a href="?dato=<?=$prevMonth;?>" class="<?php echo $dateButtonTagDay ?> button">- 30</a>
                <a href="?dato=<?=$prevWeek;?>" class="button <?php echo $dateButtonTagDay ?>">- 7</a>
                <a href="?dato=<?=$prevDay;?>" class="button <?php echo $dateButtonTagDay ?>">- 1</a>
                <a href="index.php" class="button"><?php echo $queryDate; ?></a>
                <a href="?dato=<?=$nextDay;?>" class="button">+ 1</a>
                <a href="?dato=<?=$nextWeek;?>" class="button">+ 7</a>
                <a href="?dato=<?=$nextMonth;?>" class="button">+ 30</a>
            </div>
        </div>
    </div>

    <!-- Rom -->
    <div class="mainBlock">
        <p><i>Trykk på et tidspunkt for å booke rom.</i></p>
        <?php

        // Get all filter variables
        $querySizes = array(2, 3, 4);
        $queryProjector = "%";
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
        $roomSql = $db->database->prepare("SELECT * FROM Rom WHERE Storrelse IN (:size1, :size2, :size3) AND Prosjektor LIKE :projector;");
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
            $sql = $db->database->prepare("SELECT * FROM LeieAvRom WHERE RomId LIKE :roomId AND Dato LIKE :date;");
            $sql->setFetchMode(PDO::FETCH_OBJ);
            $sql->execute(array(
                'roomId' => $roomId,
                'date' => $queryDate
            ));

            $rented = array_fill(0, 13, null);
            while($rent = $sql->fetch())
            {
                for ($i = 8; $i <= 20; $i ++)
                {
                    $startTime = $rent->Tidspunkt; // Start tidspunkt
                    $hour = $i * 3600; // Time i loopen
                    $cur = strtotime(date('H:i:s', $hour)); // Time i loopen til format

                    if ($startTime == date('H:i:s', $cur)) // Hvis time i loopen er samme som timen i bookingen
                    {
                        // Legg til bookingen i arrayen
                        $rented[$i - 8] = $rent;
                    }
                }
            }

            // Display hours (un)available
            $i = 0;
            foreach($rented as $isRented)
            {
                $bgColor = "ddd";
                $rentButtonClass = " buttonBook";
                $renterIsLoggedIn = false;
                $timeInfo = "Ledig!";

                if ($isRented)
                {
                    if ($isRented->BrukerId == $db->getUserIdFromName($_SESSION['user'])) // Rented by logged in user
                    {
                        $timeInfo = "Du leier her!";
                        $renterIsLoggedIn = true;
                        $bgColor = "7fc79a";
                        $rentButtonClass = "";
                    }
                    else // Rented by someone else
                    {
                        $timeInfo = "Opptatt, leies av " . $db->getUserFromId($isRented->BrukerId)['Brukernavn'];
                        $bgColor = "999";
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
                                <a href="bekreftelse.php?date=<?php echo $queryDate . "&time=" . $timestamp . ":00&roomid=" . $roomId ?>" class="button <?php echo $rentButtonClass ?>">Book</a>
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
            echo "<p id='noResults'>Ingen resultater passer til søket... Prøv igjen med et bredere søk.</p>";
        }
        ?>
    </div>
</div>

</body>
</html>