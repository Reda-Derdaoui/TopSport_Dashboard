<?php

include __DIR__ . "../../Modules/Connecter.php";

$id = "";
$prenom = "";
$nom = "";
$tel = "";
$date = "";
$specialite = "";

if ($_SERVER["REQUEST_METHOD"] == "GET") {

    if (!isset($_GET["id"])) {
        header("Location:  ../view/Admin/Entraineurs.php");
        exit();
    }

    $id = $_GET["id"];

    try {

        $sql = "SELECT 
        personne.id, 
        personne.Nom, 
        personne.Prenom, 
        personne.Tele, 
        personne.DateNaissance, 
        entraineur.id, 
        entraineur.specialite 
        FROM personne 
        LEFT JOIN entraineur ON personne.id = entraineur.id WHERE personne.id = ?";

        $stm = $pdo->prepare($sql);
        $params = [$id];
        $stm->execute($params);
        $results = $stm->fetch();

        if (!$results) {
            header("Location: ../view/Admin/Entraineurs.php");
            exit();
        }

        $prenom = $results["Prenom"];
        $nom = $results["Nom"];
        $tel = $results["Tele"];
        $date = $results["DateNaissance"];
        $specialite = $results["specialite"];
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }
} else {
    $id = $_POST["id"];
    $prenom = $_POST["prenom"];
    $nom = $_POST["nom"];
    $tel = $_POST["tele"];
    $date = $_POST["dateNaissance"];
    $specialite = $_POST["specialite"];

    do {
        if (empty($prenom) || empty($nom) || empty($tel) || empty($date) || empty($specialite)) {
            echo "Tous les champs sont obligatoires.";
            break;
        } else {

            try {

                //update personne firts
                $sql = "UPDATE personne SET Nom = ?, Prenom = ?, Tele = ?, DateNaissance = ? WHERE id = ?";
                $stm = $pdo->prepare($sql);
                $params = [$nom, $prenom, $tel, $date, $id];
                $stm->execute($params);

                //update Entraineur 
                $sql = "UPDATE entraineur SET specialite = ? WHERE id = ?";
                $stm = $pdo->prepare($sql);
                $params = [$specialite, $id];
                $stm->execute($params);

                header("Location: ../view/Admin/Entraineurs.php");
                exit();
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
        }
    } while (true);
}
