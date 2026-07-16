<?php
session_start();

include __DIR__ . "../../Modules/Connecter.php";
include __DIR__ . "../../Modules/ACTIVITE.php";
include __DIR__ . "../../Modules/Connecter.php";


if (isset($_SESSION["user_id"])) {
    $idResponsbale = $_SESSION["user_id"];
} else {
    echo "user id not found";
}


//get responsable data
try {
    $sql = "SELECT personne.id,  personne.Prenom FROM personne Join entraineur ON personne.id = entraineur.id";
    $stm = $pdo->prepare($sql);
    $stm->execute();
    $entraineurs = $stm->fetchAll();
} catch (Exception $ex) {
    echo $ex->getMessage();
}

//get assurances data
try {
    $sql = "SELECT Id_Assurance, Prix FROM assurance";
    $stm = $pdo->prepare($sql);
    $stm->execute();
    $ass = $stm->fetchAll();
} catch (Exception $ex) {
    echo $ex->getMessage();
}

// activite configuration
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["add_act"])) {

        if (!empty($_POST["activite"]) && !empty($_POST["entraineur"]) && !empty($_POST["assu"])) {

            $activite = new ACTIVITE();
            $activite->setLibelle($_POST["activite"]);

            $entraineur = $_POST["entraineur"];
            $assurance = $_POST["assu"];

            //entraineur check
            if (isset($entraineurs)) {
                foreach ($entraineurs as $row) {
                    if ($entraineur == $row["Prenom"]) {
                        $idEntraineur = $row["id"];
                        break;
                    }
                }
            }

            //assurance check
            if (isset($ass)) {
                foreach ($ass as $row) {
                    if ($assurance == $row["Prix"]) {
                        $idAssurance = $row["Id_Assurance"];
                    }
                }
            }

            //check activites 
            try {
                $req = "SELECT Libelle_Activite FROM activite WHERE Libelle_Activite = ?";
                $pst = $pdo->prepare($req);
                $params = [$activite->getLibelle()];
                $pst->execute($params);
                $result = $pst->fetch();
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }

            if (is_array($result)) {
                $error =  "Activite déjà existant dans le système.";
            } else {
                try {
                    $sql = "INSERT INTO activite (Id_Assurance, id, Res_id, Libelle_Activite) VALUES (?, ?, ?, ?)";
                    $stm = $pdo->prepare($sql);
                    $params = [$idAssurance, $idEntraineur,  $idResponsbale, $activite->getLibelle()];
                    $stm->execute($params);
                } catch (Exception $ex) {
                    echo $ex->getMessage();
                }
                $validation = "Bien reçu.";
            }
        }
    } else if (isset($_POST["update_act"])) {

        if (!empty($_POST["activite"])  && !empty($_POST["id"]) && !empty($_POST["entraineur"]) && !empty($_POST["assu"])) {

            $id = $_POST["id"];
            $activite = $_POST["activite"];
            $entraineur = $_POST["entraineur"];
            $assurance = $_POST["assu"];

            //entraineur check
            if (isset($entraineurs)) {
                foreach ($entraineurs as $row) {
                    if ($entraineur == $row["Prenom"]) {
                        $idEntraineur = $row["id"];
                        break;
                    }
                }
            }

            //assurance check
            if (isset($ass)) {
                foreach ($ass as $row) {
                    if ($assurance == $row["Prix"]) {
                        $idAssurance = $row["Id_Assurance"];
                        break;
                    }
                }
            }

            try {
                $sql = "UPDATE activite SET Id_Assurance = ?, id = ?, Libelle_Activite = ? WHERE Id_Activite  = ?";
                $stm = $pdo->prepare($sql);
                $params = [$idAssurance,  $idEntraineur, $activite, $id];
                $stm->execute($params);
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
        }
    }
}
