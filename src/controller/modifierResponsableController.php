<?php

include __DIR__ . "../../Modules/Connecter.php";

$id = "";
$prenom = "";
$nom = "";
$tel = "";
$date = "";
$login = "";
$password = "";

if ($_SERVER["REQUEST_METHOD"] == "GET") {

    if (!isset($_GET["id"])) {
        header("Location:  ../view/Admin/Responsable.php");
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
        responsable.id, 
        responsable.UserName, 
        responsable.Password 
        FROM personne 
        LEFT JOIN responsable ON personne.id = responsable.id WHERE personne.id = ?";

        $stm = $pdo->prepare($sql);
        $params = [$id];
        $stm->execute($params);
        $results = $stm->fetch();

        if (!$results) {
            header("Location: ../view/Responsable/Responsables.php");
            exit();
        }

        $prenom = $results["Prenom"];
        $nom = $results["Nom"];
        $tel = $results["Tele"];
        $date = $results["DateNaissance"];
        $login = $results["UserName"];
        $password = $results["Password"];
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }
} else {
    $id = $_POST["id"];
    $prenom = $_POST["prenom"];
    $nom = $_POST["nom"];
    $tel = $_POST["tele"];
    $date = $_POST["dateNaissance"];
    $login = $_POST["login"];
    $password = $_POST["password"];

    do {
        if (
            empty($prenom)
            || empty($nom)
            || empty($tel)
            || empty($date)
            || empty($login)
            || empty($password)
        ) {
            echo "Tous les champs sont obligatoires.";
            break;
        } else {

            try {

                //update personne firts
                $sql = "UPDATE personne SET Nom = ?, Prenom = ?, Tele = ?, DateNaissance = ? WHERE id = ?";
                $stm = $pdo->prepare($sql);
                $params = [$nom, $prenom, $tel, $date, $id];
                $stm->execute($params);

                //update Responsable 
                $sql = "UPDATE responsable SET UserName = ?, Password = ? WHERE id = ?";
                $stm = $pdo->prepare($sql);
                $params = [$login, $password, $id];
                $stm->execute($params);

                header("Location: ../view/Admin/Responsables.php");
                exit();
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
        }
    } while (true);
}
