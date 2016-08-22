<?php
	session_start();
?>
<!DOCTYPE HTML>
<head></head>
<body>
	<navbar>
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
						<a href="/panier.php">Mon Panier(<?=$_SESSION['panierQte'])?>)</a>
						<a href="/logout.php">se d&eacute;connecter</a>
					<?php
				} else {
					//Visiteur non authentifié
					?>
						<a href="/profile.php">se d&eacute;connecter</a>
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
		