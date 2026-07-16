<?php

include __DIR__ . "../../Modules/Connecter.php";

try {
    $sql = "SELECT 
    a.Id_Assurance, 
    a.Prix AS assurance_prix, 
    e.id, 
    p_entr.Prenom AS entraineur_prenom,
    r.id, 
    p_res.Prenom AS responsable_prenom,
    act.Id_Activite, 
    act.Libelle_Activite
    FROM assurance a JOIN activite act ON a.Id_Assurance = act.Id_Assurance
    JOIN entraineur e ON act.id = e.id
    JOIN personne p_entr ON e.id = p_entr.id 
    JOIN responsable r ON act.Res_id = r.id
    JOIN personne p_res ON r.id = p_res.id";

    $stm = $pdo->prepare($sql);
    $stm->execute();
    $activites = $stm->fetchAll();
} catch (Exception $ex) {
    echo $ex->getMessage();
}
