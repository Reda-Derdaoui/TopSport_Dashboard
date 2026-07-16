<?php

session_start();

if (isset($_SESSION["user_id"])) {
    $idAdmin = $_SESSION["user_id"];
} else {
    echo "user id not found";
}

include __DIR__ . "../../Modules/Connecter.php";
include __DIR__ . "../../Modules/PERSONNE.php";
include __DIR__ . "../../Modules/ENTRAINEUR.php";


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($_POST["prenom"] !== "" && $_POST["nom"] !== "" && $_POST["tele"] !== ""  && $_POST["dateNaissance"] !== "" && $_POST["specialite"]) {

        $personne = new PERSONNE();
        $entraineur = new ENTRAINEUR();

        $DateString = $_POST["dateNaissance"];
        $Date = new DateTime($DateString);
        $today = new DateTime();


        $personne->setPrenom($_POST["prenom"]);
        $personne->setNom($_POST["nom"]);
        $personne->setTele($_POST["tele"]);
        $personne->setDateN($Date);

        $entraineur->setSpecialite($_POST["specialite"]);

        $tele = str_replace(' ', ' ',  $personne->getTele());
        $valid = '/^(\+212|0)[5-7][0-9]{8}$/';

        //check entraineur
        try {
            $req = "SELECT 
            e.Specialite 
            FROM personne p JOIN entraineur e ON p.id = e.id 
            WHERE  Specialite = ?";

            $pst = $pdo->prepare($req);
            $params = [$entraineur->getSpecialte()];
            $pst->execute($params);
            $result = $pst->fetch();
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }

        if (is_array($result)) {
            $error =  "Specialite déjà existant dans le système.";
        } else if ($Date > $today) {
            $errorDateNaissance = "Date de naissance invalide";
        } else if (!preg_match($valid, $tele)) {
            $errorTele = "Le numéro de téléphone est invalide";
        } else if (is_numeric($personne->getNom()) || is_numeric($personne->getPrenom()) || is_numeric($entraineur->getSpecialte())) {
            $numberError = "Les champs Nom et Prénom et Specialite doivent contenir uniquement des lettres.";
            return $numberError;
        } else {

            try {

                $sql = "INSERT INTO personne (Nom, Prenom, Tele, DateNaissance) VALUES(?,?,?,?)";
                $stm = $pdo->prepare($sql);
                $params = [$personne->getNom(), $personne->getPrenom(), $personne->getTele(), $personne->getDateN()->format('Y-m-d')];
                $stm->execute($params);

                $idEntraineur = $pdo->lastInsertId();

                $sql2 = "INSERT INTO entraineur (id, Adm_id, specialite) VALUES (?, ?, ?)";
                $stm = $pdo->prepare($sql2);
                $params = [$idEntraineur, $idAdmin,  $entraineur->getSpecialte()];
                $stm->execute($params);
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
            $validation = "Bien reçu.";
        }
    }
}
