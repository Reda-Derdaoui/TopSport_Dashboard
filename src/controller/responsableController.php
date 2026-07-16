<?php

session_start();

if (isset($_SESSION["user_id"])) {
    $id = $_SESSION["user_id"];
} else {
    echo "user id not found";
}


include __DIR__ . "../../Modules/Connecter.php";
include __DIR__ . "../../Modules/PERSONNE.php";
include __DIR__ . "../../Modules/RESPONSABLE.php";


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (
        $_POST["prenom"] !== ""
        && $_POST["nom"] !== ""
        && $_POST["tele"] !== ""
        && $_POST["login"] !== ""
        && $_POST["password"] !== ""
        && $_POST["dateNaissance"] !== ""
    ) {


        $personne = new PERSONNE();
        $responsable = new Responsable();


        $DateString = $_POST["dateNaissance"];
        $Date = new DateTime($DateString);
        $today = new DateTime();


        $personne->setPrenom($_POST["prenom"]);
        $personne->setNom($_POST["nom"]);
        $personne->setTele($_POST["tele"]);
        $personne->setDateN($Date);

        $responsable->setLogin($_POST["login"]);
        $responsable->setPassword($_POST["password"]);


        $tele = str_replace(' ', ' ',  $personne->getTele());
        $valid = '/^(\+212|0)[5-7][0-9]{8}$/';

        //check responsables 
        try {
            $req = "SELECT * FROM personne p JOIN responsable r ON p.id = r.id WHERE password = ? AND UserName = ?";
            $pst = $pdo->prepare($req);
            $params = [$responsable->getPassword(), $responsable->getLogin()];
            $pst->execute($params);
            $result = $pst->fetch();
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }

        if (is_array($result)) {
            $error =  "Responsable déjà existant dans le système.";
        } else if (!preg_match($valid, $tele)) {
            $errorTele = "Le numéro de téléphone est invalide";
        } else if ($Date > $today) {
            $errorDateNaissance = "Date de naissance invalide";
        } else if (is_numeric($personne->getNom()) || is_numeric($personne->getPrenom())) {
            $numberError = "Les champs Nom et Prénom doivent contenir uniquement des lettres.";
            return $numberError;
        } else {

            try {

                $sql = "INSERT INTO personne (Nom, Prenom, Tele, DateNaissance) VALUES(?,?,?,?)";
                $stm = $pdo->prepare($sql);
                $params = [$personne->getNom(), $personne->getPrenom(), $personne->getTele(), $personne->getDateN()->format('Y-m-d')];
                $stm->execute($params);

                $idResponsable = $pdo->lastInsertId();

                $sql2 = "INSERT INTO responsable (id, Adm_id,  UserName, Password) VALUES (?, ?, ?, ?)";
                $stm = $pdo->prepare($sql2);
                $params = [$idResponsable, $id,  $responsable->getLogin(), $responsable->getPassword()];
                $stm->execute($params);
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }

            $validation = "Bien reçu.";
        }
    }
}
