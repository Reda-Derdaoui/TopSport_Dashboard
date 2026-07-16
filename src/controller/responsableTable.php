<?php

include __DIR__ . "../../Modules/Connecter.php";

try {

    $sql = "SELECT  r.id, p_res.Nom, p_res.Prenom, p_res.Tele, p_res.DateNaissance, r.UserName, r.Password, p_admin.Prenom AS admin_prenom
    FROM responsable r JOIN personne p_res ON r.id = p_res.id
    JOIN admin a  ON r.Adm_id = a.id
    JOIN personne p_admin ON a.id = p_admin.id";

    $stm = $pdo->prepare($sql);
    $stm->execute();
    $responsables = $stm->fetchAll();
} catch (Exception $ex) {
    echo $ex->getMessage();
}
