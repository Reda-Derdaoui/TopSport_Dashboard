<?php

include __DIR__ . "../../Modules/Connecter.php";

if (isset($_GET["Id_adherent"])) {
    $adherent = $_GET["Id_adherent"];
    try {
        $req = "DELETE FROM personne WHERE id = ?";
        $pst = $pdo->prepare($req);
        $params = [$adherent];
        $pst->execute($params);
    } catch (Exception $ex) {
        $ex->getMessage();
    }
}

header("Location: ../view/Responsable/Client.php");
exit();
