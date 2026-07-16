<?php

include __DIR__ . "../../Modules/Connecter.php";

if (isset($_GET["id"])) {

    $id = $_GET["id"];

    try {
        $sql = "DELETE FROM personne WHERE id = ?";
        $stm = $pdo->prepare($sql);
        $params = [$id];
        $stm->execute($params);
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }
}
header("Location: ../view/Admin/Responsables.php");
exit();
