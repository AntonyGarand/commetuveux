<!DOCTYPE HTML>
<head>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" type="text/css" href="css/reset.css"/>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
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
                <?php if ($_SESSION['role'] === 'admin') { //Admin ?>
                    <a href="logout.php">Se d&eacute;connecter</a>
                <?php } elseif ($_SESSION['role'] === 'user') { //Usager ?>
                    <a href="panier.php">Mon Panier(<?=$_SESSION['pannierQte']?>)</a>
                    <a href="logout.php">Se d&eacute;connecter</a>
                <?php } else { //Visiteur non authentifié ?>
                    <a href="login.php">S'identifier</a>
                <?php } ?>
            </div>
            <div class="navOption">
                <?php if ($_SESSION['role'] === 'admin') { //Admin ?>
                    <a class="nav red" href="service.php">Service</a>
					<a class="nav yellow" href="promos.php">Promotions</a>
                    <a class="nav yellow" href="facture.php">Facture</a>
                    <input type="text" name="search" value="Recherche"/>
                <?php } elseif ($_SESSION['role'] === 'user') { //Usager ?>
                    <a class="nav red" href="catalogue.php">Catalogue</a>
                    <a class="nav yellow" href="profil.php">Profil</a>
                    <input type="text" name="search" value="Recherche"/>
                <?php } ?>
            </div>
        </div> <!-- end #menuNav -->
    </div> <!-- end #mainMenu -->
