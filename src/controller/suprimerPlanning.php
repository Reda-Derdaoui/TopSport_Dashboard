<?php
include __DIR__ . "../../Modules/Connecter.php";

if (isset($_GET["Id_Planning"])) {

    $id = $_GET["Id_Planning"];

    try {
        $sql = "DELETE FROM planing WHERE Id_Planning = ?";
        $stm = $pdo->prepare($sql);
        $params = [$id];
        $stm->execute($params);
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }
}
header("Location: ../view/Responsable/Planing.php");
exit();
