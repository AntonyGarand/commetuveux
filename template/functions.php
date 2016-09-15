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
