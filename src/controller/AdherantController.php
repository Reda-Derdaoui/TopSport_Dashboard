<?php

include __DIR__ . "../../Modules/Connecter.php";
include_once __DIR__ . "../../controller/activiteTable.php";
include_once __DIR__ . "../../controller/typeAbonnementConfig.php";


if (isset($_SESSION["user_id"])) {
    $idRes = $_SESSION["user_id"];
} else {
    echo "Responsable not found";
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (
        !empty($_POST["prenom"])
        && !empty($_POST["nom"])
        && !empty($_POST["tele"])
        && !empty($_POST["dateNaissance"])
        && !empty($_POST["Activite"])
        && !empty($_POST["Type_Abon"])
        && !empty($_POST["date"])
        && !empty($_POST["prix"])
        && !empty($_POST["assurance"])
    ) {

        if (isset($_POST["addAdherent"])) {

            $dateDebut = $_POST["date"];
            $datNaissance = $_POST["dateNaissance"];
            $date = new DateTime($dateDebut);
            $dateN = new DateTime($datNaissance);
            $today = new DateTime();
            $tele = $_POST["tele"];
            $tele = str_replace(' ', ' ',  $tele);
            $valid = '/^(\+212|0)[5-7][0-9]{8}$/';
            $prenom = $_POST["prenom"];
            $nom = $_POST["nom"];
            $activite = $_POST["Activite"];
            $typeAb = $_POST["Type_Abon"];
            $prix = $_POST["prix"];
            $assurance = $_POST["assurance"];

            //check type abonnement
            if (isset($typeAbonnement)) {
                foreach ($typeAbonnement as $row) {
                    if ($typeAb === $row["Libelle_TAbonnement"]) {
                        $typeName = $row["Libelle_TAbonnement"];
                        $idType = $row["Id_TAbonnement"];
                    }
                }
            }

            $dateFin = 0;
            if ($typeName === "MENSUEL") {
                $dateFin = date('Y-m-d', strtotime($date->format('Y-m-d') . ' +1 month'));
            } else if ($typeName === "TRIMESTRIEL") {
                $dateFin = date('Y-m-d', strtotime($date->format('Y-m-d') . ' +3 month'));
            } else if ($typeName === "SEMESTRIEL") {
                $dateFin = date('Y-m-d', strtotime($date->format('Y-m-d') . ' +6 month'));
            } else if ($typeName === "ANNUEL") {
                $dateFin = date('Y-m-d', strtotime($date->format('Y-m-d') . ' +1 year'));
            }

            //check activite 
            if (isset($activites)) {
                foreach ($activites as $actRow) {
                    if ($activite === $actRow["Libelle_Activite"]) {
                        $idAct = $actRow["Id_Activite"];
                    }
                }
            }

            //check adherent
            try {
                $req = "SELECT  
                p.Prenom,
                p.Nom, 
                ad.id,
                part.Id_Activite,
                part.id,
                act.Libelle_Activite
                FROM personne p JOIN adherent ad ON p.id = ad.id
                JOIN participer part ON part.id = ad.id
                JOIN activite act ON act.Id_Activite = part.Id_Activite
                WHERE Nom = ? AND Prenom = ? AND Libelle_Activite = ?";
                $pst = $pdo->prepare($req);
                $params = [$nom,  $prenom, $activite];
                $pst->execute($params);
                $result = $pst->fetch();
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }

            //check for any error possible 
            if ($_POST["prix"] < 50) {
                $errorPrice = "Prix invalide";
                return  $errorPrice;
            } else if ($_POST["assurance"] < 50) {
                $errorAssurance = "Assurance invalide";
                return  $errorAssurance;
            } else if ($dateN > $today) {
                $errorDateNaissance = "Date de naissance invalide";
                return $errorDateNaissance;
            } else if (!preg_match($valid, $tele)) {
                $errorTele = "Le numéro de téléphone est invalide";
                return $errorTele;
            } else if (is_array($result)) {
                $error =  "Adherent déjà existant dans le système.";
                return $error;
            } else if (is_numeric($prenom) || is_numeric($nom)) {
                $numberError = "Les champs Nom et Prénom doivent contenir uniquement des lettres.";
                return $numberError;
            } else {

                try {
                    //personne
                    $sql = "INSERT INTO personne (Nom, Prenom, Tele, DateNaissance) VALUES (?, ?, ?, ?)";
                    $stm = $pdo->prepare($sql);
                    $params = [$nom, $prenom, $tele, $datNaissance];
                    $stm->execute($params);

                    $idAdherent = $pdo->lastInsertId();

                    //adherent
                    $sql2 = "INSERT INTO  adherent (id, Res_id, prixAssurance) VALUES (?, ?, ?)";
                    $stm2 = $pdo->prepare($sql2);
                    $params2 = [$idAdherent, $idRes, $assurance];
                    $stm2->execute($params2);


                    //abonnement
                    $sql3 = "INSERT INTO abonnement (Id_Adherent, Id_TAbonnement, id, DateDebut, DateFin, Prix) VALUES (?, ?, ?, ?, ?, ?)";
                    $stm3 = $pdo->prepare($sql3);
                    $params3 = [$idAdherent, $idType, $idRes, $date->format('Y-m-d'), $dateFin, $prix];
                    $stm3->execute($params3);

                    //participer 
                    $sql4 = "INSERT INTO  participer (id, Id_Activite) VALUES (?, ?)";
                    $stm4 = $pdo->prepare($sql4);
                    $params4 = [$idAdherent, $idAct];
                    $stm4->execute($params4);
                } catch (Exception $ex) {
                    $ex->getMessage();
                }
                $validation = "Bien reçu.";
            }
        }
    }
}


//get adherent data
try {
    $sql = "SELECT 
    ab.Id_Abonnement AS Id_Abonnement,
    ab.Id_Adherent,
    ab.Id_TAbonnement,
    ab.DateDebut, 
    ab.DateFin, 
    ab.Prix, 
    tab.Id_TAbonnement,
    tab.Libelle_TAbonnement  AS type_abonnement,
    ad.id AS Id_adherent, 
    ad.prixAssurance,  
    p.Prenom, 
    p.Nom, 
    p.Tele, 
    p.DateNaissance,
    ad.Res_id,     
    r.id,
    p_res.Prenom AS responsable_nom,     
    par.id, 
    par.Id_Activite,    
    act.Id_Activite, 
    act.Libelle_Activite AS activite, 
    act.id,    
    e.id, 
    p_ent.Prenom AS entraineur_nom
    FROM abonnement ab
    JOIN type_abonnement tab ON  ab.Id_TAbonnement = tab.Id_TAbonnement
    JOIN adherent ad ON ab.Id_Adherent = ad.id
    JOIN personne p ON ad.id = p.id 
    JOIN responsable r ON ad.Res_id = r.id
    JOIN personne p_res ON p_res.id = r.id
    JOIN participer par ON par.id = ad.id
    JOIN activite act ON act.Id_Activite = par.Id_Activite
    JOIN entraineur e ON act.id = e.id
    JOIN personne p_ent ON e.id = p_ent.id 
    WHERE r.id = ?";

    $stm = $pdo->prepare($sql);
    $params = [$idRes];
    $stm->execute($params);
    $adherents = $stm->fetchAll();
} catch (Exception $ex) {
    echo $ex->getMessage();
}
