<?php 
require_once 'template/header.inc.php';
if ($_SESSION['role'] !== 'admin') {
    //TODO: Replace header with head and navbar, then do send a location header to redirect
    echo 'Forbidden';
    header('Location: index.php');
}
$invoiceQuery = 'SELECT * FROM facture INNER JOIN ORDER BY pk_service';
$invoice = $db->query($invoiceQuery)->fetchAll();
require_once 'template/navbar.inc.php';

print_r($factures);?>

<!-- /**************************************************************************************************/
/* Fichier ...................... : facture.php */
/* Titre ........................ : Lab Web */
/* Auteur ....................... : Amélie Frappier et Antony Garand */
/* Date de création ............. : 2016-08-22 */
/* Date de mise en ligne ........ : Jamais */
/* Date de mise à jour .......... : 2016-08-22 */
/*******************************************************************************************************/
-->