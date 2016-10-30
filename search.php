<?php
    require_once('template/header.inc.php');

    function parseSearch(){
        global $db;
        if(!isset($_GET['search']) ||!is_string($_GET['search'])){
            return "Vous devez effectuer une recherche!";
        }
        $searchTerms = explode(' ', urldecode($_GET['search']));

        //Prepend and append "%" to the strings
        array_walk($searchTerms, function(&$term){$term = '%' . $term . '%';});
        
        $searchQuery= "SELECT * FROM service WHERE actif = 1 AND (";
        
        for($i = 0; $i < count($searchTerms); $i++){
            if($i !== 0){
                $searchQuery .= ' OR';
            }
            $searchQuery .= " service_titre LIKE :param$i OR service_description like :param$i";

            $searchTerms["param$i"] = $searchTerms[$i];
            unset($searchTerms[$i]);
        }
        $searchQuery .= ")";

        $searchStmt = $db->prepare($searchQuery);

        if($searchStmt->execute($searchTerms)){
            $result = $searchStmt->fetchAll();
            return $result;
        } else {
            return false;
        }
        return $searchQuery;
    }
    $products = parseSearch();
    require_once('template/navbar.inc.php');

    if(!$products){
        echo "<h2>Aucun produit n'à été trouvé!</h2>";
    } else {
        foreach ($products as $product) {
        ?>
            <div class="catalogue">
                <div class="serviceImageWrapper">
                    <img class="serviceImage" src="<?=$product['image']?>" alt="Image de <?=$product['service_titre']?>"/>
                </div>
                <div class="serviceContent">
                    <h2 class="serviceTitle"><?=htmlspecialchars($product['service_titre'])?></h2>
                    <p class="serviceDescription"><?=htmlspecialchars($product['service_description'])?></p>
                    <div class="servicePriceAndLengthWrapper">
                        <span class="servicePriceWrapper servicePrice">Tarif : <?=intval($product['tarif'])?>$</span>
                        <span class="serviceLengthWrapper serviceLength">Durée : <?=intval($product['duree'])?> h</span>
                        <div class="serviceAddToCartWrapper">
                            <button class="addToCartBtn" onclick="addToCart(<?=intval($product['pk_service'])?>)"></button>
                        </div>
                    </div>
                </div>
            </div>
        <?php }
    }
    require_once('template/footer.inc.php');
