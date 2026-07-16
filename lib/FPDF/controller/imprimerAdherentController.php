<?php

session_start();

include __DIR__ . "../../../../src/Modules/Connecter.php";
include __DIR__ . "../../fpdf.php";


if (isset($_SESSION["user_id"])) {
    $idRes = $_SESSION["user_id"];
} else {
    echo "user id not found";
}


if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["Id_adherent"])) {


    $idAdh = $_GET["Id_adherent"];

    try {

        $req = "SELECT 
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
                  
                par.id,
                par.Id_Activite ,
                    
                act.Id_Activite, 
                act.Libelle_Activite AS activite,
                act.id,
                
                e.id,  
                p_ent.Prenom AS entraineur_Prenom,
                p_ent.Nom AS entraineur_Nom,
                 
                pla.Id_Activite,
                pla.Id_Planning , 

                plani.Id_Planning,
                plani.jour,
                plani.heureDebut,
                plani.heureFin

    FROM abonnement ab JOIN type_abonnement tab ON  ab.Id_TAbonnement = tab.Id_TAbonnement
    JOIN adherent ad ON ab.Id_Adherent = ad.id
    JOIN personne p ON ad.id = p.id 
    JOIN participer par ON par.id = ad.id
    JOIN activite act ON act.Id_Activite = par.Id_Activite
    JOIN entraineur e ON act.id = e.id
    JOIN personne p_ent ON e.id = p_ent.id 
    JOIN planifier pla ON act.Id_Activite =  pla.Id_Activite
    JOIN planing plani ON pla.Id_Activite = plani.Id_Planning WHERE Id_adherent = ?

    ORDER BY FIELD(plani.jour, 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'), plani.heureDebut ASC ";

        $pst = $pdo->prepare($req);
        $params = [$idAdh];
        $pst->execute($params);
        $adherent = $pst->fetch();

        //get responsable data
        $sql = "SELECT personne.Nom, personne.Prenom 
                FROM personne 
                JOIN responsable ON personne.id = responsable.id 
                WHERE personne.id = ?";


        //get responsable data 
        $stm = $pdo->prepare($sql);
        $params = [$idRes];
        $stm->execute($params);
        $responsable = $stm->fetch();

        //information personnelles 
        $prenom = $adherent["Prenom"];
        $nom = $adherent["Nom"];
        $tele = $adherent["Tele"];
        $dateNaissance = (new DateTime($adherent["DateNaissance"]))->format('d/m/Y') ;

        //information abonnement
        $activite = $adherent["activite"];
        $typeAbonnement = $adherent["type_abonnement"];
        $dateDebut = (new DateTime($adherent["DateDebut"]))->format('d-m-Y') ;
        $dateFin = (new DateTime($adherent["DateFin"]))->format('d-m-Y');
        $prix = $adherent["Prix"];

        //information sportif 
        $entraineurPrenom = $adherent["entraineur_Prenom"];
        $entraineurNom = $adherent["entraineur_Nom"];
        $jour = $adherent["jour"];
        $horaireDebut = date('H:i', strtotime($adherent['heureDebut']));
        $horaireFin =  date('H:i', strtotime($adherent['heureFin']));


        //responsable data
        $responsablePrenom = $responsable["Prenom"];
        $responsableNom = $responsable["Nom"];

        $pdf = new fpdf('P', 'mm', 'A4');

        $pdf->AddPage();

        $pdf->Image('top-sport-noBack.png', 81, 5, 0, 30);

        $pdf->Ln(30);

        $pdf->SetFont('Arial', 'B', 26);

        $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1', 'Fiche adhérent'), 'B', 1, 'C');

        $pdf->Ln(10);

        $pdf->SetFont('Arial', '', 14);
        $h = 7;
        $retrait = '      ';

        $pdf->Cell(0, 5, "Informations personnelles", '', 1);
        $pdf->Ln(8);

        $pdf->SetFont('Arial', '', 12);
        $pdf->Write($h, $retrait . 'Nom :  ');
        $pdf->SetFont('', 'BI');
        $pdf->Write($h, $nom . "\n");
        $pdf->Ln(5);

        $pdf->SetFont('Arial', '', 12);
        $pdf->Write($h, $retrait . "Prenom : ");
        $pdf->SetFont('', 'BI');
        $pdf->Write($h, $prenom . "\n");
        $pdf->Ln(5);

        $pdf->SetFont('Arial', '', 12);
        $pdf->Write($h, iconv('UTF-8', 'ISO-8859-1', $retrait . "Téléphone : "));
        $pdf->SetFont('', 'BI');
        $pdf->Write($h, $tele . "\n");
        $pdf->Ln(5);

        $pdf->SetFont('Arial', '', 12);
        $pdf->Write($h, $retrait . "Date de naissance : ");
        $pdf->SetFont('', 'BI');
        $pdf->Write($h, iconv('UTF-8', 'ISO-8859-1', $dateNaissance . "  À  : Marrakech " . "\n"));
        $pdf->Ln(5);

        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell(0, 5, "Informations abonnement", '', 1);

        $pdf->Ln(8);

        $pdf->SetFont('Arial', '', 12);
        $pdf->write($h, iconv('UTF-8', 'ISO-8859-1', $retrait . "Activité :  "));
        $pdf->SetFont('', 'BIU');
        $pdf->Write($h, $activite . "\n");
        $pdf->Ln(5);

        $pdf->SetFont('Arial', '', 12);
        $pdf->Write($h,  $retrait . "Type abonnement : ");
        $pdf->SetFont('', 'BI');
        $pdf->Write($h, $typeAbonnement  . "\n");
        $pdf->Ln(5);

        $pdf->SetFont('Arial', '', 12);
        $pdf->Write($h,  iconv('UTF-8', 'ISO-8859-1', $retrait . "Date début: "));
        $pdf->SetFont('', 'BI');
        $pdf->Write($h, $dateDebut  . "\n");
        $pdf->Ln(5);

        $pdf->SetFont('Arial', '', 12);
        $pdf->Write($h,  $retrait . "Date fin: ");
        $pdf->SetFont('', 'BI');
        $pdf->Write($h, $dateFin  . "\n");
        $pdf->Ln(5);


        $pdf->SetFont('Arial', '', 12);
        $pdf->Write($h,  $retrait . "Prix: ");
        $pdf->SetFont('', 'BI');
        $pdf->Write($h, $prix  . ' Dh' . "\n");
        $pdf->Ln(5);

        $pdf->SetFont('Arial', '', 12);
        $pdf->Write($h, iconv('UTF-8', 'ISO-8859-1', $retrait . "Entraîneur: "));
        $pdf->SetFont('', 'BI');
        $pdf->Write($h, $entraineurPrenom  . " " . $entraineurNom . "\n");
        $pdf->Ln(5);

        $pdf->SetFont('Arial', '', 12);
        $pdf->Write($h, iconv('UTF-8', 'ISO-8859-1', $retrait . "Ajout du Adherent par l'Administrateur : "));
        $pdf->SetFont('', 'BIU');
        $pdf->Write($h, $responsableNom . " " . $responsablePrenom . "\n");

        $pdf->Ln(15);

        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell(20);
        $pdf->Cell(150, 8, "VALIDATION ", 1, 1, 'C');
        $pdf->Cell(20);
        $pdf->Cell(150, 5, '', 'LR', 1, 'C');
        $pdf->Cell(20);
        $pdf->Cell(150, 5, "Signature : ", 'LR', 1);
        $pdf->Cell(20);
        $pdf->Cell(150, 5, '', 'LR', 1, 'C');
        $pdf->Cell(20);
        $pdf->Cell(150, 5, '', 'LR', 1, 'C');
        $pdf->Cell(20);
        $pdf->Cell(150, 5, '', 'LR', 1, 'C');
        $pdf->Cell(20);
        $pdf->Cell(150, 5, "Cachet : ", 'LR', 1);
        $pdf->Cell(20);
        $pdf->Cell(150, 5, '', 'LR', 1, 'C');
        $pdf->Cell(20);
        $pdf->Cell(150, 5, '', 'LBR', 1, 'C');


        $pdf->Output();
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }
}
