<?php


include __DIR__ . "../../Modules/Connecter.php";

if (isset($_GET["Id_Activite"])) {

    $id = $_GET["Id_Activite"];
    try {
        $sql = "DELETE FROM activite WHERE Id_Activite = ?";
        $stm = $pdo->prepare($sql);
        $params = [$id];
        $stm->execute($params);
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }
}

header("Location: ../view/Responsable/CatalogueDesPrestation.php");
exit();
