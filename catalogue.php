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
    <div class="catalogue">
        <div class="serviceImageWrapper">
            <img class="serviceImage" src="<?=$product['image']?>" alt="Image de <?=$product['service_titre']?>"/>
        </div>
        <div class="serviceContent">
            <h2 class="serviceTitle"><?=htmlspecialchars($product['service_titre'])?></h2>
            <p class="serviceDescription"><?=htmlspecialchars($product['service_description'])?></p>
            <div class="servicePriceAndLengthWrapper">
                <span class="servicePriceWrapper"><p class="servicePrice">Tarif : <?=intval($product['tarif'])?>$</p></span>
                <span class="serviceLengthWrapper"><p class="serviceLength">Durée : <?=intval($product['duree'])?> h</p></span>
                <div class="serviceAddToCartWrapper">
                    <button class="addToCartBtn" onclick="addToCart(<?=intval($product['pk_service'])?>)"></button>
                </div>
            </div>
        </div>
    </div>
<?php } 
require_once('template/footer.inc.php');
