<?php
function validatePost($values)
{
    $errors = array();
    foreach ($values as $value) {
        if (!isset($_POST[$value]) || !is_string($_POST[$value]) || empty($_POST[$value])) {
            $errors[] = $value;
        }
    }

    return $errors;
}

//TODO: Encrypt new password
function generatePassword($user) {

    $pass = openssl_random_pseudo_bytes(20);  //Changes password

    //Inserts new password in DB
    $passChangeQuery = "UPDATE utilisateur SET mot_de_passe=:pass WHERE pk_utilisateur=:userID";
    $stmt = $db->prepare($passChangeQuery);
    $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
    $stmt->bindParam(':userID', $user['pk_utilisateur']);
    if ($stmt->execute()) {
        return $pass;
    }
    else {
        return null;
    }
}

//TODO: Add token functionality
function sendMail($user, $pass) {
    $to = $user['courriel'];
    $subject = "Changement de mot de passe";
    $headers = 'From: <noreply@infoplusplus.com>' . "\r\n" .
        'Reply-To: <noreply@infoplusplus.com>';
    $randomPass = $pass;
    $message = 
        "Bonjour, \r\n\r\n
        Suite à votre demande de réinitialisation de mot de passe, nous vous avons assigné \n
        le mot de passe suivant : " . $randomPass . "\r\n\r\n" .
        "Si vous n'avez pas fait cette demande, veuillez contacter l'administrateur. \r\n\r\n";
    if (mail($to, $subject, $message, $headers, '-fadmin@gmail.com')) {
        return true;
    }
    else {
        return false;
    }
}

function randomString($length = 6) {
    $str = "";
    $characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
    $max = count($characters) - 1;
    for ($i = 0; $i < $length; $i++) {
        $rand = mt_rand(0, $max);
        $str .= $characters[$rand];
    }
    return $str;
}

function getCartItems(){
    $items = array();
    if(isset($_COOKIE['cart']) && is_string($_COOKIE['cart'])){
        $items = explode('|',$_COOKIE['cart']);
    }
    return $items;
}
