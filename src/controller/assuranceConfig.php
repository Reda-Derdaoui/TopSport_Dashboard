<?php


include __DIR__ . "../../Modules/Connecter.php";
include __DIR__ . "../../Modules/ASSURANCE.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["add"])) {

        //Add data
        if (!empty($_POST["dateDebut"]) && !empty($_POST["prix"])) {

            $assurance = new ASSURANCE();

            $DateString = $_POST["dateDebut"];
            $Date = new DateTime($DateString);

            $assurance->set_DateDebut($Date);
            $dateFin = date('Y-m-d', strtotime($assurance->get_DateDebut()->format('Y-m-d') . ' +1 year'));
            $prix = $_POST["prix"];
            $assurance->setPrix($prix);

            //check assurance 
            try {
                $req = "SELECT DateDebut, Prix FROM assurance WHERE DateDebut = ? AND Prix = ?";
                $pst = $pdo->prepare($req);
                $params = [$assurance->get_DateDebut()->format('Y-m-d'), $assurance->getPrix()];
                $pst->execute($params);
                $result3 = $pst->fetch();
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }

            if (is_array($result3)) {
                $error3 =  "Assurance déjà existant dans le système.";
            } else {
                try {
                    $sql = "INSERT INTO assurance (DateDebut, DateFin, Prix) VALUES (?, ?, ?)";
                    $stm = $pdo->prepare($sql);
                    $params = [$assurance->get_DateDebut()->format('Y-m-d'), $dateFin, $assurance->getPrix()];
                    $stm->execute($params);
                } catch (Exception $ex) {
                    echo $ex->getMessage();
                }
                $validation3 = "Bien reçu.";
            }
        }
    }

    if (isset($_POST["update"])) {

        //update
        if (!empty($_POST["dateDebut"]) && !empty($_POST["prix"]) && !empty($_POST["id2"])) {

            $id = $_POST["id2"];
            $dateDebut = $_POST["dateDebut"];
            $prix = $_POST["prix"];
            $dateFin = date('Y-m-d', strtotime($dateDebut . ' +1 year'));


            try {
                $sql2 = "UPDATE assurance SET DateDebut = ?, DateFin = ?,  Prix = ? WHERE Id_Assurance  = ?";
                $stm2 = $pdo->prepare($sql2);
                $params2 = [$dateDebut, $dateFin, $prix, $id];
                $stm2->execute($params2);
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
        }
    }
}

try {
    $sql = "SELECT * FROM assurance";
    $stm = $pdo->prepare($sql);
    $stm->execute();
    $assurances = $stm->fetchAll();
} catch (Exception $ex) {
    $ex->getMessage();
}
