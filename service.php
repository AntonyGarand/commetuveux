<!-- /**************************************************************************************************/
/* Fichier ...................... : service.php */
/* Titre ........................ : Lab Web */
/* Auteur ....................... : Amélie Frappier et Antony Garand */
/* Date de création ............. : 2016-08-22 */
/* Date de mise en ligne ........ : Jamais */
/* Date de mise à jour .......... : 2016-08-22 */
/*******************************************************************************************************/
-->
<?php 
require_once('template/header.inc.php');
if($_SESSION['role'] !== 'admin'){
    //TODO: Replace header with head and navbar, then do send a location header to redirect
    echo "Forbidden";
    die('<script>window.location.href="index.php"</script>');
}
$productsQuery = "SELECT * FROM service ORDER BY pk_service";
$products = $db->query($productsQuery)->fetchAll();
foreach($products as $product){
    ?>
    <div class="service">
    <span class="serviceImage">
        <img src="<?=urlencode($product['image'])?>" alt="image de <?=$product['service_titre']?>"/>
    </span>
    <span class="serviceContentAdmin">
        <p class="serviceTitle"><?=htmlspecialchars($product['service_titre'])?></p>
        <p class="serviceDescription"><?=htmlspecialchars($product['service_description'])?></p>
        <span class="servicePriceWrapper"><p class="servicePrice">Tarif : <?=intval($product['tarif'])?></p></span>
        <span class="serviceLengthWrapper"><p class="serviceLength">Durée : <?=intval($product['durée'])?> h</p></span>
        <span class="serviceAddToCartWrapper"><button class="addToCartBtn" onclick="addToCart(<?=intval($product['pk_service'])?>)"></button></span>
    </span>
    <span class="servicePromoAdmin">
        <span class="servicePromoText">
            <p class="promotion">Promotions :</p>
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
                    AND fk_service = ' . intval($product['pk_service']);
            $promotions = $db->query($promotionQuery)->fetchAll();
            foreach($promotions as $promotion){
                ?>
                <div class="promotion" id="promotion<?=$promotion['id']?>">
                    <p class="promoValue"><?=floatval($promotion['rabais'])*100?>%</p>
                    <div class="promotionBoxBottom">PROMO CODE</div><?php //TODO: Faire cette boite avec un ::after et content="PROMO CODE" à la place? ?>
                </div>
                <?php
            }
            ?>
            <p>＋</p>
            <span class="serviceShareIcons"><!--TODO--></span>
        </span>
    </span>
    </div>
    <?php
}
?>
