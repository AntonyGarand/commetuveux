<!-- /**************************************************************************************************/
/* Fichier ...................... : login.php */
/* Titre ........................ : Lab Web */
/* Auteur ....................... : Amélie Frappier et Antony Garand */
/* Date de création ............. : 2016-08-22 */
/* Date de mise en ligne ........ : Jamais */
/* Date de mise à jour .......... : 2016-08-22 */
/*******************************************************************************************************/
--><?php 
require_once('template/header.inc.php');
if(isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] === true){ //Already logged in, 
?>
    <script>window.location.href("index.php");</script>
<?php
}
if(isset($_POST['login'])){
    //User trying to log in, we need to validate the form then try logging him in
    $required = array('email','password');
    $errors = validatePost($required);
    //No errors, check if the user/pass exists
    if(empty($errors)){
        $validUserQuery = "SELECT * FROM utilisateur WHERE courriel = :email AND mot_de_passe = :password";
        $validUserStmt = $db->prepare($validUserQuery);
        if($validUserStmt->execute(array(
            ':email'=>$_POST['email'],
            ':password'=>$_POST['password']))
        ){
            if($validUserStmt->rowCount() === 1){
                //Valid email/password!
                $user = $validUserStmt->fetch();
                $_SESSION['email'] = $user['courriel'];
                $_SESSION['role'] = intval($user['administrateur'])===1 ? 'admin' : 'user';
                $_SESSION['isLoggedIn'] = true;
                //TODO: Split header/navbar and send header "Location: index.php"; instead?
                ?>
                    <script>window.location.href("index.php");</script>
                <?php
            } else {
                $errors[] = "Le courriel ou mot de passe est invalide!";
            }
        } else {
            $errors[] = "Un problème technique est survenu!<br/>Veuillez réessayer plus tard.";
        }
    } else {
        //Check if the email/pass is missing and show errors accordingly in the form
        $errors[] = "Il faut entrer un courriel et un mot de passe!";
    }
}
?>
<div class="loginWrapper">
	<h2>Veuillez vous identifier pour avoir la possibilité d'acheter des formations</h2>
	<?php if(!empty($errors)){ ?>
		<p class="error"><?=implode('<br/>',$errors)?></p>
	<?php } ?>
	<form id="loginForm" action="login.php" method="post">
		<input 
		type="email" name="email" placeholder="Courriel" pattern=".{5,100}" title="Veuillez entre 5 et 100 caractères" required <?php
		if(isset($_POST['email']) && is_string($_POST['email'])){
			echo "value=\"" . htmlspecialchars($_POST['email']) . "\" ";
		}?>/> <br/>
		<input type="password" name="password" placeholder="Mot de passe" pattern=".{5,100}" title="Veuillez entre 5 et 100 caractères" required /> <br/>
		<a href="#TODO">Mot de passe oublié</a> <br/>
		<input class="button" name="login" type="submit" value="Connexion"/>
		<a class="button" href="profile.php">S'inscrire</a> <br/>
		<img src="#facebookTodo" alt="Se connecter avec facebook"/>
	</form>
</div> <!-- end .loginWrapper-->
<?php include ('template/footer.inc.php'); ?>
