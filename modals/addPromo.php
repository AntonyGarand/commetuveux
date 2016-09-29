<div class="addPromoContent">
   <h2>Ajouter la période et un code pour appliquer la promotion choisie.</h2>
   <h3>Le code n'est pas obligatoire et ne sera pas exigé si le champ est vide.</h3>
   <form id="addPromo">
	   <div class="addPromoWrapper">
			<div id="addPromoNb" class="addPromoNb">0%</div>
			<div class="promoTitleWrapper">
				<div class="promoListMenu">
					<select id="promoName" name="promoName" disabled>
						<option selected disabled>Choisir le titre</option>
						<?php foreach($promotions as $promo) {?>
							<option  id="promo<?=$promo['pk_promotion']?>" data-percent="<?=$promo['rabais']?>" value="<?=$promo['pk_promotion']?>" ><?=$promo['promotion_titre']?></option>
						<?php } ?>
					</select>
				</div>
			</div>
	   </div>
	   <div class="addPromoDateWrapper">
			<p>Période de la promotion</p> <br/>
			<input type="date" name="debut" placeholder="Date de début"/> à <input type="date" name="fin" placeholder="Date de fin"/>
			<p>Entrer un code s'il est requis pour appliquer la promotion lors de la création de la facture.</p>
			<input type="text" name="codePromo" />
	   </div>
	   <div class="addPromoSubmitWrapper"><input type="submit" name="addPromo" value="Confirmer" onclick="applyToAll()"/></div>
   </form>
</div>