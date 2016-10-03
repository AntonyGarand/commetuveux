<?php 
require_once 'template/header.inc.php';
if (isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] === true) { //Already logged in,
    die(header('Location: index.php'));
}
if (isset($_POST['login'])) {
    if(isset($_POST['fbEmail'])){
        //Login via facebook

        $required = array('fbEmail','fbToken');
        $errors = validatePost($required);
        if(empty($errors)){
            $token = $_POST['fbToken'];
            $request = "https://graph.facebook.com/me?fields=email&access_token=$token";
            $result = json_decode(file_get_contents($request));
            if(!isset($result->email)){
                $errors = array('Erreur lors de la connection!');
            } else {
                $email= $result->email;
                $emailValidateQuery = 'SELECT * FROM utilisateur WHERE courriel = :courriel';
                $emailValidateStmt = $db->prepare($emailValidateQuery);
                $emailValidateResult = $emailValidateStmt->execute(array(':courriel'=>$email));
            }
        } else {
            $errors = array('Erreur lors de la connection! Veuillez réessayer.');
        }
    } else { //Regular user/pass login
        //User trying to log in, we need to validate the form then try logging him in
        $required = array('email', 'password');
        $errors = validatePost($required);
        //No errors, check if the user/pass exists
        if (empty($errors)) {
            $validUserQuery = 'SELECT * FROM utilisateur WHERE courriel = :email AND mot_de_passe = :password';
            $validUserStmt = $db->prepare($validUserQuery);
            if ($validUserStmt->execute(array(
                ':email' => $_POST['email'],
                ':password' => $_POST['password'], ))
            ) {
                if ($validUserStmt->rowCount() === 1) {
                    //Valid email/password!
                    $user = $validUserStmt->fetch();
                    $_SESSION['email'] = $user['courriel'];
                    $_SESSION['role'] = intval($user['administrateur']) === 1 ? 'admin' : 'user';
                    $_SESSION['isLoggedIn'] = true;
                    $_SESSION['userId'] = $user['pk_utilisateur'];
                    header('Location: index.php');
                    die();
                } else {
                    $errors[] = 'Le courriel ou mot de passe est invalide!';
                }
            } else {
                $errors[] = 'Un problème technique est survenu!<br/>Veuillez réessayer plus tard.';
            }
        } else {
            //Check if the email/pass is missing and show errors accordingly in the form
            $errors[] = 'Il faut entrer un courriel et un mot de passe!';
        }
    }
}
require_once 'template/navbar.inc.php';
?>
<!-- /**************************************************************************************************/
/* Fichier ...................... : login.php */
/* Titre ........................ : Lab Web */
/* Auteur ....................... : Amélie Frappier et Antony Garand */
/* Date de création ............. : 2016-08-22 */
/* Date de mise en ligne ........ : Jamais */
/* Date de mise à jour .......... : 2016-08-22 */
/*******************************************************************************************************/
-->

<div class="loginWrapper">
        <h2>Veuillez vous identifier pour avoir la possibilité d'acheter des formations</h2>
        <?php if (!empty($errors)) { ?>
            <p class="error"><?=implode('<br/>', $errors)?></p>
        <?php } ?>
        <form id="loginForm" action="login.php" method="post">
            <div>
            <input type="email" name="email" placeholder="Courriel" pattern=".{5,100}" title="Veuillez entre 5 et 100 caractères" required <?php
if (isset($_POST['email']) && is_string($_POST['email'])) {
    echo 'value="'.htmlspecialchars($_POST['email']).'" ';
}?>/> <br/>
            <input type="password" name="password" placeholder="Mot de passe" pattern=".{5,100}" title="Veuillez entre 5 et 100 caractères" required /> <br/>
            <a href="resetpassword.php">Mot de passe oublié</a>
        </div>
        <div>
            <input class="button" name="login" type="submit" value="Connexion"/>
            <a href="profil.php"><input class="button" type="button" value="S'inscrire'"></a><br/>
            <img src="img/graphiques/facebook.png" alt="facebook login" onclick="fbLogin()"/>
        </div>
    </form>
    <script>
    function fbLogin(){
        FB.login(function(response){
            if(response.status === 'connected'){
                validateLogin(response.authResponse.accessToken);
            } else {
                console.log('Login failed! Please try again.');
            }
        }, {scope: 'email'});
    }

function validateLogin(token){
    FB.api('/me', { fields: 'email'}, function(response) {
        let email = response.email;
        //Creating form
        let f = document.createElement('form');
        f.setAttribute('method','post');
        f.setAttribute('action','login.php');
        var emailInput = document.createElement('input');
        emailInput.setAttribute('type','hidden');
        emailInput.setAttribute('name','fbEmail');
        emailInput.setAttribute('value',email);
        f.appendChild(emailInput);

        var tokenInput = document.createElement('input');
        tokenInput.setAttribute('type','hidden');
        tokenInput.setAttribute('name','fbToken');
        tokenInput.setAttribute('value',token);
        f.appendChild(tokenInput);
        var loginInput = document.createElement('input');
        loginInput.setAttribute('type','hidden');
        loginInput.setAttribute('name','login');
        loginInput.setAttribute('value','true');
        f.appendChild(loginInput);

        document.body.appendChild(f);
        f.submit();
    });
}
</script>
</div><!-- end .loginWrapper-->
<?php include 'template/footer.inc.php'; ?>
