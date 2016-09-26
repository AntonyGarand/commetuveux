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
    $answer = parseSearch();
    
    require_once('template/navbar.inc.php');

    print_r($answer);


