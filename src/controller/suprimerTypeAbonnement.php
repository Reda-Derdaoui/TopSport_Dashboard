<?php

include __DIR__ . "../../Modules/Connecter.php";

if (isset($_GET["Id_TAbonnement"])) {

    $id = $_GET["Id_TAbonnement"];

    try {
        $sql = "DELETE FROM type_abonnement WHERE Id_TAbonnement = ?";
        $stm = $pdo->prepare($sql);
        $params = [$id];
        $stm->execute($params);
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }
}
header("Location: ../view/Responsable/CatalogueDesPrestation.php");
exit();