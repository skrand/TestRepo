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

    $queryDate = date('Y-m-d');
    $queryDate = '2015-03-11';

    $date = date('Y-m-d');
    $date = isset($_POST['date']) ? $_POST['date'] : date('Y-m-d');
    $prevDate = date('Y-m-d', strtotime($date .' -1 day'));
    $nextDate = date('Y-m-d', strtotime($date .' +1 day'));
    ?>

<form method="post">
    <input type="submit" value="<" name="datePrev">
    <label><?php echo $date; ?></label>
    <input type="submit" value=">" name="dateNext">
</form>

    <?php


    if (isset($_POST['filter']))
    {
        if (isset($_POST["size1"]))
            $querySizes[0] = 2;
        if (isset($_POST["size2"]))
            $querySizes[1] = 3;
        if (isset($_POST["size3"]))
            $querySizes[2] = 4;
        if (isset($_POST['projector']))
        {
            $queryProjector = 'j';
        }

    }
    if (isset($_POST['dateNext']))
    {
        echo "<b>_next_</b>";
        $next = date('Y-m-d', strtotime($date .' +1 day'));
        $date = $next;
    }
    if (isset($_POST['datePrev']))
    {
        $prev = date('Y-m-d', strtotime($date .' -1 day'));
        $date = $prev;
        echo "<b>_prev_</b>";
    }
    $queryDate = $date;
    $queryDate = 'AND l.Dato LIKE %-%-%';
    //echo $queryDate;

    remove sql leieavrom on expire
    // Query
    /*
     * Få Rom oversikt og LeieAvRom oversikt (for nåværende dato) i to forskjellige spørringer
     * Så bruk rom oversikten til å vise rom, og LeieAvRom til å vise ledighet
     * foreach (room)
     *      display room info
     *      foreach (rent of this room) [query to get the rents uses the date]
     *          display red or green element
     * */
    $roomOverviewQuery = "";
    $rentOverviewQuery = "";
    $sql = $db->prepare("SELECT r.RomId, r.Beskrivelse, r.Storrelse, r.Prosjektor, l.Dato, l.Tidspunkt, l.AntallTimer, b.Brukernavn, b.Epost FROM Rom AS r
LEFT JOIN LeieAvRom AS l ON l.RomId = r.RomId
LEFT JOIN Bruker AS b ON l.BrukerId = b.BrukerId WHERE r.Storrelse IN (:size1, :size2, :size3) AND r.Prosjektor LIKE :projector");
//" AND l.Dato LIKE :date;");//" AND l.Dato LIKE :date;");
    // Set parameters
    $sql->setFetchMode(PDO::FETCH_OBJ);
    $sql->bindParam(':size1', $querySizes[0], PDO::PARAM_STR);
    $sql->bindParam(':size2', $querySizes[1], PDO::PARAM_STR);
    $sql->bindParam(':size3', $querySizes[2], PDO::PARAM_STR);
    $sql->bindParam(':projector', $queryProjector, PDO::PARAM_STR);
    //$sql->bindParam(':date', $queryDate, PDO::PARAM_STR);

    $sql->execute();


    // Run through results of query
    $uniqueRooms = array();
    while($rom = $sql->fetch())
    {
        // Check if room hasn't been displayed yet
        $roomId = $rom->RomId;
        $exists = false;
        foreach($uniqueRooms as $val) // Loop through rooms
        {
            if ($roomId === $val) // If current roomId is the same as the iterated roomId
            {
                //
                $exists = true;
                break;
            }
        }
        if ($exists) // Skip this iteration (aka skip this room)
        {
            continue;
        }
        $uniqueRooms[] = $roomId; // Add roomId to displayed rooms

        // Display room
        echo "<div class='roomBlock'>";
        $prosjektor = "JA";
        if ($rom->Prosjektor === "n")
        {
            $prosjektor = "NEI";
        }
        echo "<h3>" . $rom->Beskrivelse . "</h3>";
        echo "<p>Størrelse <span class='infoBlock'>" . $rom->Storrelse . "</span>" . " Prosjektor <span class='infoBlock'>" . $prosjektor . "</span></p>";

        // Hours overview
        for ($i = 8; $i <= 20; $i ++)
        {
            $startTime = $rom->Tidspunkt;
            $hour = $i * 3600;
            $hours = $rom->AntallTimer;

            $start = strtotime($rom->Tidspunkt);
            $end = $start + (3600 * $hours);
            $cur = strtotime(date('H:i:s', $hour));

            // Set background color
            $bgColor = '#0fa';
            if ($cur > $start + 1 && $cur < $end + 1)
            {
                $bgColor = '#f55';
            }

            // Display element
            echo "<a href='#' class='timeBlock' style='background-color: " . $bgColor . ";'>" . str_pad($i, 2, '0', STR_PAD_LEFT) . ':00' . "</a>";
        }
        echo "</div>";
    }

    // Check if any rows were returned
    if ($sql->rowCount() <= 0)
    {
        echo "<br /><br />No results...";
    }
    ?>

</body>
</html>