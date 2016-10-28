<?php 
require_once 'template/header.inc.php';
if ($_SESSION['role'] !== 'admin') {
    die(header('Location: index.php'));
}

//User opens modal to modify promotion
if(isset($_GET['updatePromoId'])) {
	$oldPromoQuery = "SELECT * FROM ta_promotion_service WHERE pk_promotion_service=:promoId";
	$stmt = $db->prepare($oldPromoQuery);
	$stmt->bindParam(':promoId', $_GET['updatePromoId']);
	if ($stmt->execute()) {
	
		//Fetch current promo
		$oldPromo = $stmt->fetchAll();
		
		//Get promotion list for dropdown menu
		$promotionsQuery = 'SELECT * FROM promotion ORDER BY promotion_titre';
		$promotions = $db->query($promotionsQuery)->fetchAll();
		
		//Send info as JSON to fill out modal
		echo json_encode($oldPromo);
	}
}

//If user deletes promotion
if (isset($_POST['promoID'])) {
	$today = date('Y-m-d 00:00:00');
    $deactivatePromoQuery = 'UPDATE ta_promotion_service SET date_fin=:date WHERE pk_promotion_service=:promoID';
    $stmt = $db->prepare($deactivatePromoQuery);
	$stmt->bindParam(':date', $today);
    $stmt->bindParam(':promoID', $_POST['promoID']);
    if (!($stmt->execute())) {
        $errors[] = "Impossible de désactiver la promotion dans la base de données.";
    }
}

//If user updates a given promotion 
if (isset($_POST['updateId'])) {
    $required = array('updateId', 'debut', 'fin');
    $errors = validatePost($required); 	//validate if array is not empty
	if (empty($errors)) {
		if (strtotime($_POST['debut']) < strtotime($_POST['fin'])) {
			$updatePromoQuery = 'UPDATE ta_promotion_service SET(fk_promotion=:promo, date_debut=:debut, date_fin=:fin, code=:code) WHERE fk_promotion=:oldPromo AND fk_service=:service';
			$stmt = $db->prepare($updatePromoQuery);
			$stmt->bindParam(':debut', $_POST['debut']);
			$stmt->bindParam(':fin', $_POST['fin']);
			$stmt->bindParam(':promo', $_POST['newPromoId']);
			$stmt->bindParam(':oldPromo', $_POST['updateId']);
			$stmt->bindParam(':service', $service['pk_service']);
			$stmt->bindParam(':code', isset($_POST['code']) ? $_POST['code'] : '');
			if (!($stmt->execute())) {
				$errors[] = "Impossible d'appliquer cette promotion à tous les services.";
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

$productsQuery = 'SELECT * FROM service ORDER BY pk_service';
$products = $db->query($productsQuery)->fetchAll();
require_once 'template/navbar.inc.php'; ?>

<!--/**************************************************************************************************/
/* Fichier ...................... : service.php */
/* Titre ........................ : Lab Web */
/* Auteur ....................... : Amélie Frappier et Antony Garand */
/* Date de création ............. : 2016-08-22 */
/* Date de mise en ligne ........ : Jamais */
/* Date de mise à jour .......... : 2016-08-22 */
/*******************************************************************************************************/
-->
<div class="modal">
    <div class="modal-content">
        <span class="close" style="cursor: pointer;" onclick="document.getElementsByClassName('modal')[0].style.display = 'none';">x</span>
        <div id="modalFrame"> </div>
    </div>
</div>
<div class="addNewService"><a href="creerService.php">Ajouter un nouveau service</a></span></div>
<?php
foreach ($products as $product) {
?>
    <div class="service">
        <div class="serviceMenu">
            <div class="cornerContentWrapper" id="cornerMenu<?=$product['pk_service']?>" tabindex="<?=$product['pk_service'] /*For the onblur to work*/?>" onblur="setTimeout(function(item){item.style.display='none';},100, this);">
                <p onclick="editService(<?=$product['pk_service']?>)">Modifier le service</p>
                <p onclick="disableService(<?=$product['pk_service']?>)">Désactiver le service</p>
            </div>
            <div class="corner" onclick="showMenu(<?=$product['pk_service']?>)"></div>
        </div>
        <div class="serviceDescWrapper">
            <div class="serviceImageWrapper">
                <img class="serviceImage" src="<?=$product['image']?>" alt="image de <?=$product['service_titre']?>"/>
            </div>
            <div class="serviceContentAdmin">
                <h2 class="serviceTitle"><?=htmlspecialchars($product['service_titre'])?></h2>
                <p class="serviceDescription"><?=htmlspecialchars($product['service_description'])?></p>
                <span class="servicePriceAndLengthWrapper">
                    <span class="servicePriceWrapper"><p class="servicePrice">Tarif : <?=intval($product['tarif'])?>$</p></span>
                    <span class="serviceLengthWrapper"><p class="serviceLength">Durée : <?=intval($product['duree'])?> h</p></span>
                </span>
            </div>
        </div>
    <div class="servicePromoAdmin">
        <span class="servicePromoText">
            <p class="promotionTitleText">Promotions :</p>
        </span>
<?php 
    $promotionQuery = 'SELECT 
        pk_promotion_service as id,
        pk_promotion, 
        code,
        promotion_titre as titre,
        rabais,
        date_debut,
        date_fin
        FROM `promotion` join 
        ta_promotion_service ON fk_promotion = pk_promotion 
        WHERE 
        fk_service = ' .intval($product['pk_service']) . '
        ORDER BY date_debut';
$promotions = $db->query($promotionQuery)->fetchAll();
if (count($promotions) > 0) {
    ?><span class="servicePromoContent"><span class="promotionWrapper"><?php
    foreach ($promotions as $promotion) {
        $startDate = strtotime($promotion['date_debut']);
        $endDate = strtotime($promotion['date_fin']);
        $now = time();
        $class = "inactive";
        //If the end date is older than now
        if($endDate < $now){
            $class = "inactive ended";
        }
        //Or if we're between the start and end date
        if($startDate < $now && $endDate > $now){
            $class = "active";
        } ?>
                <div class="promotion <?=$class?>" id="promotion<?=$promotion['id']?>">
                    <div class="promotionMenuWrapper">
                        <div class="promoCornerContentWrapper" id="cornerPromo<?=$promotion['id']?>" tabindex="<?=$promotion['id']+1000 /*For the onblur to work*/?>"  onblur="setTimeout(function(item){item.style.display='none';},300, this);">
                            <a href="#" onclick="openUpdatePromo(<?=$promotion['id']?>)">Modifier la promotion</a><br/>
                            <a href="#" onclick="deletePromotion(<?=$promotion['id']?>)">Désactiver la promotion</a>
                        </div>
                        <div class="corner" onclick="showPromo(<?=$promotion['id']?>)"></div>
                    </div>
                    <div class="promotionValue">
                        <p class="promoValue"><?=floatval($promotion['rabais']) * 100?>%</p>
                        <div class="promotionBoxBottom"><p class="promocodeText"><?=$promotion['code']?></p></div>
                    </div> 
                </div>
                <?php } ?></span></span><?php } ?>
            <span class="promoPlusWrapper"><p class="promoPlus"><a href="addPromo.php?id=<?=$product['pk_service']?>">✚</a></p></span>
            <div class="serviceShareIcons">
                <img src="img/icones/medias sociaux.jpeg" usemap="#image-map<?=$product['pk_service']?>"/></a>
                <map name="image-map<?=$product['pk_service']?>">
                    <area target="_blank" alt="Partager sur Twitter" title="Partager sur Twitter" href="https://twitter.com/intent/tweet?text=Allez%20voir%20nos%20cours%20sur&url=http://weba.cegepsherbrooke.qc.ca/~tia16001/catalogue.php&hashtags=infoplusplus,coursInfo," coords="42,21,19" shape="circle">
                    <area target="_blank" alt="Partager sur Facebook" title="Partager sur Facebook" data-href="http://weba.cegepsherbrooke.qc.ca/~tia16001/catalogue.php" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fweba.cegepsherbrooke.qc.ca%2F%7Etia16001%2Fcatalogue.php&amp;src=sdkpreparse" coords="22,57,18" shape="circle">
                    <area target="_blank" alt="Partager sur Google+" title="Partager sur Google+" href="https://plus.google.com/share?url=http://weba.cegepsherbrooke.qc.ca/~tia16001/catalogue.php" coords="65,56,17" shape="circle">
                </map>
            </div>
        </div>
    </div>
    <?php } ?>
	
	<!-- add promo to all modal windows -->
<div id="updatePromoModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">x</span>
    <?php include('modals/updatePromo.php'); ?>
  </div>

</div>       
	
    <script src="script/service.js"></script>
<?php include("template/footer.inc.php"); ?>
