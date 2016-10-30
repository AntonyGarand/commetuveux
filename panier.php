<?php 
require_once 'template/header.inc.php';

//check if client type


//Fait le paiement si l'adresse de retour à été renvoyée au bon endroit
if (isset($_GET['confirmpayment']) && $_GET['confirmpayment']) {
	
	$token=$_GET["token"];//Returned by paypal, you can save this in SESSION too
	$paypal = new Paypal();
	$requestParams = array('TOKEN' => $token);
	
	$response = $paypal->GetExpressCheckoutDetails($requestParams);
	$payerId=$response["PAYERID"];//Payer id returned by paypal
	
	//Create request for DoExpressCheckoutPayment
	$requestParams=array(
		"TOKEN"=>$token,
		"PAYERID"=>$payerId,
		"PAYMENTREQUEST_0_AMT"=>"20",//Payment amount. This value should be sum of of item values, if there are more items in order
		"PAYMENTREQUEST_0_CURRENCYCODE"=>"CAD",//Payment currency
		"PAYMENTREQUEST_0_ITEMAMT"=>"1"//Item amount
	);
	$transactionResponse=$paypal->DoExpressCheckoutPayment($requestParams);//Execute transaction
	
	if(is_array($transactionResponse) && $transactionResponse["ACK"]=="Success"){//Payment was successfull
		//Successful Payment
	}
	else{
		//Failure
	}
}

require_once 'template/navbar.inc.php';

?>
<!DOCTYPE html>

<!-- /**************************************************************************************************/
/* Fichier ...................... : panier.php */
/* Titre ........................ : Lab Web */
/* Auteur ....................... : Amélie Frappier et Antony Garand */
/* Date de création ............. : 2016-08-22 */
/* Date de mise en ligne ........ : Jamais */
/* Date de mise à jour .......... : 2016-08-22 */
/*******************************************************************************************************/ -->


<body>
    <div class="contenu">

        <br><br>	
    	<hr>
        
	    <h2>Paiement</h2>
		
		<form id="checkout" method="post" action="payment.php">
		  <div id="payment-form"></div>
		  <input type="submit" value="Payer">
		</form>
		
   </div>

</body>
</html>
-->

