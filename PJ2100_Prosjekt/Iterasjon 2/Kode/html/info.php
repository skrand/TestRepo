<DOCTYPE! html>
<html>

<head>
<title>Westerdals Oslo ACT - Forside</title>
<meta charset="utf-8">
<link rel="stylesheet" href="../css/stylesheet.css">
</head>

<body>
	<div id="mainDiv">
		<div id="logoDiv">
			<a href="../index.php"><img src="../images/logo3.png"/></a>
		</div>
		<div id="menyDiv">
			<ul>
				<li><a href="https://nith.itslearning.com/Index.aspx">Intranett</a></li>
				<li><a href="study.php">Studier</a> </li>
				<li><a href="info.php">Informasjon</a><li>
			</ul>
		</div>

		<div id="centerDiv">
			<article id="leftArticle">
				<img src="../images/info.jpg">
			</article>
		</div>

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
		<aside id="leftAside2">
			<H1>CK<BR>32<H1>
			<p>Christian Krohgs gate 32</p>
		</aside>
		<footer><p>Telefon: 22995063, Email: post@westerdals.no, Christian Kroghs gate 32</p>
		</footer>
	</div>

</body>



</html>