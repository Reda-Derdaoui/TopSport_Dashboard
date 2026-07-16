<?php
include __DIR__ . "../../Modules/Connecter.php";

if (isset($_GET["Id_Assurance"])) {

    $id = $_GET["Id_Assurance"];

    try {
        $sql = "DELETE FROM assurance WHERE Id_Assurance = ?";
        $stm = $pdo->prepare($sql);
        $params = [$id];
        $stm->execute($params);
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }
}
header("Location: ../view/Responsable/CatalogueDesPrestation.php");
exit();