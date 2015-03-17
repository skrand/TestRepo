<?php
require 'config.php';
?>

<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Registrer ny bruker</h1>
<form action="registeruser.php" method="post">
    <input type="text" placeholder="Brukernavn" name="username" required="required"><br>
    <?php
    if (isset($_GET['usernametaken']))
        echo "<p class=''>Brukernavn er allerede i bruk.</p>";
    ?>
    <input type="password" placeholder="Passord" name="password" required="required"><br>
    <br>
    <input type="submit" value="Registrer">
</form>
<a href="../index.php">Tilbake til forsiden</a>

</body>
</html>