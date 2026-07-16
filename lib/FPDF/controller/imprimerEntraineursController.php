<?php
session_start();

if (isset($_SESSION["user_id"])) {
    $idAdmin = $_SESSION["user_id"];
} else {
    echo "user id not found";
}

include __DIR__ . "../../../../src/Modules/Connecter.php";
include __DIR__ . "../../fpdf.php";
include __DIR__ . "../../autoPrint.php";



if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {

    $id = $_GET["id"];

    try {
        //entraineur
        $sql = "SELECT 
        personne.id, 
        personne.Nom, 
        personne.Prenom, 
        personne.Tele,
        personne.DateNaissance, 
        entraineur.specialite 
        FROM personne 
        JOIN entraineur ON personne.id =  entraineur.id WHERE personne.id = ? ";
        $stm = $pdo->prepare($sql);
        $params = [$id];
        $stm->execute($params);
        $entraineur = $stm->fetch();

        //admin
        $sql = "SELECT 
        personne.Nom, 
        personne.Prenom 
        FROM personne 
        JOIN admin ON personne.id = admin.id WHERE personne.id = ?";

        $stm = $pdo->prepare($sql);
        $params = [$idAdmin];
        $stm->execute($params);
        $admin = $stm->fetch();
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }

    $nom = $entraineur["Nom"];
    $prenom = $entraineur["Prenom"];
    $tele = $entraineur["Tele"];
    $dateN = (new DateTime($entraineur["DateNaissance"]))->format('d-m-Y');
    $specialite = $entraineur["specialite"];

    $adminNom = $admin["Nom"];
    $adminPrenom = $admin["Prenom"];

    $pdf = new fpdf('P', 'mm', 'A4');

    $pdf->AddPage();

    $pdf->Image('top-sport-noBack.png', 81, 5, 0, 30);

    $pdf->Ln(30);

    $pdf->SetFont('Arial', 'B', 26);

    $pdf->Cell(0, 10, 'Fiche Entraineur', 'B', 1, 'C');

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
    $pdf->Write($h, iconv('UTF-8', 'ISO-8859-1', $dateN . "  À  : Marrakech " . "\n"));
    $pdf->Ln(5);

    $pdf->SetFont('Arial', '', 12);
    $pdf->Write($h, $retrait . "Specialite : ");
    $pdf->SetFont('', 'BI');
    $pdf->Write($h,  $specialite .  "\n");

    $pdf->Ln(12);

    $pdf->SetFont('Arial', '', 14);
    $pdf->Cell(0, 5, "Informations du compte", '', 1);

    $pdf->Ln(8);

    $pdf->SetFont('Arial', '', 12);
    $pdf->write($h, $retrait . "Nom :  ");
    $pdf->SetFont('', 'BIU');
    $pdf->Write($h, $nom . " " . $prenom . "\n");
    $pdf->Ln(5);

    $pdf->SetFont('Arial', '', 12);
    $pdf->Write($h, iconv('UTF-8', 'ISO-8859-1', $retrait . "Rôle : "));
    $pdf->SetFont('', 'BI');
    $pdf->Write($h, 'Entraineur' . "\n");
    $pdf->Ln(5);

    $pdf->SetFont('Arial', '', 12);
    $pdf->Write($h, $retrait . "Ajout du Entraineur par l'Administrateur : ");
    $pdf->SetFont('', 'BIU');
    $pdf->Write($h, $adminNom . " " . $adminPrenom . "\n");

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
    $pdf->Cell(150, 5, '', 'LR', 1, 'C');
    $pdf->Cell(20);
    $pdf->Cell(150, 5, '', 'LR', 1, 'C');
    $pdf->Cell(20);
    $pdf->Cell(150, 5, "Cachet : ", 'LR', 1);
    $pdf->Cell(20);
    $pdf->Cell(150, 5, '', 'LR', 1, 'C');
    $pdf->Cell(20);
    $pdf->Cell(150, 5, '', 'LR', 1, 'C');
    $pdf->Cell(20);
    $pdf->Cell(150, 5, '', 'LR', 1, 'C');
    $pdf->Cell(20);
    $pdf->Cell(150, 5, '', 'LBR', 1, 'C');


    $pdf->Output();
}
