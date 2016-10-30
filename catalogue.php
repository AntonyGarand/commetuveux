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
-->
<div id="modal" class="modal">
    <div class="modal-content">
        <span class="close" style="cursor: pointer;" onclick="document.getElementsByClassName('modal')[0].style.display = 'none';">x</span>
        <div id="modalFrame">
            <input type="hidden" id="cartItemId"/>
            <div id="modalTitle">Title</div>
            <div id="modalPrice">Price</div>
            <div id="modalDescription">Description</div>
            <input type="button" value="Ajouter au panier" onclick="addCartItem()"/>
        </div>
    </div>
</div>
<?php
foreach ($products as $product) {
?>
    <div class="catalogue" id="catalogue<?=$product['pk_service']?>">
        <div class="serviceImageWrapper">
            <img class="serviceImage" src="<?=$product['image']?>" alt="Image de <?=$product['service_titre']?>"/>
        </div>
        <div class="serviceContent">
            <h2 class="serviceTitle" id="serviceTitle<?=$product['pk_service']?>"><?=htmlspecialchars($product['service_titre'])?></h2>
            <p class="serviceDescription" id="serviceDescription<?=$product['pk_service']?>"><?=htmlspecialchars($product['service_description'])?></p>
            <div class="servicePriceAndLengthWrapper">
                <span class="servicePriceWrapper servicePrice" id="servicePrice<?=$product['pk_service']?>">Tarif : <?=intval($product['tarif'])?>$</span>
                <span class="serviceLengthWrapper serviceLength" id="serviceLength<?=$product['pk_service']?>">Durée : <?=intval($product['duree'])?> h</span>
                <div class="serviceAddToCartWrapper">
                    <button class="addToCartBtn" onclick="addToCart(<?=intval($product['pk_service'])?>)"></button>
                </div>
            </div>
        </div>
    </div>
<?php } 
require_once('template/footer.inc.php');
