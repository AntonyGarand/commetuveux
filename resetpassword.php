<?php 
require_once 'template/header.inc.php';
if (isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] === true) { //Already logged in,
    die(header('Location: index.php'));
}

if (isset($_POST['resetPassword'])) {
	$required = array('email');
    $errors = validatePost($required);
	if (empty($errors)) {
		//Validate if user exists in database
		$mailQuery = "SELECT pk_utilisateur, courriel FROM utilisateur WHERE courriel='" . $_POST['email'] . "'";
		$mail = $db->query($mailQuery)->fetchAll();
		if (count($mail) > 0) {
			
			//Generates unique token and send mail to user for password reset
			$pass = generatePassword($mail[0]);
			if ($pass != null) {
				if (sendMail($mail[0])) {
				//Redirect to login page
				 echo '<script language="javascript">';
				 echo 'alert("Un nouveau mot de passe temporaire vous sera envoyé par courriel. Veuillez vous reconnecter et choisir un nouveau mot de passe lorsque  vous vous serez identifié de nouveau.");'; 
				 echo 'document.location.href="login.php";';
				 echo '</script>';
				}
				else {
					$errors[] = "Courriel non envoyé";
				}
			}
			
			
			
		}
		else {
			$errors[] = "Cet utilisateur n'existe pas dans notre base de données. Si vous êtes un nouvel utilisateur, veuillez vous inscrire.";
		};
		
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
