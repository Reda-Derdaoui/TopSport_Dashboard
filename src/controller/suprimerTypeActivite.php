<?php

include __DIR__ . "../../Modules/Connecter.php";


if (isset($_GET["Id_TActivite"])) {

    $id = $_GET["Id_TActivite"];

    try {
        $sql = "DELETE FROM type_activite WHERE Id_TActivite = ?";
        $stm = $pdo->prepare($sql);
        $params = [$id];
        $stm->execute($params);

    } catch (Exception $e) {
        echo $e->getMessage();

    }
}
header("Location: ../view/Responsable/CatalogueDesPrestation.php");
exit();