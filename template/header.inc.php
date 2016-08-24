<?php
        session_start();
	require_once('config.php');
	require_once('functions.php');
	//$db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=UTF8",DB_USER, DB_PASS);
?>
<!DOCTYPE HTML>
<head>
	<meta charset="UTF-8"/>
	<link rel="stylesheet" type="text/css" href="css/style.css"/>
        <?php if($_SESSION['role'] === 'admin'){?>
            <link rel="stylesheet" type="text/css" href="css/styleAdmin.css">
        <?php } ?>
</head>
<body>
	<nav>
		<span>
                    <a href="index.php" class="logo">INFO++</a>
		</span>
		<span>
			<div class="menuOption">
			<?php 
				/*if($_SESSION['role'] === 'admin'){
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
				}*/
			?>
						<a href="/panier.php">Mon Panier(1)</a>
						<a href="/logout.php">se d&eacute;connecter</a>
			</div>
			<div class="navOption">
				<a class="nav red" href="catalogue.php">Catalogue</a>
				<a class="nav yellow" href="profile.php">Profil</a>
				recherche
			</div>
		</span>
	</nav>
        <div class="container">
