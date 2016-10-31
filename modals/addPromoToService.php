<div class="addPromoContent">
   <h2>Ajouter la période et un code pour appliquer la promotion choisie.</h2>
   <h3>Le code n'est pas obligatoire et ne sera pas exigé si le champ est vide.</h3>
   <form id="addPromo">
    <input type="hidden" id="serviceId" name="serviceId" value="" />
	   <div class="addPromoWrapper">
			<div id="addPromoNb" class="addPromoNb">0%</div>
			<div class="promoTitleWrapper">
				<div class="promoListMenu">
					<select id="promoName" name="promoName">
						<?php foreach($promoList as $promo) {?>
							<option id="promo<?=$promo['pk_promotion']?>" data-percent="<?=$promo['rabais']?>" value="<?=$promo['pk_promotion']?>"><?=$promo['promotion_titre']?></option>
						<?php } ?>
					</select>
				</div>
			</div>
	   </div>
	   <div class="addPromoDateWrapper">
			<p>Période de la promotion</p> <br/>
			<input type="date" id="debut" name="debut"/> à <input type="date" id="fin" name="fin"/>
			<p>Entrer un code s'il est requis pour appliquer la promotion lors de la création de la facture.</p>
			<input type="text" name="codePromo" id="codePromo" />
	   </div>
	   <div class="addPromoSubmitWrapper"><input type="submit" name="addPromo" value="Confirmer" onclick="addPromotion()"/></div>
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