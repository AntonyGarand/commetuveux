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

function updateService(){
    global $db; 
    $requiredFields = array(
        'serviceId','titre','description','duree','tarif','actif','imageChanged'
    );
    $missingFields = validatePost($requiredFields);
    if(!empty($missingFields)){
        return $missingFields;
    }
    $customErrors = array();

    if($_POST['imageChanged'] == true){
        $imageName = validateAndUploadImage();
        if($imageName === false){
            $customErrors[] = 'image';
        }
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

    $variables = array('`service_titre` = :titre','`service_description` = :description', '`duree` = :duree', '`tarif` = :tarif','`actif`=:actif');
    $params = array(
        ':titre'=>htmlspecialchars($_POST['titre']),
        ':description' => htmlspecialchars($_POST['description']),
        ':duree' => $_POST['duree'],
        ':tarif' => $_POST['tarif'],
        ':actif' => $_POST['actif'] == 'on' ? '1' : '0',
        ':serviceId' => intval($_POST['serviceId'])
    );
    if($_POST['imageChanged'] == true){
        $params[':image'] = 'img/uploads/' . $imageName;
        $variables[] = '`image`=:image';
    }
    $updateServiceQuery = 'UPDATE `service` SET ' . implode(',', $variables) . ' WHERE `service`.`pk_service` = :serviceId';

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

function disableService(){
    global $db;
    $serviceId = intval($_GET['serviceId']);
    $disableServiceQuery ="UPDATE `service` SET actif=0 WHERE pk_service = $serviceId";
    return $result= $db->exec($disableServiceQuery);
}

function getService(){
    global $db;

    if(!isset($_GET['serviceId']) ||!is_numeric($_GET['serviceId'])){
        return null;
    }

    $serviceId = intval($_GET['serviceId']);
    $selectServiceQuery = "SELECT * FROM SERVICE WHERE pk_service = $serviceId";
    $selectServiceStmt = $db->query($selectServiceQuery);
    if($selectServiceStmt->rowCount() !== 1){
        return null;
    }
    return $selectServiceStmt->fetch();
}

if(isset($_POST['serviceUpdate'])){
    if(!empty($errors = updateService()) && $errors !== true){
        $errorMessages = array_map("getErrorMessageFromField",$errors);
    } else {
        $successMessage = 'Les modifications ont été enregistrées avec succès!';
    }
}

if(isset($_GET['inactive'])){
    disableService();
    die(header('Location: /service.php'));
}

$service = getService();

if(isset($_POST['serviceUpdate'])){
    $id = isset($_POST['serviceId']) ? intval($_POST['serviceId']) : '';
    $titre = isset($_POST['titre']) ? htmlspecialchars($_POST['titre']) : '';
    $description = isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '';
    $image = isset($service['image']) ? $service['image'] : '';
    $duree = isset($_POST['duree']) ? intval($_POST['duree']) : '';
    $tarif = isset($_POST['tarif']) ? intval($_POST['tarif']) : '';
    $actif = isset($_POST['actif']) ? $_POST['actif'] == 'on' ? true : false : false;
} elseif($service !== null){ 
    $id = $service['pk_service'];
    $titre = $service['service_titre'];
    $description = $service['service_description'];
    $image = $service['image'];
    $duree = $service['duree'];
    $tarif = $service['tarif'];
    $actif = $service['actif'] == 1;
} else {
    $id = '';
    $titre = '';
    $description = '';
    $image = '';
    $duree = '';
    $tarif = '';
    $actif = true;
}

require_once('template/navbar.inc.php');
?>
<div class="service">
    <h1 id="serviceHeader">Vous pouvez modifier les informations du service</h1>
    <h2 id="serviceAllRequiredHeader">Tous les champs sont obligatoires</h2>
    <?php if(!empty($successMessage)){ ?>
        <div class="success"><?=$successMessage?></div>
    <?php } elseif(!empty($errorMessages)){ ?>
        <div class="error"><?=implode("<br/>",$errorMessages)?></div>
    <?php } ?>
    <form id="serviceUpdateForm" method="post" enctype="multipart/form-data">
        <input type="hidden" name="serviceId" value="<?=$id?>"/>
        <div class="serviceImageSection">
            <div class="serviceImageWrapper">
                <img src="<?=$image?>" class="serviceImage" alt="Service image"/>
            </div>
            <div class="serviceUploadImage">Mettre à jour la photo</div>
            <input type="hidden" name="imageChanged" value="false" id="imageChanged"/>
            <input type="file" name="image" onchange="document.getElementById('imageChanged').value = 'true';"/>
        </div>
        <div class="serviceInformationSection">
            <input name="titre" type="text" placeholder="Titre du service" value="<?=htmlspecialchars($titre)?>"/>
            <textarea name="description" placeholder="Description du service"><?=htmlspecialchars($description)?></textarea>
            <input name="duree" type="number" placeholder="Durée du service" value="<?=intval($duree)?>"/>
            <input name="tarif" type="number" placeholder="Tarif du service" value="<?=intval($tarif)?>"/>
            <div id="showService"><input name="actif" type="checkbox" <?php if($actif) { echo "Checked"; }?>/><span id="showServiceLabel">Ce service sera affiché dans le catalogue</span></div>
        </div>
        <div class="serviceSubmit"><input type="submit" name="serviceUpdate" value="Confirmer"/></div>
    </form>
</div>
