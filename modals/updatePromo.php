<?php 

//Get promotion list for dropdown menu in addPromo and updatePromo modals
		$promoListQuery = 'SELECT * FROM promotion ORDER BY promotion_titre';
		$promoList= $db->query($promoListQuery)->fetchAll();

?>

<div class="updatePromoContent">
   <h2>Ajouter la période et un code pour appliquer la promotion choisie.</h2>
   <h3>Le code n'est pas obligatoire et ne sera pas exigé si le champ est vide.</h3>
   <form id="updatePromo" action="service.php" method="post">
    <input type="hidden" id="updateServiceId" name="serviceId" value="" />
    <input type="hidden" id="updatePromoServiceId" name="promoServiceId" value="" />
       <div class="addPromoWrapper">
            <div id="updatePromoNb" class="updatePromoNb">0%</div>
            <div class="promoTitleWrapper">
                <div class="promoListMenu">
                    <select id="updatePromoName" name="promoName" onchange="changePercentage()">
                        <?php foreach($promoList as $promo) {?>
                            <option id="promo<?=$promo['pk_promotion']?>" data-percent="<?=$promo['rabais']?>" value="<?=$promo['pk_promotion']?>"><?=$promo['promotion_titre']?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
       </div>
       <div class="updatePromoDateWrapper">
            <p>Période de la promotion</p> <br/>
            <input type="date" id="updateDebut" name="debut"/> à <input type="date" id="updateFin" name="fin"/>
            <p>Entrer un code s'il est requis pour appliquer la promotion lors de la création de la facture.</p>
            <input type="text" name="codePromo" id="updateCodePromo" />
       </div>
       <div class="updatePromoSubmitWrapper"><input type="submit" name="updatePromo" value="Confirmer" onclick='updatePromoTest()'/></div>
   </form>
</div>

<script>
function changePercentage() {
    var e = document.getElementById('updatePromoName');
    var promo = e.children[e.selectedIndex];
    var percent = promo.getAttribute("data-percent");
    document.getElementById('updatePromoNb').innerHTML = percent * 100 + "%";
}
</script>
