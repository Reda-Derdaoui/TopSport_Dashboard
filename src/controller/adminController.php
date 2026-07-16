<?php
include __DIR__ . "../../Modules/Connecter.php";
//get adherent data
try {
    $sql = "SELECT 
    ab.Id_Abonnement AS Id_Abonnement,
    ab.Id_Adherent,
    ab.Id_TAbonnement,
    ab.DateDebut, 
    ab.DateFin, 
    ab.Prix, 
    tab.Id_TAbonnement,
    tab.Libelle_TAbonnement  AS type_abonnement,
    ad.id AS Id_adherent, 
    ad.prixAssurance,  
    p.Prenom, 
    p.Nom, 
    p.Tele, 
    p.DateNaissance,
    ad.Res_id,     
    r.id,
    p_res.Prenom AS responsable_nom,     
    par.id, 
    par.Id_Activite,    
    act.Id_Activite, 
    act.Libelle_Activite AS activite, 
    act.id,    
    e.id, 
    p_ent.Prenom AS entraineur_nom
    FROM abonnement ab
    JOIN type_abonnement tab ON  ab.Id_TAbonnement = tab.Id_TAbonnement
    JOIN adherent ad ON ab.Id_Adherent = ad.id
    JOIN personne p ON ad.id = p.id 
    JOIN responsable r ON ad.Res_id = r.id
    JOIN personne p_res ON p_res.id = r.id
    JOIN participer par ON par.id = ad.id
    JOIN activite act ON act.Id_Activite = par.Id_Activite
    JOIN entraineur e ON act.id = e.id
    JOIN personne p_ent ON e.id = p_ent.id ";

    $stm = $pdo->prepare($sql);
    $stm->execute();
    $adherents = $stm->fetchAll();
} catch (Exception $ex) {
    echo $ex->getMessage();
}