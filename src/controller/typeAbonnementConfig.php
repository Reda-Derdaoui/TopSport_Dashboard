<?php

include __DIR__ . "../../Modules/Connecter.php";
include __DIR__ . "../../Modules/TYPE_ABONNEMENT.php";


// ajouter type abonnement
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["add_type"])) {

        //Add data 
        if (!empty($_POST["typeAbonnement"])) {

            $type_abonnement = new TYPE_ABONNEMENT();

            $type_abonnement->setLibelle($_POST["typeAbonnement"]);

            //check type abonnement 
            try {
                $req = "SELECT Libelle_TAbonnement FROM type_abonnement WHERE Libelle_TAbonnement = ?";
                $pst = $pdo->prepare($req);
                $params = [$type_abonnement->getLibelle()];
                $pst->execute($params);
                $result2 = $pst->fetch();
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }

            if (is_array($result2)) {
                $error2 =  "Type abonnement déjà existant dans le système.";
            } else {
                try {
                    $sql = "INSERT INTO type_abonnement(Libelle_TAbonnement) VALUES (?)";
                    $stm = $pdo->prepare($sql);
                    $params = [$type_abonnement->getLibelle()];
                    $stm->execute($params);
                } catch (Exception $ex) {
                    echo $ex->getMessage();
                }
                $validation2 = "Bien reçu.";
            }
        }
    } else if (isset($_POST["update_type"])) {

        //update
        if (!empty($_POST["typeAbonnement"]) && !empty($_POST["id"])) {

            $id = $_POST["id"];
            $type = $_POST["typeAbonnement"];

            try {
                $sql = "UPDATE type_abonnement SET Libelle_TAbonnement = ? WHERE Id_TAbonnement = ?";
                $stm = $pdo->prepare($sql);
                $params = [$type, $id];
                $stm->execute($params);
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
        }
    }
}

//get the data
try {
    $sql = "SELECT * FROM  type_abonnement";
    $stm = $pdo->prepare($sql);
    $stm->execute();
    $typeAbonnement = $stm->fetchAll();
} catch (Exception $ex) {
    echo $ex->getMessage();
}
