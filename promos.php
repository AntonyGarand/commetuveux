<?php 
require_once 'template/header.inc.php';
if ($_SESSION['role'] !== 'admin') {
    //TODO: Replace header with head and navbar, then do send a location header to redirect
    echo 'Forbidden';
    header('Location: index.php');
    die();
}

//If user deletes promotion
if (isset($_POST['deletedID'])) {
    $today = date('Y-m-d 00:00:00');
    $deactivatePromoQuery = 'UPDATE ta_promotion_service SET date_fin=:date WHERE fk_promotion=:promoID';
    $stmt = $db->prepare($deactivatePromoQuery);
    $stmt->bindParam(':date', $today);
    $stmt->bindParam(':promoID', $_POST['deletedID']);
    if (!($stmt->execute())) {
        $errors[] = "Impossible de désactiver la promotion dans la base de données.";
    }
}

//If user applies promotion to all services 
if (isset($_POST['applyPromoId'])) {
    $required = array('applyPromoId', 'debut', 'fin');
    $errors = validatePost($required); 	//validate if array is not empty
	if (empty($errors)) {
		if (strtotime($_POST['debut']) < strtotime($_POST['fin'])) {
	
			$allServicesQuery = 'SELECT pk_service FROM service';
			$allServices = $db->query($allServicesQuery)->fetchAll();
			$applyPromoQuery = 'INSERT INTO ta_promotion_service (fk_promotion, fk_service, date_debut, date_fin, code) VALUES(:promo, :service, :debut, :fin, :code)';
			foreach($allServices as $service) {
				$stmt = $db->prepare($applyPromoQuery);
				$stmt->bindParam(':debut', $_POST['debut']);
				$stmt->bindParam(':fin', $_POST['fin']);
				$stmt->bindParam(':promo', $_POST['applyPromoId']);
				$stmt->bindParam(':service', $service['pk_service']);
				$stmt->bindParam(':code', isset($_POST['code']) ? $_POST['code'] : '');
				if (!($stmt->execute())) {
					$errors[] = "Impossible d'appliquer cette promotion à tous les services.";
					break;
				}
			}
			
		}
		else {
			$errors[] = "La date de début doit être plus petite que la date de fin";
		}
	}
	else {
		$errors[] = "Veuillez saisir tous les champs requis.";
	}
	 
}

//If user updates promotion
if (isset($_POST['updatePromo'])) {
    $required = array('promoTitle', 'promoRabais');
    $errors = validatePost($required); 	//validate if array is not empty
	if (empty($errors)) {
		//validate if rebate is valid
		$_POST['promoRabais'] = floatval($_POST['promoRabais']);
		if (is_numeric($_POST['promoRabais']) && $_POST['promoRabais'] < 100 && $_POST['promoRabais'] > 0) {
			//insert value in db
			$rebate = $_POST['promoRabais'] / 100;
			$updatePromo = "UPDATE promotion SET promotion_titre=:title, rabais=:rebate WHERE pk_promotion=:id";
			$stmt = $db->prepare($updatePromo);
			$stmt->bindParam(':title', $_POST['promoTitle'], PDO::PARAM_STR);
			$stmt->bindParam(':rebate', $rebate);
			$stmt->bindParam(':id', $_POST['promoId']);
			if (!($stmt->execute())) {
				$errors[] = "Impossible de sauvegarder la promotion dans la base de données.";
				die(print_r($stmt->errorInfo()));
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

require_once 'template/navbar.inc.php'; 

?>

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
			<div class="gestionPromoMenu">
				<div class="cornerContentWrapper" id="cornerMenu<?=$promotion['pk_promotion']?>" tabindex="<?=$promotion['pk_promotion'] /*For the onblur to work*/?>" onblur="setTimeout(function(item){item.style.display='none';},250, this);">
					<a href="" onclick="openModal(<?=$promotion['pk_promotion']?>);return false;" >Appliquer à tous les services</a><br/>
					<a href="promos.php?updateid=<?=$promotion['pk_promotion']?>">Modifier la promotion</a><br/>
					<a href="" onclick="deleteItem(<?=$promotion['pk_promotion']?>);return false;">Désactiver la promotion</a>
				</div>
				<div class="corner" onclick="showMenu(<?=$promotion['pk_promotion']?>)"></div>
			</div>
			
			<?php if (isset($_GET['updateid']) && $_GET['updateid'] == $promotion['pk_promotion']) { ?>
				<form id="updatePromo" name="updatePromo" action="promos.php" method="post">
					<input type="hidden" name="promoId" value="<?=$promotion['pk_promotion']?>"/>
					<input type="text" name="promoTitle" value="<?=$promotion['promotion_titre']?>"/>
					<input type="text" name="promoRabais" value="<?=($promotion['rabais'] * 100)?>" /> %
					<input type="submit" name="updatePromo" value="Confirmer" />
				</form>
			<?php }
			else { ?>
				<div class="hidden"><?=$promotion['pk_promotion']?></div> 
				<div id="gestionPromoTitre" class="gestionPromoTitre"><?=$promotion['promotion_titre']?></div>
				<div id="gestionPromoRabais" class="gestionPromoRabais"><?=($promotion['rabais'] * 100) . '%'?></div>
			<?php } ?>
			
		</div>
	
<?php } ?>       

<!-- add promo to all modal windows -->
<div id="applyToAllModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">x</span>
    <?php include('modals/addPromo.php'); ?>
  </div>

</div>                              

<?php if (isset($_GET['add']) && $_GET['add']) { ?> 
	<div class="gestionPromoContent">
		<form id="addPromo" action="promos.php" method="post">
			<input name="newPromoTitle" placeholder="Titre" type="text"/>
			<input name="newPromoRebate" placeholder="Rabais" type="text"/> %
			<input type="submit" name="addPromo" value="Confirmer"/>
		</form>
	</div>
<?php } ?>

<script src="script/promos.js"></script>
