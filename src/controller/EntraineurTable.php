<?php

include __DIR__ . "../../Modules/Connecter.php";

try {

    $sql = "SELECT 
    e.id, 
    p.Prenom, 
    p.Nom, 
    p.Tele, 
    p.DateNaissance, 
    e.Specialite,  
    p_admin.Prenom AS admin_prenom
    FROM entraineur e JOIN personne p ON e.id = p.id 
    JOIN admin a ON e.Adm_id = a.id
    JOIN personne p_admin ON a.id = p_admin.id";

    $stm = $pdo->prepare($sql);
    $stm->execute();
    $entraineurs = $stm->fetchAll();
} catch (Exception $ex) {
    echo $ex->getMessage();
}
