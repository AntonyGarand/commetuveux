<?php 
require_once 'template/header.inc.php';
if ($_SESSION['role'] !== 'admin') {
    //TODO: Replace header with head and navbar, then do send a location header to redirect
    echo 'Forbidden';
    header('Location: index.php');
}

//If user adds new promotion to DB
if (isset($_POST['addPromo'])) {
	$required = array('newPromoTitle', 'newPromoRebate');
    $errors = validatePost($required); 	//validate if array is not empty
	if (empty($errors)) {
		//validate if rebate is valid
		$_POST['newPromoRebate'] = floatval($_POST['newPromoRebate']);
		if (is_numeric($_POST['newPromoRebate']) && $_POST['newPromoRebate'] < 100 && $_POST['newPromoRebate'] > 0) {
			//insert value in db
			$rebate = $_POST['newPromoRebate'] / 100;
			$newPromoQuery = "INSERT INTO promotion (promotion_titre, rabais) VALUES(:title, :rebate)";
			$stmt = $db->prepare($newPromoQuery);
			$stmt->bindParam(':title', $_POST['newPromoTitle'], PDO::PARAM_STR);
			$stmt->bindParam(':rebate', $rebate);
			if (!($stmt->execute())) {
				$errors[] = "Impossible de sauvegarder le rabais dans la base de données.";
			}
		}
		else {
			$errors[] = "Veuillez saisir un nombre valide pour le rabais entre 0 et 100.";
		}
	}
	else {
		$errors[] = "Veuillez saisir tous les champs.";
	}
}

//Get promotion list
$promotionsQuery = 'SELECT * FROM promotion ORDER BY promotion_titre';
$promotions = $db->query($promotionsQuery)->fetchAll();

require_once 'template/navbar.inc.php'; ?>

<!-- /**************************************************************************************************/
/* Fichier ...................... : promos.php */
/* Titre ........................ : Lab Web */
/* Auteur ....................... : Amélie Frappier et Antony Garand */
/* Date de création ............. : 2016-08-22 */
/* Date de mise en ligne ........ : Jamais */
/* Date de mise à jour .......... : 2016-08-22 */
/*******************************************************************************************************/
-->

<?php if (!empty($errors)) { ?>
            <p class="error"><?=implode('<br/>', $errors)?></p>
	<?php } ?>

<a class="addNewPromo" href="promos.php?add=true">Ajouter une promotion</a>
<?php
	foreach ($promotions as $promotion) {
	?>

		<div class="gestionPromoContent">
			<span class="hidden"><?=$promotion['pk_promotion']?></span> 
			<span class="gestionPromoTitre"><?=$promotion['promotion_titre']?></span>
			<span class="gestionPromoRabais"><?=($promotion['rabais'] * 100) . '%'?></span>
		</div>
	
<?php } ?>

<?php if (isset($_GET['add']) && $_GET['add']) { ?> 
	<div class="gestionPromoContent">
		<form id="addPromo" action="promos.php" method="post">
			<input name="newPromoTitle" placeholder="Titre" type="text"/>
			<input name="newPromoRebate" placeholder="Rabais" type="text"/> %
			<input type="submit" name="addPromo" value="Confirmer"/>
		</form>
	</div>
<?php } ?>