<?php 
require_once 'template/header.inc.php';
if ($_SESSION['role'] !== 'admin') {
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
                <p onclick="modifierService(<?=$product['pk_service']?>)">Désactiver le service</p>
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
                        <div class="promoCornerContentWrapper" id="cornerPromo<?=$promotion['id']?>" tabindex="<?=$promotion['id']+1000 /*For the onblur to work*/?>" onblur="this.style.display='none';">
                            <a href="/modifierPromotion.php?promoId=<?=$promotion['id']?>">Modifier la promotion</a><br/>
                            <a href="/desactiverPromotion.php?promoId=<?=$promotion['id']?>">Désactiver la promotion</a>
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
    <script src="script/service.js"></script>
<?php include("template/footer.inc.php"); ?>
