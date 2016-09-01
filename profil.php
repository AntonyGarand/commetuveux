<?php require_once 'template/header.inc.php';
function generateErrorMessage($suffix)
{
    return $errorMessage = array(
        'firstName' => "Le prénom $suffix",
        'lastName' => "le nom $suffix",
        'civicNo' => "Le numéro civique $suffix",
        'street' => "Le nom de la rue $suffix",
        'city' => 'La ville choisie est manquante ou invalide',
        'zipCode' => "Le code postal $suffix",
        'phone' => "Le numéro de telephone $suffix",
        'email' => "Le courriel $suffix",
        'confirmEmail' => "Le courriel de confirmation $suffix",
        'password' => "Le mot de passe $suffix",
        'confirmPassword' => "Le mot de passe de confirmation $suffix",
    );
}
//Admins don't have a profile! redirect them to prevent bugs and errors
if ($_SESSION['role'] == 'admin') {
    die(header('Location: index.php'));
}
$cleanNames = array(
    'firstName' => 'Le prénom',
    'lastName' => 'le nom',
    'civicNo' => 'Le numéro civique',
    'street' => 'Le nom de la rue',
    'city' => 'La ville choisie est manquante ou invalide',
    'zipCode' => 'Le code postal',
    'phone' => 'Le numéro de telephone',
    'email' => 'Le courriel',
    'confirmEmail' => 'Le courriel de confirmation',
    'password' => 'Le mot de passe',
    'confirmPassword' => 'Le mot de passe de confirmation',
);

//Loading the cities first as we need them in the profile validation (Check if id exists)
$citiesQuery = 'SELECT * FROM ville ORDER BY pk_ville';
$cities = $db->query($citiesQuery)->fetchAll();

//Setting the user variable if we're logged in
$user = false;
if ($_SESSION['userId'] !== 0) {
    $userSelectQuery = "SELECT * FROM utilisateur JOIN client ON client.fk_utilisateur = pk_utilisateur JOIN adresse ON adresse.pk_adresse = client.fk_adresse WHERE pk_utilisateur = {$_SESSION['userId']}";
    $userSelectStmt = $db->query($userSelectQuery);
    if ($userSelectStmt->rowCount() === 1) {
        $user = $userSelectStmt->fetch();
    }
}

if (isset($_POST['profil'])) {
    function validateInformation()
    {
        global $db, $cities, $cleanNames;
        //Form is being sent, validate the content and try to create a user
        $required = array('firstName', 'lastName', 'civicNo', 'street', 'city', 'zipCode', 'phone', 'email', 'confirmEmail', 'password', 'confirmPassword');
        $errors = validatePost($required);
        if (!empty($errors)) {
            $suffix = 'est manquant ou invalide';
            $errorMessage = generateErrorMessage($suffix);
            $errorsClean = array();
            foreach ($errors as $error) {
                $errorsClean[] = $errorMessage[$error];
            }

            return $errorsClean;
        }
        //All values are present and strings, let's keep checking them
        $emailCheckQuery = "SELECT * FROM utilisateur WHERE courriel = :courriel AND pk_utilisateur != {$_SESSION['userId']}";
        $emailCheckStmt = $db->prepare($emailCheckQuery);
        //Server error!
        if (!$emailCheckStmt->execute(array(':courriel' => $_POST['email']))) {
            $errorsClean = array('Erreur du serveur! Veuillez réessayer.');
            die(var_dump($emailCheckStmt));

            return $errorsClean;
        }
        //The email already exists in the database
        if ($emailCheckStmt->rowCount() > 0) {
            $errorsClean = array('Le courriel est déjà pris! Veuillez réessayer.');

            return $errorsClean;
        }
        //Validating the info sent by the client
        $cityExists = false;
        $_POST['city'] = intval($_POST['city']);
        //Looping through the cities. If one has pk_ville = city sent, it's valid!
        foreach ($cities as$city) {
            if ($city['pk_ville'] == $_POST['city']) {
                $cityExists = true;
                break;
            }
        }
        if (!$cityExists) {
            $errorsClean = array("La cité n'existe pas! Veuillez réessayer");

            return $errorsClean;
        }
        //City exists, now check the lengths and values sent
        $lengthAndTypeValidator = array(
            'firstName' => array('minLength' => 2, 'maxLength' => 75, 'type' => 'string'),
            'lastName' => array('minLength' => 2, 'maxLength' => 75, 'type' => 'string'),
            'civicNo' => array('minLength' => 1, 'maxLength' => 10, 'type' => 'string'),
            //Regex: As postal code can't contain  D, F, I, O, Q, or U, and cannot start with W or Z, make a manual regex with the charcters
            'zipCode' => array('minLength' => 6, 'maxLength' => 6, 'type' => 'string', 'regex' => '/^[ABCEGHJKLMNPRSTVXY][0-9][ABCEGHJKLMNPRSTVWXYZ][0-9][ABCEGHJKLMNPRSTVWXYZ][0-9]/'),
            'street' => array('minLength' => 2, 'maxLength' => 75, 'type' => 'string'),
            'phone' => array('minLength' => 10, 'maxLength' => 20, 'type' => 'string', 'regex' => '/^(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?$/'),
            'email' => array('minLength' => 5, 'maxLength' => 100, 'type' => 'string'),
            'password' => array('minLength' => 8, 'maxLength' => 100, 'type' => 'string'),
        );
        $errorsClean = array();
        $errorMessage = generateErrorMessage("n'a pas une longueur valide!");
        if ($_POST['password'] !== $_POST['confirmPassword']) {
            $errorsClean[] = 'Les deux mots de passe ne correspondent pas!';
        }
        if ($_POST['email'] !== $_POST['confirmEmail']) {
            $errorsClean[] = 'Les deux courriels ne correspondent pas!';
        }
        //Email format validation
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errorsClean[] = 'Le courriel ne respecte pas le format demandé!';
        }
        foreach ($lengthAndTypeValidator as $key => $validation) {
            $length = strlen($_POST[$key]);
            if ($length < $validation['minLength'] || $length > $validation['maxLength']) {
                $errorsClean[] = $errorMessage[$key];
            }
            if (isset($validation['regex'])) {
                if (preg_match($validation['regex'], $_POST[$key]) !== 1) {
                    $errorsClean[] = $cleanNames[$key].' ne respecte pas le format demandé!';
                }
            }
        } //End of foreach key validation
        if (!empty($errorsClean)) {
            return $errorsClean;
        }

        return array();
    }
    function createNewUser()
    {
        global $db;
        //User creation!
        //TODO: Salt+Hash the password! Plaintext is bad
        $userCreateQuery = "INSERT INTO `utilisateur` (`pk_utilisateur`, `courriel`, `mot_de_passe`, `administrateur`) VALUES (NULL, :email, :password, '0')";
        $adressCreateQuery = 'INSERT INTO `adresse` (`pk_adresse`, `no_civique`, `rue`, `fk_ville`, `code_postal`) VALUES (NULL, :civicNo, :street, :city, :zipCode)';
        $clientCreateQuery = 'INSERT INTO `client` (`pk_client`, `fk_utilisateur`, `prenom`, `nom`, `fk_adresse`, `telephone`, `infolettre`) VALUES (NULL, :userId, :prenom, :nom, :adressId, :phone, :wantSpam)';
        $adressCreateStmt = $db->prepare($adressCreateQuery);
        $adressCreateStmt->execute(array(
            ':civicNo' => $_POST['civicNo'],
            ':street' => $_POST['street'],
            ':city' => $_POST['city'],
            ':zipCode' => $_POST['zipCode'],
        ));
        $adressId = $db->lastInsertId();
        $userCreateStmt = $db->prepare($userCreateQuery);
        //TODO: Sanitize email when echoing it out, as "><script>alerT(1);</script>"@test.ca is a valid email!
        //Anything in the form "(.*)"@domain.com is valid, which could lead to XSS if not sanitized properly.
        $userCreateStmt->execute(array(
            ':email' => $_POST['email'],
            ':password' => $_POST['password'],
        ));
        $userId = $db->lastInsertId();

        $clientCreateStmt = $db->prepare($clientCreateQuery);
        $clientCreateStmt->execute(array(
            ':userId' => $userId,
            ':prenom' => $_POST['firstName'],
            ':nom' => $_POST['lastName'],
            ':adressId' => $adressId,
            ':phone' => $_POST['phone'],
            ':wantSpam' => empty($_POST['sendPromo']) ? 0 : 1,
        ));
        //New account created
        header('Location: index.php');
    }

    function updateUser()
    {
        global $db;
        $selectCompleteUserStmt = "SELECT * from client JOIN utilisateur on utilisateur.pk_utilisateur = client.fk_utilisateur JOIN adresse ON adresse.pk_adresse = client.fk_adresse WHERE utilisateur.pk_utilisateur = {$_SESSION['userId']}";
        $user = $db->query($selectCompleteUserStmt)->fetch();

        $fields = array(
            'firstName' => 'prenom',
            'lastName' => 'nom',
            'phone' => 'telephone',
            'email' => 'courriel',
            'sendPromo' => 'infolettre',
            'password' => 'mot_de_passe',
            'civicNo' => 'no_civique',
            'street' => 'rue',
            'zipCode' => 'code_postal',
        );

        $clientUpdateQuery = "UPDATE `client` SET `prenom` = :firstName, `nom` = :lastName, `telephone` = :phone WHERE `client`.`pk_client` = {$user['pk_client']}";
        $adressUpdateQuery = "UPDATE `adresse` SET `no_civique` = :civicNo, `rue` = :street, `code_postal` = :zipCode, `fk_ville` = :ville WHERE `adresse`.`pk_adresse` = {$user['pk_adresse']}";
        $userUpdateQuery = "UPDATE `utilisateur` SET `courriel` = :email, `mot_de_passe` = :password WHERE `utilisateur`.`pk_utilisateur` = {$user['pk_utilisateur']}";
        $clientUpdateStmt = $db->prepare($clientUpdateQuery);
        $clientUpdateStmt->execute(array(
            ':firstName' => $_POST['firstName'],
            ':lastName' => $_POST['lastName'],
            ':phone' => $_POST['phone'],
        ));
        $adressUpdateStmt = $db->prepare($adressUpdateQuery);
        $adressUpdateStmt->execute(array(
            ':civicNo' => $_POST['civicNo'],
            ':street' => $_POST['street'],
            ':ville' => $_POST['ville'],
            ':zipCode' => $_POST['zipCode'],
        ));
        $userUpdateStmt = $db->prepare($userUpdateQuery);
        $userUpdateStmt->execute(array(
            ':email' => $_POST['email'],
            ':password' => $_POST['password'],
        ));
        header('Location: index.php');
        die();
    }
    $errorsClean = validateInformation();

    if (empty($errorsClean)) {
        if (isset($_POST['userId']) && is_numeric($_POST['userId'])) {
            if (intval($_POST['userId']) !== intval($_SESSION['userId'])) {
                require_once 'template/navbar.inc.php';
                echo'<h1>Erreurs lors de la mise à jour!</h1><h3>Veuillez vous reconnecter.</h3>';
                require_once 'template/footer.inc.php';
                die();
            } else {
                updateUser();
            }
        } else {
            createNewUser();
        }
    }
} //End of profile confirmation

if (empty($_POST) && $user !== false) {
    $firstName = htmlspecialchars(htmlspecialchars_decode($user['prenom']));
    $lastName = htmlspecialchars(htmlspecialchars_decode($user['nom']));
    $civicNo = htmlspecialchars(htmlspecialchars_decode($user['no_civique']));
    $street = htmlspecialchars(htmlspecialchars_decode($user['rue']));
    $cityId = htmlspecialchars(htmlspecialchars_decode($user['fk_ville']));
    $zipCode = htmlspecialchars(htmlspecialchars_decode($user['code_postal']));
    $phone = htmlspecialchars(htmlspecialchars_decode($user['telephone']));
    $email = htmlspecialchars(htmlspecialchars_decode($user['courriel']));
    $emailConfirm = htmlspecialchars(htmlspecialchars_decode($user['courriel']));
} else {
    //If data has been sent, save it to repopulate the form
    if (isset($_POST['lastName']) && is_string($_POST['lastName'])) {
        $lastName = htmlspecialchars($_POST['lastName']);
    } else {
        $lastName = '';
    }
    if (isset($_POST['firstName']) && is_string($_POST['firstName'])) {
        $firstName = htmlspecialchars($_POST['firstName']);
    } else {
        $firstName = '';
    }
    if (isset($_POST['civicNo']) && is_string($_POST['civicNo'])) {
        $civicNo = htmlspecialchars($_POST['civicNo']);
    } else {
        $civicNo = '';
    }
    if (isset($_POST['street']) && is_string($_POST['street'])) {
        $street = htmlspecialchars($_POST['street']);
    } else {
        $street = '';
    }
    if (isset($_POST['city']) && is_numeric($_POST['city'])) {
        $cityId = intval($_POST['city']);
    } else {
        $cityId = '';
    }
    if (isset($_POST['zipCode']) && is_string($_POST['zipCode'])) {
        $zipCode = htmlspecialchars($_POST['zipCode']);
    } else {
        $zipCode = '';
    }
    if (isset($_POST['phone']) && is_string($_POST['phone'])) {
        $phone = htmlspecialchars($_POST['phone']);
    } else {
        $phone = '';
    }
    if (isset($_POST['email']) && is_string($_POST['email'])) {
        $email = htmlspecialchars($_POST['email']);
    } else {
        $email = '';
    }
    if (isset($_POST['emailConfirm']) && is_string($_POST['emailConfirm'])) {
        $emailConfirm = htmlspecialchars($_POST['emailConfirm']);
    } else {
        $emailConfirm = '';
    }
}
require_once 'template/navbar.inc.php';
/*
 * For the following form, all fields will be saved if an error occured (Format not respected, didn't enter value, ...)
 * Excluding the password fields
 * To optimize the following, we could create a function createInput($name, $type, $placeholder, $autoFill = true, $otherAttributes) to automatically manage these.
 */
?>
<!-- /**************************************************************************************************/
/* Fichier ...................... : profil.php */
/* Titre ........................ : Lab Web */
/* Auteur ....................... : Amélie Frappier et Antony Garand */
/* Date de création ............. : 2016-08-22 */
/* Date de mise en ligne ........ : Jamais */
/* Date de mise à jour .......... : 2016-08-22 */
/*******************************************************************************************************/
-->
<div class="profile-form">
    <form method="post" action="profil.php">
        <fieldset>
            <?php if (!empty($errorsClean)) {
                echo '<p class="warning">'.implode('<br/>', $errorsClean).'</p>';
            } 
            if (is_numeric($_SESSION['userId']) && $_SESSION['userId'] !== 0) {
            ?>
                <input type="hidden" name="userId" value="<?=$_SESSION['userId']?>"/><?php 
            } ?>
            <h2>Remplissez ce formulaire pour créer votre profil</h2> <br/>
            <h3>Tous les champs sont obligatoires</h3> <br/>
            <!--
            'firstName' => array('minLength' => 2, 'maxLength' => 75, 'type' => 'string'),
            'lastName' => array('minLength' => 2, 'maxLength' => 75, 'type' => 'string'),
            'civicNo' => array('minLength' => 1, 'maxLength' => 10, 'type' => 'string'),
            //Regex: As postal code can't contain  D, F, I, O, Q, or U, and cannot start with W or Z, make a manual regex with the charcters
            'zipCode' => array('minLength' => 6, 'maxLength' => 6, 'type' => 'string', 'regex' => '/^[ABCEGHJKLMNPRSTVXY][0-9][ABCEGHJKLMNPRSTVWXYZ][0-9][ABCEGHJKLMNPRSTVWXYZ][0-9]/'),
            'street' => array('minLength' => 2, 'maxLength' => 75, 'type' => 'string'),
            'phone' => array('minLength' => 10, 'maxLength' => 20, 'type' => 'string', 'regex' => '/^(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?$/'),
            'email' => array('minLength' => 5, 'maxLength' => 100, 'type' => 'string'),
            'password' => array('minLength' => 8, 'maxLength' => 100, 'type' => 'string'),
            -->
            <div class="left-column">
                <input tabIndex="1" type="text" name="lastName" placeholder="Nom" value="<?=$lastName?>" pattern=".{2,75}" title="Le nom doit contenir entre 2 et 75 caractères inclusivement!" required/>
                <div class="profileAddress">
                    <input tabIndex="3" id="civicNo" type="text" name="civicNo" placeholder="No. civique" value="<?=$civicNo?>" pattern="[0-9]{1,10}" title="Le numéro civique doit être composé de 1 à 10 chiffres!" required/>
                    <input tabIndex="4" id="street" type="text" name="street" placeholder="Rue" value="<?=$street?>" pattern=".{2,27}" title="Le nom de la rue doit contenir entre 2 et 75 caractères inclusivement!"/>  
                </div>
                <input tabIndex="6" type="text" name="zipCode" placeholder="Code postal" value="<?=$zipCode?>" pattern="[ABCEGHJKLMNPRSTVXY][0-9][ABCEGHJKLMNPRSTVWXYZ][0-9][ABCEGHJKLMNPRSTVWXYZ][0-9]" title="Le code postal doit être sous le format A1B2C3! Certains caractères sont restreints afin de respecter le format des codes postaux." required/>
            </div>
            <div class="right-column">
                <input tabIndex="2" type="text" name="firstName" placeholder="Prénom" value="<?=$firstName?>" pattern=".{2,75}" title="Le prénom doit contenir entre 2 et 75 caractères inclusivement!" required/>
                <select tabIndex="5" name="city" required><?php foreach ($cities as $city) {
                    //Populating the cities with the database-fetched values
                    echo '<option value="'.$city['pk_ville'].'"';
                    //If the current city is the selected one, keep its value
                    if ($cityId == $city['pk_ville']) {
                        echo ' selected';
                    }
                    echo '>'.$city['ville'].'</option>';
                }?></select>
                <input tabIndex="7" type="text" name="phone" placeholder="Numéro de téléphone" value="<?=$phone?>" pattern="^(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?$" title="Le numéro de telephone doit être composé de trois suites de trois, trois et quatre chiffres respectivement séparés par un espace, un point, un trait-d'union ou rien. Il peut être précédé d'un indicatif et avoir un extension ayant le mot-clé 'ext.' ou 'extension' suivi du numéro d'extension." required/>
            </div>
        </fieldset>
        <fieldset>
            <h2>Votre courriel servira à vous identifier lors de votre prochaine visite</h2> <br/>
            <h3>Votre mot de passe doit contenir un minimum de 8 caractères.</h3> <br/>
            <div class="left-column">
                <input tabIndex="8" type="email" name="email" id="email" placeholder="Courriel" value="<?=$email?>" required/>
                <input tabIndex="10" type="password" name="password"  id="password" placeholder="Mot de passe" pattern=".{8,100}" title="Le mot de passe doit contenir entre 8 et 100 caractères!" required />
            </div>
            <div class="right-column">
                <input tabIndex="9" type="text" name="confirmEmail" id="confirmEmail" placeholder="Confirmation du email" value="<?=isset($emailConfirm) ? $emailConfirm : ''?>" oninput="validateSameInput(this, 'email', 'courriel')" required/>
                <input tabIndex="11" type="password" name="confirmPassword" id="confirmPassword" placeholder="Confirmation du mot de passe" oninput="validateSameInput(this,'password','mot de passe')" required/>
            </div>
            <input tabIndex="12" type="checkbox" name="sendPromo" value="send" checked="checked"> <span class="receivePromo">Souhaitez-vous recevoir les promotions et les nouveautés?</span>
        </fieldset>
        <input tabIndex="13" name="profil" class="profileSubmit" type="submit" value="Confirmer"/>
    </form>
</div>
<script>
    function validateSameInput(input, copyId, fieldName){
        if(input.value != document.getElementById(copyId).value){
            console.log(document.getElementById(copyId).value);
            console.log(input.value);
            input.setCustomValidity("Ce champ doit correspondre au champ " + fieldName + "!");
        } else {
            input.setCustomValidity('');
        }
    }
</script>
<?php include 'template/footer.inc.php';
