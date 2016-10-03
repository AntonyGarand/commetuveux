<?php 
require_once 'template/header.inc.php';

if ($_SESSION['role'] !== 'admin' || !isset($_GET['id'])) { //page needs an id to work if form is not submitted, else redirects to index.php
    die(header('Location: index.php'));
}

$serviceId = $_GET['id'];

//form submit
if(isset($_POST['addPromo'])) {

	$required = array('promoName', 'debut', 'fin');
    $errors = validatePost($required); 	//validate if array is not empty
	if (empty($errors)) {
		if (strtotime($_POST['debut']) < strtotime($_POST['fin'])) {
		
			//adjusts query if it requires code
			if (isset($_POST['codePromo'])) {
				$insertNewPromoQuery = 'INSERT INTO ta_promotion_service (fk_promotion, fk_service, date_debut, date_fin, code) VALUES (:promo, :service, :debut, :fin, :code)';
				$stmt = $db->prepare($insertNewPromoQuery);
				$stmt->bindParam(':code', $_POST['codePromo']);
			}
			else {
				$insertNewPromoQuery = 'INSERT INTO ta_promotion_service (fk_promotion, fk_service, date_debut, date_fin) VALUES (:promo, :service, :debut, :fin)';
				$stmt = $db->prepare($insertNewPromoQuery);
			}
			
			$stmt->bindParam(':promo', $_POST['promoName'], PDO::PARAM_STR);
			$stmt->bindParam(':service', $serviceId);
			$stmt->bindParam(':debut', $_POST['debut']);
			$stmt->bindParam(':fin', $_POST['fin']);
			
			if (!($stmt->execute())) {
				$errors[] = "Impossible de sauvegarder la promotion dans la base de données.";
			}
			else {
				die(header('Location: service.php'));
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

//get promotion list
$promoListQuery = 'SELECT * FROM promotion';
$promoList = $db->query($promoListQuery)->fetchAll();

require_once('template/navbar.inc.php');
?>

<!--/**************************************************************************************************/
/* Fichier ...................... : addPromo.php */
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

<div class="addPromoContent">
   <h2>Ajouter la période et un code pour appliquer la promotion choisie.</h2>
   <h3>Le code n'est pas obligatoire et ne sera pas exigé si le champ est vide.</h3>
   <form id="addPromo" action="addPromo.php?id=<?=$serviceId?>" method="post">
	   <div class="addPromoWrapper">
			<div id="addPromoNb" class="addPromoNb">0%</div>
			<div class="promoTitleWrapper">
				<div class="promoListMenu">
					<select id="promoName" name="promoName" onchange="changePercentage()" >
						<option selected disabled>Choisir le titre</option>
						<?php foreach($promoList as $promo) {?>
							<option  id="promo<?=$promo['pk_promotion']?>" data-percent="<?=$promo['rabais']?>" value="<?=$promo['pk_promotion']?>" ><?=$promo['promotion_titre']?></option>
						<?php } ?>
					</select>
				</div>
			</div>
	   </div>
	   <div class="addPromoDateWrapper">
			<p>Période de la promotion</p> <br/>
			<input type="date" name="debut" placeholder="Date de début"/> à <input type="date" name="fin" placeholder="Date de fin"/>
			<p>Entrer un code s'il est requis pour appliquer la promotion lors de la création de la facture.</p>
			<input type="text" name="codePromo" />
	   </div>
	   <div class="addPromoSubmitWrapper"><input type="submit" name="addPromo" value="Confirmer" /></div>
   </form>
</div>

<script>
	function changePercentage() {
		var e = document.getElementById('promoName');
		var promo = e.children[e.selectedIndex];
		var percent = promo.getAttribute("data-percent");
		document.getElementById('addPromoNb').innerHTML = percent * 100 + "%";
	}
</script>
