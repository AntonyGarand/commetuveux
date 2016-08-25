<?php
    session_start();
	require_once(__DIR__ . '/config.php');
	require_once(__DIR__ . '/functions.php');
	$db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=UTF8",DB_USER, DB_PASS);
?>
<!DOCTYPE HTML>
<head>
	<meta charset="UTF-8"/>
	<link rel="stylesheet" type="text/css" href="css/reset.css"/>
	<link rel="stylesheet" type="text/css" href="css/style.css"/>
        <?php /*if($_SESSION['role'] === 'admin'){?>
            <link rel="stylesheet" type="text/css" href="css/styleAdmin.css">
        <?php }*/ ?>
</head>
<body>

	<nav>
		<!-- Puts the header BG at 100% -->
	</nav>
	
	<div class="container">
	
	<div id="mainMenu">
		<div id="logo">
            <a href="index.php" class="logo"><img src="img/graphiques/logo.png" alt="Info++" /></a>
		</div> <!-- end #logo -->
		<div id="menuNav">
			<div class="menuOption">
			<?php /*
				if($_SESSION['role'] === 'admin'){
					//Admin
					?>
						<a href="/logout.php">se d&eacute;connecter</a>
					<?php
				} elseif ($_SESSION['role'] === 'user') {
					//Usager
					?>
						<a href="/panier.php">Mon Panier(<?=$_SESSION['pannierQte']?>)</a>
						<a href="/logout.php">se d&eacute;connecter</a>
					<?php
				} else {
					//Visiteur non authentifié
					?>
						<a href="/profile.php">s'identifier</a>
					<?php
				} */
			?>
						<a href="panier.php">Mon Panier (1)</a>
						<a href="logout.php">Se d&eacute;connecter</a>
			</div>
			<div class="navOption">
				<a class="nav red" href="catalogue.php">Catalogue</a>
				<a class="nav yellow" href="profil.php">Profil</a>
				<input id="search" type="text" name="search" value="Recherche"/>
			</div>
		</div> <!-- end #menuNav -->
		</div> <!-- end #mainMenu -->