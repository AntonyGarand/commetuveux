<?php
	session_start();
	require_once('config.php');
	require_once('functions.php');
	$db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=UTF8",DB_USER, DB_PASS);
?>
<!DOCTYPE HTML>
<head>
	<meta charset="UTF-8"/>
	<link rel="stylesheet" type="text/css" href="css/style.css"/>
</head>
<body>
	<nav>
		<span>
			<img src="/TODO/LOGO.png" alt="TODO"/>
		</span>
		<span>
			<div>
			<?php 
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
				}
			?>
			</div>
			<div>
				<a href="catalogue.php">Catalogue</a>
				<a href="profile.php">Profil</a>
				recherche
			</div>
		</span>
	</nav>
