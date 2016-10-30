<?php 
require_once 'template/header.inc.php';
require_once "librairies/DPayPal.php";
$paypal = new DPayPal();

//save token in session variable
if(isset($_GET['token'])) {
    $token=$_GET["token"];//Returned by paypal, you can save this in SESSION too
    $_SESSION['paypaltoken'] = $token;
}

//Fait le paiement si l'adresse de retour à été renvoyée au bon endroit
if (isset($_GET['confirmpayment']) && $_GET['confirmpayment']) {
    $requestParams = array('TOKEN' => $_SESSION['paypaltoken']);

    $response = $paypal->GetExpressCheckoutDetails($requestParams);
    $payerId=$_GET["PAYERID"];//Payer id returned by paypal
    print_r($response); die();

    //Create request for DoExpressCheckoutPayment
    $requestParams=array(
        "TOKEN"=>$_SESSION['paypaltoken'],
        "PAYERID"=>$payerId,
        "PAYMENTREQUEST_0_AMT"=>"20",//Payment amount. This value should be sum of of item values, if there are more items in order
        "PAYMENTREQUEST_0_CURRENCYCODE"=>"EUR",//Payment currency
        "PAYMENTREQUEST_0_ITEMAMT"=>"20"//Item amount
    );

    $transactionResponse=$paypal->DoExpressCheckoutPayment($requestParams);//Execute transaction

    if(is_array($transactionResponse) && $transactionResponse["ACK"]=="Success"){//Payment was successfull
        //Successful Payment
        //empty cart and show successful message
    }
    else{
        //Failure
        //shows error message
    }
}
require_once 'template/navbar.inc.php';

$orderTotal = 0;
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
        <?php 
            $items = getCartItems();
            foreach($items as $serviceId){
                $selectItemQuery = "SELECT * FROM service WHERE pk_service = :id";
                $selectItemStmt = $db->prepare($selectItemQuery);
                $selectItemStmt->execute(array(':id'=>$serviceId));
                if($selectItemStmt->rowCount() == 1){
                    $service = $selectItemStmt->fetch();
                    $orderTotal += $service["tarif"]?>
                    <div class="service" id="service<?=$serviceId?>">
                        <div class="serviceImageWrapper">
                            <img class="serviceImage" src="<?=$service["image"]?>" alt="Image de <?=$service["service_titre"]?>"/>
                        </div>
                        <div class="serviceInformation">
                            <div class="serviceTitre"><?=$service["service_titre"]?></div>
                            <div class="serviceTarif">Tarif: <?=$service["tarif"]?></div>
                        </div>
                        <div class="servicePromotion" id="servicePromotion<?=$serviceId?>"></div>
                        <div class="serviceRemove">Retirer</div>
                    </div>
                <?php }
            }
        ?>
        <div class="sectionPromocode">
            <span>Entrez le code promotionnel pour profiter d'un rabais additionnel</span>
            <input type="text" id="promoCode"/><br/>
            <input type="button" value="Valider"onclick="checkPromocode()" id="validatePromocode"/>
        </div>
        <div class="sectionTotal">
            <div id="orderSubtotal">Sous-total : <?=$orderTotal?>$</div>
            <div id="orderSpecial">rabais additionnel : <?=0?>$</div>
            <hr/>
            <div id="orderTotal">Total : <?=$orderTotal?>$</div>
            <hr/>
            <form id="checkout" name="checkout" method="post" action="payment.php">
                <div id="payment-form"></div>
                <input type="submit" name="checkout" value="Paiement"/>
            </form>
        </div>
    </div>
</body>
</html>

