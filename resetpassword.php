<?php 
require_once 'template/header.inc.php';
if (isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] === true) { //Already logged in,
    die(header('Location: index.php'));
}

if (isset($_POST['resetPassword'])) {
	$required = array('email');
    $errors = validatePost($required);
	if (empty($errors)) {
		//TODO: Validate if user exists in database
		//TODO: Send password reset notification by email
		 echo '<script language="javascript">';
		 echo 'alert("Un nouveau mot de passe temporaire vous sera envoyé par courriel. Veuillez vous reconnecter et choisir un nouveau mot de passe lorsque vous vous serez identifié de nouveau.")'; 
		 //echo 'document.location="login.php"';
		 echo '</script>';
	}
	else {
		$errors[] = "Veuillez saisir votre courriel";
	}
}

require_once 'template/navbar.inc.php';
?>
<!-- /**************************************************************************************************/
/* Fichier ...................... : resetpassword.php */
/* Titre ........................ : Lab Web */
/* Auteur ....................... : Amélie Frappier et Antony Garand */
/* Date de création ............. : 2016-09-12 */
/* Date de mise en ligne ........ : Jamais */
/* Date de mise à jour .......... : 2016-09-12 */
/*******************************************************************************************************/
-->

<div class="loginWrapper">

	<?php if (!empty($errors)) { ?>
            <p class="error"><?=implode('<br/>', $errors)?></p>
	<?php } ?>

	<h2>Veuillez saisir votre courriel afin de réinitialiser votre mot de passe.</h2>
		<form id="resetPassword" action="resetpassword.php" method="post">
			<input type="email" name="email" placeholder="Courriel" pattern=".{5,100}" title="Veuillez entre 5 et 100 caractères" required <?php
            if (isset($_POST['email']) && is_string($_POST['email'])) {
                echo 'value="'.htmlspecialchars($_POST['email']).'" ';
            }?>/>
			<input class="button" name="resetPassword" type="submit" value="Envoyer"/>
		</form>
		<a href="login.php">Retour à la page de connexion</a>
	
</div> <!-- end .loginWrapper-->

<?php include 'template/footer.inc.php'; ?>
