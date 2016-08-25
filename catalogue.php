<?php 
require_once 'template/header.inc.php';
$productsQuery = 'SELECT * FROM service ORDER BY pk_service';
$products = $db->query($productsQuery)->fetchAll();

require_once 'template/navbar.inc.php';
?><!-- /**************************************************************************************************/
/* Fichier ...................... : catalogue.php */
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
    <span class="serviceImage">
        <img src="<?=urlencode($product['image'])?>" alt="image de <?=$product['service_titre']?>"/>
    </span>
    <span class="serviceContent">
        <p class="serviceTitle"><?=htmlspecialchars($product['service_titre'])?></p>
        <p class="serviceDescription"><?=htmlspecialchars($product['service_description'])?></p>
        <span class="servicePriceWrapper"><p class="servicePrice">Tarif : <?=intval($product['tarif'])?></p></span>
        <span class="serviceLengthWrapper"><p class="serviceLength">Durée : <?=intval($product['durée'])?> h</p></span>
        <span class="serviceAddToCartWrapper"><button class="addToCartBtn" onclick="addToCart(<?=intval($product['pk_service'])?>)"></button></span>
    </span>
    </div>
<?php } ?>
