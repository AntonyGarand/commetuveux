<!-- /**************************************************************************************************/
/* Fichier ...................... : profil.php */
/* Titre ........................ : Lab Web */
/* Auteur ....................... : Amélie Frappier et Antony Garand */
/* Date de création ............. : 2016-08-22 */
/* Date de mise en ligne ........ : Jamais */
/* Date de mise à jour .......... : 2016-08-22 */
/*******************************************************************************************************/
-->

<?php include('template/header.inc.php'); ?>

<div class="profile-form">

	<form method="post" action="profil.php">
		<fieldset>
			<h2>Remplissez ce formulaire pour créer votre profil</h2>
			<h3>Tous les champs sont obligatoires</h3>
			<input type="text" name="lastName" placeholder="Nom"/>
			<input type="text" name="firstName" placeholder="Prénom"/>
			<input type="text" name="civicNo" placeholder="No. civique"/>
			<input type="text" name="street" placeholder="Rue"/>
			<select name="city">
			  <option value="montreal">Montreal</option>
			  <option value="quebec">Québec</option>
			  <option value="sherbrooke">Sherbrooke</option>
			  <option value="victoriaville">Victoriaville</option>
			</select>
			<input type="text" name="zipCode" placeholder="Code postal"/>
			<input type="text" name="phone" placeholder="Numéro de téléphone"/>
		</fieldset>
		
		<fieldset>
			<h2>Votre courriel servira à vous identifier lors de votre prochaine visite</h2>
			<h3>Votre mot de passe doit contenir un minimum de 8 caractères.</h3>
			<input type="text" name="email" placeholder="Courriel"/>
			<input type="text" name="confirmEmail" placeholder="Confirmation du email"/>
			<input type="password" name="password" placeholder="Mot de passe"/>
			<input type="password" name="confirmPassword" placeholder="Confirmation du mot de passe"/>
			<input type="checkbox" name="sendPromo" value="send" checked="checked"> Souhaitez-vous recevoir les promotions et les nouveautés
		</fieldset>
		
		<input type="submit" value="Confirmer"/>
		
	</form>
	
</div>

<?php include('template/footer.inc.php'); ?>