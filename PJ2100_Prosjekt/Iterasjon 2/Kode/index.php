<?php require 'booking/config.php'; ?>
<DOCTYPE! html>
<html>

<head>
<title>Westerdals Oslo ACT - Forside</title>
<meta charset="utf-8">
<link rel="stylesheet" href="css/stylesheet.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
	<div id="mainDiv">
		<!-- logoen-->
		<div id="logoDiv">
			<a href="index.php"><img src="images/logo3.png"/></a>
		</div>
		<!-- menyen -->
		<div id="menyDiv">
			<ul>
				<li><a href="https://nith.itslearning.com/Index.aspx">Intranett</a></li>
				<li><a href="html/studier.php">Studier</a> </li>
				<li><a href="html/info.php">Informasjon</a><li>
			</ul>
		</div>
		<!-- infodiv som skal ha informasjon på hver side -->
		<div id="centerDiv">
			<article id="leftArticle">
				<img src="../images/west2.jpg">
			</article>
			
		</div>
		<!--venstre side - login-->
		<aside id="leftAside">
			<p><b>Logg inn for å <br>reservere rom</b></p>
            <?php
            if (isset($_GET['registersuccess']))
            {
                echo "Ny bruker registrert!";
            }
            ?>
			<form action="booking/login.php" method="post">
				<input type="text" placeholder="Brukernavn" name="username" required="required"><br>
				<input type="password" placeholder="Passord" name="password"required="required"><br>
				<br>
				<input type="submit" value="Logg inn">
			</form>
            <?php
            if (isset($_GET['badlogin']))
            {
                echo "<p>Incorrect login.</p>";
            }
            ?>
            <a href="booking/register.php">Registrer ny bruker</a>

		</aside>
		<!-- venstre side CK32 reklamen -->
		<aside id="leftAside2">
			<H1>CK<BR>32<H1>
			<p>Christian Krohgs gate 32</p>
		</aside>

		<!-- footer -->
		<footer><p>Telefon: 22995063, Email: post@westerdals.no, Christian Kroghs gate 32</p>
		</footer>
	</div>

</body>
</html>