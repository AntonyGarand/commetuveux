<!-- /**************************************************************************************************/
/* Fichier ...................... : profil.php */
/* Titre ........................ : Lab Web */
/* Auteur ....................... : Amélie Frappier et Antony Garand */
/* Date de création ............. : 2016-08-22 */
/* Date de mise en ligne ........ : Jamais */
/* Date de mise à jour .......... : 2016-08-22 */
/*******************************************************************************************************/
-->
<?php require_once('template/header.inc.php'); 
function generateErrorMessage($suffix){
    return $errorMessage = array(
        'firstName'=>"Le prénom $suffix",
        'lastName'=>"le nom $suffix",
        'civicNo'=>"Le numéro civique $suffix",
        'street'=>"Le nom de la rue $suffix",
        'city'=>"La ville choisie est manquante ou invalide",
        'zipCode'=>"Le code postal $suffix",
        'phone'=>"Le numéro de telephone $suffix",
        'email'=>"Le courriel $suffix",
        'confirmEmail'=>"Le courriel de confirmation $suffix",
        'password'=>"Le mot de passe $suffix",
        'confirmPassword'=>"Le mot de passe de confirmation $suffix"
    );
}
//Loading the cities first as we need them in the profile validation (Check if id exists)
$citiesQuery = "SELECT * FROM ville ORDER BY pk_ville";
$cities = $db->query($citiesQuery)->fetchAll();

if(isset($_POST['profil'])){
    //Form is being sent, validate the content and try to create a user
    $required = array('firstName','lastName','civicNo','street','city','zipCode','phone','email','confirmEmail', 'password','confirmPassword');
    $errors = validatePost($required);
    if(empty($errors)){
        //All values are present and strings, let's keep checking them
        $emailCheckQuery = "SELECT * FROM utilisateur WHERE courriel = :courriel";
        $emailCheckStmt = $db->prepare($emailCheckQuery);
        //Server error!
        if(!$emailCheckStmt->execute(array(':courriel'=>$_POST['email']))){
            $errorsClean = array("Erreur du serveur! Veuillez réessayer.");
        } else {
            //The email already exists in the database
            if($emailCheckStmt->rowCount() > 0){
                $errorsClean = array("Le courriel est déjà pris! Veuillez réessayer.");
            } else{
                //Validating the info sent by the client
                $cityExists = false;
                $_POST['city'] = intval($_POST['city']);
                //Looping through the cities. If one has pk_ville = city sent, it's valid!
                foreach($cities as$city){if($city['pk_ville']==$_POST['city']){$cityExists=true;break;}}
                if(!$cityExists){
                    $errorsClean = array("La cité n'existe pas! Veuillez réessayer");
                } else {
                    //City exists, now check the lengths and values sent
                    $lengthAndTypeValidator = array(
                        'firstName'=>array('minLength'=>2,'maxLength'=>75,'type'=>'string'),
                        'lastName'=>array('minLength'=>2,'maxLength'=>75,'type'=>'string'),
                        'civicNo'=>array('minLength'=>1,'maxLength'=>10,'type'=>'string'),
                        'zipCode'=>array('minLength'=>6,'maxLength'=>6,'type'=>'string'),
                        'street'=>array('minLength'=>2,'maxLength'=>75,'type'=>'string'),
                        'phone'=>array('minLength'=>10,'maxLength'=>20,'type'=>'string'),
                        'email'=>array('minLength'=>5,'maxLength'=>100,'type'=>'string'),
                        'password'=>array('minLength'=>8,'maxLength'=>100,'type'=>'string')
                    );
                    $errorsClean = array();
                    $errorMessage = generateErrorMessage("n'a pas une longueur valide!");
                    if($_POST['password'] !== $_POST['confirmPassword']){
                        $errorsClean[] = "Les deux mots de passe ne correspondent pas!";
                    }
                    if($_POST['email'] !== $_POST['confirmEmail']){
                        $errorsClean[] = "Les deux courriels ne correspondent pas!";
                    }
                    foreach($lengthAndTypeValidator as $key => $validation){
                        $length = strlen($_POST[$key]);
                        if($length < $validation['minLength'] || $length > $validation['maxLength']){
                            $errorsClean[] = $errorMessage[$key];
                            echo $key . "<br/>";
                        }
                    } //End of foreach key validation
                    //All values are good, create the database entries
                    if(empty($errorsClean)){
                        //TODO: Salt+Hash the password! Plaintext is bad
                        $userCreateQuery = "INSERT INTO `utilisateur` (`pk_utilisateur`, `courriel`, `mot_de_passe`, `administrateur`) VALUES (NULL, :email, :password, '0')";
                        $adressCreateQuery = "INSERT INTO `adresse` (`pk_adresse`, `no_civique`, `rue`, `fk_ville`, `code_postal`) VALUES (NULL, :civicNo, :street, :city, :zipCode)";
                        $clientCreateQuery = "INSERT INTO `client` (`pk_client`, `fk_utilisateur`, `prenom`, `nom`, `fk_adresse`, `telephone`, `infolettre`) VALUES (NULL, :userId, :prenom, :nom, :adressId, :phone, :wantSpam)";
                        $adressCreateStmt = $db->prepare($adressCreateQuery);
                        $adressCreateStmt->execute(array(
                            ':civicNo' => $_POST['civicNo'],
                            ':street' => $_POST['street'],
                            ':city' => $_POST['city'],
                            ':zipCode' => $_POST['zipCode']
                        ));
                        $adressId = $db->lastInsertId();
                        $userCreateStmt = $db->prepare($userCreateQuery);
                        $userCreateStmt->execute(array(
                            ':email'=>$_POST['email'],
                            ':password'=>$_POST['password']
                        ));
                        $userId = $db->lastInsertId();

                        $clientCreateStmt = $db->prepare($clientCreateQuery);
                        $clientCreateStmt->execute(array(
                            ':userId'=>$userId,
                            ':prenom'=>$_POST['firstName'],
                            ':nom'=>$_POST['lastName'],
                            ':adressId'=>$adressId,
                            ':phone'=>$_POST['phone'],
                            ':wantSpam'=>empty($_POST['sendPromo']) ? 0 : 1
                        ));
                        die("Le compte a été créer!");
                        //TODO: Header index.php?
                    }
                } //End of city does exist condition
            }
        }

    } else {
        $suffix = "est manquant ou invalide";
        $errorMessage = generateErrorMessage($suffix);
        $errorsClean = array();
        foreach($errors as $error){
            $errorsClean[] = $errorMessage[$error];
        }
    } //End of profile validation
} //End of profile confirmation


/*
 * For the following form, all fields will be saved if an error occured (Format not respected, didn't enter value, ...)
 * Excluding the password fields
 * To optimize the following, we could create a function createInput($name, $type, $placeholder, $autoFill = true, $otherAttributes) to automatically manage these.
 */
?>

<div class="profile-form">
	<form method="post" action="profil.php">
		<fieldset>
                        <?php if(!empty($errorsClean)){ echo '<p class="warning">' . implode("<br/>",$errorsClean) . '</p>';} ?>
			<h2>Remplissez ce formulaire pour créer votre profil</h2> <br/>
			<h3>Tous les champs sont obligatoires</h3>
                        <input type="text" name="lastName" placeholder="Nom"<?php 
                            if(isset($_POST['lastName']) && is_string($_POST['lastName'])){echo' value="'.htmlspecialchars($_POST['lastName']) . '"';}?>/>
			<input type="text" name="firstName" placeholder="Prénom"<?php 
                            if(isset($_POST['firstName']) && is_string($_POST['firstName'])){echo' value="'.htmlspecialchars($_POST['firstName']) . '"';}?>/>
			<input type="text" name="civicNo" placeholder="No. civique"<?php 
                            if(isset($_POST['civicNo']) && is_string($_POST['civicNo'])){echo' value="'.htmlspecialchars($_POST['civicNo']) . '"';}?>/>
			<input type="text" name="street" placeholder="Rue"<?php 
                            if(isset($_POST['street']) && is_string($_POST['street'])){echo' value="'.htmlspecialchars($_POST['street']) . '"';}?>/>
                        <select name="city"><?php foreach($cities as $city){
                                //Populating the cities with the database-fetched values
                                echo '<option value="' . $city['pk_ville'] . '"';
                                //If the current city is the selected one, keep its value
                                if(isset($_POST['city']) && is_string($_POST['city']) && $_POST['city'] === $city['pk_ville']){
                                    echo ' selected';
                                }
                                echo '>' . $city['ville'] . '</option>';
                            }?>
			</select>
			<input type="text" name="zipCode" placeholder="Code postal"<?php 
                            if(isset($_POST['zipCode']) && is_string($_POST['zipCode'])){echo' value="'.htmlspecialchars($_POST['zipCode']) . '"';}?>/>
			<input type="text" name="phone" placeholder="Numéro de téléphone"<?php 
                            if(isset($_POST['phone']) && is_string($_POST['phone'])){echo' value="'.htmlspecialchars($_POST['phone']) . '"';}?>/>
		</fieldset>
		
		<fieldset>
			<h2>Votre courriel servira à vous identifier lors de votre prochaine visite</h2> <br/>
			<h3>Votre mot de passe doit contenir un minimum de 8 caractères.</h3>
			<input type="text" name="email" placeholder="Courriel"<?php 
                            if(isset($_POST['email']) && is_string($_POST['email'])){echo' value="'.htmlspecialchars($_POST['email']) . '"';}?>/>
			<input type="text" name="confirmEmail" placeholder="Confirmation du email"<?php 
                            if(isset($_POST['confirmEmail']) && is_string($_POST['confirmEmail'])){echo' value="'.htmlspecialchars($_POST['confirmEmail']) . '"';}?>/>
			<input type="password" name="password" placeholder="Mot de passe"/>
			<input type="password" name="confirmPassword" placeholder="Confirmation du mot de passe"/>
			<input type="checkbox" name="sendPromo" value="send" checked="checked"> Souhaitez-vous recevoir les promotions et les nouveautés?
		</fieldset>
		
		<input name="profil" type="submit" value="Confirmer"/>
		
	</form>
	
</div>

<?php include('template/footer.inc.php'); ?>
