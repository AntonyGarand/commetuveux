<?php 
require_once 'template/header.inc.php';
if ($_SESSION['role'] !== 'admin') {
    //TODO: Replace header with head and navbar, then do send a location header to redirect
    echo 'Forbidden';
    header('Location: index.php');
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
--><?php
foreach ($products as $product) {
    ?>
    <div class="service">
		<div class="serviceDescWrapper">
			<div class="serviceImageWrapper">
				<img class="serviceImage" src="<?=$product['image']?>" alt="image de <?=$product['service_titre']?>"/>
			</div>
			<div class="serviceContentAdmin">
				<h2 class="serviceTitle"><?=htmlspecialchars($product['service_titre'])?></h2>
				<p class="serviceDescription"><?=htmlspecialchars($product['service_description'])?></p>
				<span class="servicePriceAndLengthWrapper">
					<span class="servicePriceWrapper"><p class="servicePrice">Tarif : <?=intval($product['tarif'])?>$</p></span>
					<span class="serviceLengthWrapper"><p class="serviceLength">Durée : <?=intval($product['durée'])?> h</p></span>
				</span>
			</div>
		</div>
		<div class="servicePromoAdmin">
			<span class="servicePromoText">
				<p class="promotionTitleText">Promotions :</p>
			</span>
			<span class="servicePromoContent">
				<?php 
                $promotionQuery = 'SELECT 
						pk_promotion as id, 
						promotion_titre as titre,
						rabais  
					FROM `promotion` join 
						ta_promotion_service ON fk_promotion = pk_promotion 
					WHERE 
						NOW() < date_fin 
						AND fk_service = ' .intval($product['pk_service']);
    $promotions = $db->query($promotionQuery)->fetchAll();
    if (count($promotions) > 0) {
        ?><span class="promotionWrapper"><?php
                foreach ($promotions as $promotion) {
                    ?>
					<div class="promotion" id="promotion<?=$promotion['id']?>">
						<p class="promoValue"><?=floatval($promotion['rabais']) * 100?>%</p>
						<div class="promotionBoxBottom"><p class="promocodeText">PROMO CODE</p></div><?php //TODO: Faire cette boite avec un ::after et content="PROMO CODE" à la place??>
					</div>
					<?php 
                }
    } ?>
				</span>
				<span class="promoPlusWrapper"><p class="promoPlus">✚</p></span>
				<div class="serviceShareIcons"><a href="http://facebook.com" target="_blank"><img src="img/icones/medias sociaux.jpeg" alt="Partager sur les médias sociaux..."/></a></div>
			</span>
			
		</div>
    </div>
    <?php

}
?>
