<?php 
require_once 'template/header.inc.php';
if ($_SESSION['role'] !== 'admin') {
    die(header('Location: index.php'));
}

function getErrorMessageFromField($field){
    $errors = array(
        'titre' => 'Le titre est manquant ou invalide',
        'description' => 'La description est manquante ou invalide',
        'duree' => 'La durée est manquante ou invalide',
        'tarif' => 'Le tarif est manquant ou invalide',
        'image' => 'L\'image est manquante ou invalide',
        'actif' => 'Il faut décider d\'afficher ou non le service',
    );
    return isset($errors[$field]) ? $errors[$field] : 'Une erreur est survenue! Veuillez réessayer.';
}

function createService(){
    global $db; 
    $requiredFields = array(
        'titre','description','duree','tarif'
    );
    $missingFields = validatePost($requiredFields);
    if(!empty($missingFields)){
        return $missingFields;
    }
    $customErrors = array();

    $imageName = validateAndUploadImage();
    if($imageName === false){
        $customErrors[] = 'image';
    }

    $_POST['titre'] = urldecode($_POST['titre']);
    $_POST['description'] = urldecode($_POST['description']);
    if(strlen($_POST['titre']) > 75){
        $customErrors[] = 'titre';
    }
    if(strlen($_POST['tarif']) > 10 || !is_numeric($_POST['tarif'])){
        $customErrors[] = 'tarif';
    }
    if(strlen($_POST['duree']) > 10 || !is_numeric($_POST['duree'])){
        $customErrors[] = 'duree';
    }
    if(!empty($customErrors)){
        return $customErrors;
    }

    $variables = array(':titre',':description', ':duree', ':tarif',':actif', ':image');
    $params = array(
        ':titre'=>htmlspecialchars($_POST['titre']),
        ':description' => htmlspecialchars($_POST['description']),
        ':duree' => $_POST['duree'],
        ':tarif' => $_POST['tarif'],
        ':actif' => isset($_POST['actif']) ? '1' : '0',
        ':image' => 'img/uploads/' . $imageName
    );
    $updateServiceQuery = 'INSERT INTO `service` (`service_titre`,`service_description`,`duree`,`tarif`,`actif`, `image`) values (' . implode(',', $variables) . ')';

    $updateServiceStmt = $db->prepare($updateServiceQuery);
    $updateServiceResult = $updateServiceStmt->execute($params);
    return $updateServiceResult;
}

function validateAndUploadImage(){
    if(!isset($_FILES['image'])){
        return false;
    }
    $uploadDir = './img/uploads/';
    $validFormats = array('png','bmp','jpg','jpeg','gif');
    $image = $_FILES['image'];
    //Checking if it is an image
    $size = getimagesize($image['tmp_name']);
    if($size === false){
        return false; 
    }
    //Checking if it is a valid extension
    $ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
    if(!in_array($ext, $validFormats)){
        return false;
    }
    //Everything looks good, upload it
    $newName = randomString(10) .'.'. $ext;
    move_uploaded_file($image['tmp_name'], "$uploadDir$newName");
    return $newName;
}

if(isset($_POST['serviceCreate'])){
    if(!empty($errors = createService()) && $errors !== true){
        $errorMessages = array_map("getErrorMessageFromField",$errors);
    } else {
        header('Location: service.php');
    }
}

if(isset($_POST['serviceCreate'])){
    $titre = isset($_POST['titre']) ? htmlspecialchars($_POST['titre']) : '';
    $description = isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '';
    $image = isset($imageName) ? $imageName : '';
    $duree = isset($_POST['duree']) ? intval($_POST['duree']) : '';
    $tarif = isset($_POST['tarif']) ? intval($_POST['tarif']) : '';
    $actif = isset($_POST['actif']) ? $_POST['actif'] == 'on' ? true : false : false;
} else {
    $id = '';
    $titre = '';
    $description = '';
    $image = 'img/services/cours.gif';
    $duree = '';
    $tarif = '';
    $actif = true;
}

require_once('template/navbar.inc.php');
?>
<div class="service">
    <h1 id="serviceHeader">Compléter le formulaire pour ajouter un nouveau service</h1>
    <h2 id="serviceAllRequiredHeader">Tous les champs sont obligatoires</h2>
    <?php if(!empty($successMessage)){ ?>
        <div class="success"><?=$successMessage?></div>
    <?php } elseif(!empty($errorMessages)){ ?>
        <div class="error"><?=implode("<br/>",$errorMessages)?></div>
    <?php } ?>
    <form id="serviceCreateForm" method="post" enctype="multipart/form-data">
        <div class="serviceImageSection">
            <div class="serviceImageWrapper">
                <img src="<?=$image?>" class="serviceImage" alt="Service image"/>
            </div>
            <div class="serviceUploadWrapper">
                <div class="serviceUploadImage">Mettre à jour la photo</div>
                <input type="hidden" name="imageChanged" value="false" id="imageChanged"/>
                <label for="imageUpload"><img src="img/icones/camera.png" alt="camera icon"/></label>
                <input type="file" name="image" onchange="document.getElementById('imageChanged').value = 'true';" id="imageUpload"/>
            </div>
        </div>
        <div class="serviceInformationSection">
            <input name="titre" type="text" placeholder="Titre" value="<?=htmlspecialchars($titre)?>" id="titre"/>
            <textarea name="description" placeholder="Description" id="description"><?=htmlspecialchars($description)?></textarea>
            <input name="duree" type="number" placeholder="Durée" value="<?=$duree?>" id="duree"/>
            <input name="tarif" type="number" placeholder="Tarif" value="<?=$tarif?>" id="tarif"/>
            <div id="showService"><input name="actif" id="actif" type="checkbox" <?php if($actif) { echo "Checked"; }?>/><label for="actif"><span></span>Ce service sera affiché dans le catalogue</label></div>
            <div class="serviceSubmit"><input type="submit" name="serviceCreate" value="Confirmer"/></div>
        </div>
    </form>
</div>
