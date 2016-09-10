<?php 
require_once 'template/header.inc.php';
if ($_SESSION['role'] !== 'admin') {
    //TODO: Replace header with head and navbar, then do send a location header to redirect
    echo 'Forbidden';
    header('Location: index.php');
}

$baseInvoicesQuery = 'SELECT facture.pk_facture, facture.date_service, facture.no_confirmation, 
				client.prenom, client.nom 
				FROM facture
				INNER JOIN client ON client.pk_client=facture.fk_client  
				ORDER BY facture.date_service DESC';
$baseInvoices = $db->query($baseInvoicesQuery)->fetchAll();
for ($i = 0; $i < count($baseInvoices); $i++) {
	$serviceQuery = 'SELECT service.service_titre, service.tarif, 
					ta_facture_service.tarif_facture, 
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

//print_r($baseInvoices); //TODO: Remove when debug is done ?>

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
	foreach ($baseInvoices as $invoice) {
	?>

		<div class="invoiceContent">
		
		<div class="invoiceNbWrapper">
			<span class="invoiceNb"><?=$invoice['pk_facture']?></span>
		</div>
		
		<div class="invoiceClientWrapper">
			<span class="invoiceClient"><?=$invoice['prenom'] . ' ' . $invoice['nom']; ?></span> <br/>
			<span class="invoiceConfirmation"><?=strtoupper($invoice['no_confirmation']) ?></span> 
		</div>
		
		<div class="invoiceDateWrapper">
			<span class="invoiceDate"><?= date('d/m/Y',strtotime($invoice['date_service'])); ?></span> <br/>
			<?php 
				$invoiceTotal = 0;
				foreach ($invoice['services'] as $service) {
					$invoiceTotal += $service['tarif_facture'];
				}
			?>
			<span class="invoiceTarif"><?=$invoiceTotal?>$</span>
		</div>
		
		
		<div class="invoiceDetail" id="invoiceDetail">
		
			<?php foreach ($invoice['services'] as $service) {?>
				<div class="invoiceService" id="invoiceService">
					
					<?php 
					if ($service['code'] != NULL) {
					
						//Promotion title
						switch ($service['code']) {
							case "rentree2016":
								$promoTitle = "Grand solde de la rentrée";
								break;
							case "noel2016":
								$promoTitle = "Grand solde de Noël";
								break;
							case "o365":
								$promoTitle = "Promotion spéciale Office 365";
								break;
							default:
								$promoTitle = "Promotion";
								break;
							}
						
						//Rebate on the promotion
						$promoTarif = $service['tarif'] * $service['rabais'];
						
						} ?>
						
						<div class="invoiceServiceTitleWrapper">
							<span class="invoiceServiceTitle"><?=$service['service_titre']?></span> <br/>
							<?php if($service['code'] != NULL) { ?>
								<span class="invoiceServicePromoTitle"><?=$promoTitle . '(' . $service['rabais'] * 100 . '%)'?></span>
							<?php } ?>
						</div>
						
						<div class="invoiceServiceRabaisWrapper">
							<span class="invoiceServiceTarif"><?=number_format($service['tarif'], 2) . '$'?></span> <br/>
							<?php if($service['code'] != NULL) { ?>
								<span class="invoiceServicePromoTarif"><?='-' . number_format($promoTarif, 2) . '$'?></span>
							<?php } ?>
						</div>
						
					</div>
			<?php } ?>
			
			<div class="invoiceToggleWrapper">
				<a class="invoiceToggle" href="#" onclick="toggleDetail();">Détail</a>
			</div>
			
		
	</div>
		
	</div>
	
<?php } ?>

<script>
		// function toggleDetail() {
			// var div = document.getElementById('invoiceService');
			// if (div.style.display === 'none') {
				// div.style.display = 'inline-block';
			// }
			// else {
				// div.style.display = 'none';
			// }
		// }
	// </script>