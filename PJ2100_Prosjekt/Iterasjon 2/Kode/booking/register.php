<?php
require 'config.php';
?>

<h1>Registrer ny bruker</h1>
<form action="registeruser.php" method="post">
    <input type="text" placeholder="Brukernavn" name="username" required="required"><br>
    <input type="password" placeholder="Passord" name="password" required="required"><br>
    <br>
    <input type="submit" value="Registrer">
</form>

<?php
if (isset($_GET['usernametaken']))
    echo "Username is taken";
?>


