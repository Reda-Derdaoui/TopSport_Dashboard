<?php

include __DIR__ . "../../Modules/Connecter.php";
include __DIR__ . "../../Modules/TYPE_ACTIVITE.php";
include __DIR__ . "/activiteTable.php";


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["addTypeActivite"])) {

        if (!empty($_POST["typeActivte"]) && !empty($_POST["act"])) {

            $typeAc = new TYPE_ACTIVITE();
            $type = $_POST["typeActivte"];

            $typeAc->setTAC($type);

            $activite = $_POST["act"];

            //check activities 
            if (isset($activites)) {
                foreach ($activites as $row) {
                    if ($activite === $row["Libelle_Activite"]) {
                        $idActivite = $row["Id_Activite"];
                        break;
                    }
                }
            }

            //check type activite 
            try {
                $req = "SELECT Libelle_TActivite FROM type_activite WHERE Libelle_TActivite = ?";
                $pst = $pdo->prepare($req);
                $params = [$typeAc->getTAC()];
                $pst->execute($params);
                $result4 = $pst->fetch();
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }

            if (is_array($result4)) {
                $error4 =  "Type activite déjà existant dans le système.";
            } else {
                try {
                    $sql = "INSERT INTO type_activite (Id_Activite, Libelle_TActivite) VALUES (?, ?)";
                    $stm = $pdo->prepare($sql);
                    $params = [$idActivite, $typeAc->getTAC()];
                    $stm->execute($params);
                } catch (Exception $ex) {
                    echo $ex->getMessage();
                }
                $validation4 = "Bien reçu.";
            }
        }
    } else if (isset($_POST["updateTypeActivite"])) {

        if (!empty($_POST["typeActivte"]) && !empty($_POST["act"]) && !empty($_POST["id4"])) {

            $id = $_POST["id4"];
            $typeAct = $_POST["typeActivte"];
            $acti = $_POST["act"];

            //check activite data
            if (isset($activites)) {
                foreach ($activites as $row) {
                    if ($acti === $row["Libelle_Activite"]) {
                        $idActivite = $row["Id_Activite"];
                        break;
                    }
                }
            }

            try {
                $sql = "UPDATE type_activite SET Id_Activite = ?, Libelle_TActivite = ? WHERE Id_TActivite  = ?";
                $stm = $pdo->prepare($sql);
                $params = [$idActivite, $typeAct, $id];
                $stm->execute($params);
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
        }
    }
}


//get type activite data
try {

    $sql = "SELECT 
    t.Id_TActivite, 
    t.Libelle_TActivite,  
    t.Id_Activite, 
    a.Id_Activite, 
    a.Libelle_Activite AS activite_nom 
    FROM activite a JOIN type_activite t ON t.Id_Activite = a.Id_Activite";
    $stm = $pdo->prepare($sql);
    $stm->execute();
    $types = $stm->fetchAll();
} catch (Exception $ex) {
    echo $ex->getMessage();
}
