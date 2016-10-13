<?php

	//TODO: Change to config once bug is resolved. I am aware this fix is terrible. :P
    $db = new PDO('mysql:host=127.0.0.1;dbname=tia16001;charset=UTF8', "root", "");

	if (isset($_POST['clientId'])) {
		$clientInfoQuery = "SELECT client.prenom, client.nom, client.telephone, adresse.no_civique, adresse.rue, adresse.code_postal, ville.ville 
							FROM client 
							INNER JOIN adresse ON adresse.pk_adresse=client.fk_adresse 
							INNER JOIN ville ON ville.pk_ville=adresse.fk_ville 
							WHERE pk_client=:id";
		$stmt = $db->prepare($clientInfoQuery);
		$stmt->bindParam(':id', $_POST['clientId']);
		if (!($stmt->execute())) {
			$errors[] = "Impossible d'afficher les informations du client.";
		}
		else {
			$clientInfo = $stmt->fetchAll();
			echo json_encode($clientInfo);
		}
	}
 ?>