<?php


include __DIR__ . "../../Modules/Connecter.php";
include_once __DIR__ . "../../controller/typeAbonnementConfig.php";
include_once __DIR__ . "../../controller/activiteTable.php";

$idAdherent = "";
$idAbonnement = "";
$prenom = "";
$nom = "";
$tele = "";
$datNaissance = "";
$prix = "";
$dateDebut = "";
$assurance = "";

if ($_SERVER["REQUEST_METHOD"] == "GET") {

    if (!isset($_GET["Id_adherent"]) && !isset($_GET["Id_Abonnement"])) {
        header("Location:  ../view/Responsable/Client.php");
        exit();
    }

    $idAdherent = $_GET["Id_adherent"];
    $idAbonnement = $_GET["Id_Abonnement"];


    try {
        $sql = "SELECT 
                ab.Id_Abonnement,
                ab.Id_Adherent,
                ab.Id_TAbonnement,
                tab.Libelle_TAbonnement AS type_abonnement,
                ab.DateDebut,
                ab.DateFin,
                ab.Prix,

                ad.id AS Id_adherent,
                ad.prixAssurance,
                p.Prenom,
                p.Nom,
                p.Tele,
                p.DateNaissance,

                par.Id_Activite,
                act.Libelle_Activite

                FROM abonnement ab
                JOIN type_abonnement tab ON ab.Id_TAbonnement = tab.Id_TAbonnement
                JOIN adherent ad ON ab.Id_Adherent = ad.id
                JOIN personne p ON ad.id = p.id
                JOIN participer par ON par.id = ad.id
                JOIN activite act ON act.Id_Activite = par.Id_Activite

                WHERE ad.id = ?";

        $stm = $pdo->prepare($sql);
        $params = [$idAdherent];
        $stm->execute($params);
        $results = $stm->fetch();

        if (!isset($results)) {
            header("Location: ../view/Responsable/Client.php");
            exit();
        }

        $prenom = $results["Prenom"];
        $nom = $results["Nom"];
        $tele = $results["Tele"];
        $datNaissance = $results["DateNaissance"];
        $activite = $results["Libelle_Activite"];
        $typeAb = $results["type_abonnement"];
        $dateDebut = $results["DateDebut"];
        $prix = $results["Prix"];
        $assurance = $results["prixAssurance"];
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }
} else {

    $idAdherent   = $_POST["idAdherent"] ?? null;
    $idAbonnement = $_POST["abonn"] ?? null;
    $prenom       = $_POST["prenom"] ?? null;
    $nom          = $_POST["nom"] ?? null;
    $tele         = $_POST["tele"] ?? null;
    $datNaissance = $_POST["dateNaissance"] ?? null;
    $activite      = $_POST["Activite"] ?? null;
    $typeAb       = $_POST["Type_Abon"] ?? null;
    $dateDebut    = $_POST["date"] ?? null;
    $prix         = $_POST["prix"] ?? null;
    $assurance    = $_POST["assurance"] ?? null;


    do {
        if (
            empty($idAdherent)
            || empty($idAbonnement)
            || empty($prenom)
            || empty($nom)
            || empty($tele)
            || empty($datNaissance)
            || empty($activite)
            || empty($typeAb)
            || empty($prix)
            || empty($dateDebut)
            || empty($assurance)
        ) {
            echo "Tous les champs sont obligatoires.";
            exit;
        }

        $tele = $_POST["tele"];
        $tele = str_replace(' ', ' ',  $tele);
        $valid = '/^(\+212|0)[5-7][0-9]{8}$/';
        $dateN = new DateTime($datNaissance); 
        $today = new DateTime(); 

        //check activite
        if (isset($activites)) {
            foreach ($activites as $actRow) {
                if ($activite === $actRow["Libelle_Activite"]) {
                    $idAct = $actRow["Id_Activite"];
                    break;
                }
            }
        }

        //check type abonnement
        if (isset($typeAbonnement)) {
            foreach ($typeAbonnement as $row) {
                if ($typeAb === $row["Libelle_TAbonnement"]) {
                    $typeName = $row["Libelle_TAbonnement"];
                    $idType = $row["Id_TAbonnement"];
                    break;
                }
            }
        }

        //check type abonnement 
        $dateFin = 0;
        if ($typeName === "MENSUEL") {
            $dateFin = date('Y-m-d', strtotime($dateDebut . ' +1 month'));
        } else if ($typeName === "TRIMESTRIEL") {
            $dateFin = date('Y-m-d', strtotime($dateDebut . ' +3 month'));
        } else if ($typeName === "SEMESTRIEL") {
            $dateFin = date('Y-m-d', strtotime($dateDebut . ' +6 month'));
        } else if ($typeName === "ANNUEL") {
            $dateFin = date('Y-m-d', strtotime($dateDebut . ' +1 year'));
        }

        //check the possible error 
        if ($prix < 50) {
            echo  "Prix invalide";
            break;
        } else if ($assurance < 50) {
            echo   "Assurance invalide";
            break;
        } else if ($dateN >= $today) {
            echo "Date naissance incroect";
            break;
        } else if (!preg_match($valid, $tele)) {
            echo  "numero inncorect";
            break;
        }else if (is_numeric($prenom) || is_numeric($nom)){
            echo "Les champs Nom et Prénom doivent contenir uniquement des lettres.";
            break;
        }else {
            try {

                $req1 = "UPDATE personne SET Nom = ?, Prenom = ?, Tele = ?, DateNaissance = ? WHERE id = ?";
                $pst1 = $pdo->prepare($req1);
                $pst1->execute([$nom, $prenom, $tele, $datNaissance, $idAdherent]);

                $req = "UPDATE adherent SET prixAssurance = ? WHERE id = ?";
                $pst = $pdo->prepare($req);
                $params = [$assurance, $idAdherent];
                $pst->execute($params);

                $req2 = "UPDATE participer SET Id_Activite = ? WHERE id = ?";
                $pst2 = $pdo->prepare($req2);
                $params = [$idAct,  $idAdherent];
                $pst2->execute($params);


                $req3 = "UPDATE abonnement SET Id_TAbonnement = ?, DateDebut = ?, DateFin = ?, Prix = ? WHERE Id_Abonnement = ?";
                $pst3 = $pdo->prepare($req3);
                $params = [$idType, $dateDebut, $dateFin, $prix, $idAbonnement];
                $pst3->execute($params);

                header("Location: ../view/Responsable/Client.php");
                exit();
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
        }
    } while (true);
}
