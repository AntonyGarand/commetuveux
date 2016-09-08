<?php 
require_once 'template/header.inc.php';
if ($_SESSION['role'] !== 'admin') {
    //TODO: Replace header with head and navbar, then do send a location header to redirect
    echo 'Forbidden';
    header('Location: index.php');
}

$baseInvoicesQuery = 'SELECT facture.pk_facture, facture.date_service, facture.no_confirmation, 
				client.prenom, client.nom, 
				ta_facture_service.tarif_facture 
				FROM facture
				INNER JOIN client ON client.pk_client=facture.fk_client 
				INNER JOIN ta_facture_service ON ta_facture_service.fk_facture=facture.pk_facture 
				ORDER BY facture.date_service DESC';
$baseInvoices = $db->query($baseInvoicesQuery)->fetchAll();
for ($i = 0; $i < count($baseInvoices); $i++) {
	$serviceQuery = 'SELECT service.service_titre, service.tarif,
					ta_promotion_service.code, 
					promotion.rabais 
					FROM service
					LEFT JOIN ta_promotion_service ON ta_promotion_service.fk_service=service.pk_service
					INNER JOIN ta_facture_service ON service.pk_service=ta_facture_service.fk_service 
					LEFT JOIN promotion ON ta_promotion_service.fk_promotion=promotion.pk_promotion 
					WHERE ta_facture_service.fk_facture=' .$baseInvoices[$i]['pk_facture'];
	
	$baseInvoices[$i]['services']=$db->query($serviceQuery)->fetchAll();
	//$invoice = array_merge($invoice, );
}

// array_walk($baseInvoices, function($invoice){var_dump($invoice);echo "<br/>";});
// die('Done'); 
// /*$serviceQuery = 'SELECT 
				// service.service_titre, service.tarif, 
				// ta_promotion_service.code, 
				// promotion.rabais
				// FROM facture 
				// INNER JOIN service ON ta_facture_service.fk_service=service.pk_service 
				 
				// ';*/
// $invoices = $db->query($invoiceQuery)->fetchAll();
require_once 'template/navbar.inc.php';

// print_r($baseInvoices); //TODO: Remove when debug is done ?>

<!-- /**************************************************************************************************/
/* Fichier ...................... : facture.php */
/* Titre ........................ : Lab Web */
/* Auteur ....................... : Amélie Frappier et Antony Garand */
/* Date de création ............. : 2016-08-22 */
/* Date de mise en ligne ........ : Jamais */
/* Date de mise à jour .......... : 2016-08-22 */
/*******************************************************************************************************/
-->

<?php
	foreach ($invoices as $invoice) {
	?>
	<div class="invoiceContent">
		
		<span class="invoiceNb"><?=$invoice['pk_facture']?></span>
		<span class="invoiceClient"><?=$invoice['prenom'] . ' ' . $invoice['nom']; ?></span>
		<span class="invoiceConfirmation"><?=$invoice['no_confirmation'] ?></span>
		<span class="invoiceDate"><?= date('d/m/Y',strtotime($invoice['date_service'])); ?></span>
		<span class="invoiceTarif"><?=$invoice['tarif_facture']?>$</span>
		<a href="#">Détail</a>
		<div class="invoiceDetail">
			
		</div>
	</div>

<?php } ?>