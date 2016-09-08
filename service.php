<?php 
require_once 'template/header.inc.php';
if ($_SESSION['role'] !== 'admin') {
    //TODO: Replace header with head and navbar, then do send a location header to redirect
    echo 'Forbidden';
    die(header('Location: index.php'));
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
<div class="addNewService"><a href="service.php?add=true">Ajouter un nouveau service</a></span></div>
<?php
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
    <?php 
    $promotionQuery = 'SELECT 
                            pk_promotion as id, 
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
                }
                ?>
                <div class="promotion <?=$class?>" id="promotion<?=$promotion['id']?>">
                    <p class="promoValue"><?=floatval($promotion['rabais']) * 100?>%</p>
                    <div class="promotionBoxBottom"><p class="promocodeText">PROMO CODE</p></div><?php 
                    //TODO: Faire cette boite avec un ::after et content="PROMO CODE" à la place??>
                </div>
                <?php } ?></span></span><?php } ?>
            <span class="promoPlusWrapper"><p class="promoPlus">✚</p></span>
            <div class="serviceShareIcons"><a href="http://facebook.com" target="_blank"><img src="img/icones/medias sociaux.jpeg" alt="Partager sur les médias sociaux..."/></a></div>
        </div>
    </div>
    <?php } 
    include("template/footer.inc.php");
?>
