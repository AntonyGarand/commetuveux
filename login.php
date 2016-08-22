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
if(isset($_POST['isLoggedIn'])){
?>
    <script>window.location.href("/index.php");</script>
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
        if($this->validUserStmt->execute(array(
            ':email'=>$_POST['email'],
            ':password'=>$_POST['password']))
        ){
            if($validUserStmt->rowCount === 1){
                //Valid email/password!
                $user = $validUserStmt->fetch();
                $_SESSION['email'] = $user['courriel'];
                $_SESSION['role'] = $user['administrateur']===1 ? 'admin' : 'user';
            }
        }
    } else {
        //Check if the email/pass is missing and show errors accordingly in the form

    }

}
?>
