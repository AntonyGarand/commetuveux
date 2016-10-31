<?php
require_once('template/header.inc.php');
/*
 * Checking the cart's content and coupons
 */
$items = array_map('intval', getCartItems());
$selectCartItemsQuery = 'SELECT * FROM service WHERE pk_service IN (' . implode(',',$items) . ')';
$selectCartItemsStmt  = $db->query($selectCartItemsQuery);
//Empty cart items
if($selectCartItemsStmt->rowCount() < 1){
    die(header("Location: panier.php"));    
}
$cartItems = $selectCartItemsStmt->fetchAll();
$totalPrice = 0;
array_walk($cartItems, function($item){global $totalPrice;$totalPrice += $item['tarif'];});
$promotion = array('discount'=>0,'service'=>0);
if(isset($_GET['promocode'])){
    $code = strval($_GET['promocode']);
    /*
     * Select all service currently in the cart with an active promotion
     */
    $selectCodeQuery = 'SELECT * FROM promotion JOIN ta_promotion_service ON promotion.pk_promotion = ta_promotion_service.fk_promotion JOIN service ON ta_promotion_service.fk_service = service.pk_service
    WHERE service.pk_service in ('. implode(',',$items) .') AND date_fin > NOW() AND actif = 1 AND code = :code ORDER BY tarif DESC';
    $selectCodeStmt = $db->prepare($selectCodeQuery);
    if($selectCodeStmt->execute(array(':code'=>$code))){
        $promos = $selectCodeStmt->fetchAll();
        if(count($promos)!==0){
            $promotion = $promos[0];
            $discount = $promotion['rabais'] * $promotion['tarif'];
            $totalPrice -= $discount;
        }
    }
}
//die(var_dump($totalPrice));

//Paypal checkout
require_once 'librairies/DPayPal.php'; //Import library

$paypal = new DPayPal(); //Create an DPayPal object
 
//Making SetExpressCheckout API call
//All available parameters for SetExpressCheckout are available at https://developer.paypal.com/docs/classic/api/merchant/SetExpressCheckout_API_Operation_NVP/
 
$code = isset($_GET['promocode']) ? urlencode($_GET['promocode']) : '';
$requestParams = array(
    'RETURNURL' => SITE_URL . "panier.php?confirmpayment=true&coupon=$code", //Enter URL of the page where you want to redirect your user after user enters PayPal login data and confirms order on PayPal page
    'CANCELURL' => SITE_URL . 'panier.php?cancel=true'//Page you want to redirect user to, if user press cancel button on PayPal website
);
//Order settings
$orderParams = array(
    'LOGOIMG' => "test.png", //URL of your website logo. This image which will be displayed to the customer on the PayPal checkout page
    "MAXAMT" => $totalPrice, //Set maximum amount of transaction
    "NOSHIPPING" => "1", //I do not want shipping
    "ALLOWNOTE" => "0", //I do not want to allow notes
    "BRANDNAME" => "Here enter your brand name",
    "GIFTRECEIPTENABLE" => "0",//Disable gift receipt widget on the PayPal pages
    "GIFTMESSAGEENABLE" => "0"//Disables the gift message widget on the PayPal pages
);
 
//Item settings
$totalPrice = number_format($totalPrice, 2, '.', ',');
$item = array(
    'PAYMENTREQUEST_0_AMT' => $totalPrice,
    'PAYMENTREQUEST_0_CURRENCYCODE' => 'CAD',
    'PAYMENTREQUEST_0_ITEMAMT' => "1",
    'L_PAYMENTREQUEST_0_NAME0' => 'Service',
    'L_PAYMENTREQUEST_0_DESC0' => 'Service',
    'L_PAYMENTREQUEST_0_AMT0' => $totalPrice,
    'L_PAYMENTREQUEST_0_QTY0' => '1',
);
 
 
//Now we will call SetExpressCheckout API operation. 

	//call checkout only if payment form is already submitted
	if(isset($_POST['checkout'])) {
		$response = $paypal->SetExpressCheckout($requestParams + $orderParams + $item);
	}
	else {
		$response = $_GET;	//gets the answer from paypal
		if (is_array($response) && $response['ACK'] == 'Success') { //Request successful
			//Now we have to redirect user to the PayPal
			//This is the point where user will be redirected to the PayPal page in order to provide Login details
			//After providing Login details, and after he confirms order in PayPal, user will be redirected to the page which you specified in RETURNURL field
			$token = $response['TOKEN'];
		 
			header('Location: https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . urlencode($token));
		} else if (is_array($response) && $response['ACK'] == 'Failure') {
			var_dump($response);
			exit;
		}
	}
	


