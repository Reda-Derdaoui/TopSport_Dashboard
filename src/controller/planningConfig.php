<?php

include __DIR__ . "../../Modules/Connecter.php";
include __DIR__ . "../../controller/activiteTable.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["add"])) {

        if (!empty($_POST["coach"]) && !empty($_POST["jour"]) && !empty($_POST["time"]) && !empty($_POST["activity"])) {

            $coach = $_POST["coach"];
            $jour = $_POST["jour"];
            $time = $_POST["time"];
            $timeFin = date('H:i a', strtotime($time . '+1 Hour'));
            $activity = $_POST["activity"];

            if (isset($activites)) {
                foreach ($activites as $row) {
                    if ($activity === $row["Libelle_Activite"]) {
                        $idActivity = $row["Id_Activite"];
                    }
                }
            }

            //check planing 
            try {
                $req = "SELECT 
                plan.jour, 
                plan.heureDebut, 
                plan.heureFin,
                plan.Entraineur,

                act.Id_Activite,
                act.Libelle_Activite, 

                planifier.Id_Activite,
                planifier.Id_Planning
                FROM planing plan JOIN planifier ON plan.Id_Planning  = planifier.Id_Planning
                JOIN activite act ON act.Id_Activite = planifier.Id_Activite 
                WHERE jour = ? AND heureDebut = ? AND heureFin = ? AND Entraineur = ? AND Libelle_Activite = ? ";

                $pst = $pdo->prepare($req);
                $params = [$jour, $time, $timeFin, $coach, $activity];
                $pst->execute($params);
                $result = $pst->fetch();
            } catch (Exception $ex) {
                $ex->getMessage();
            }

            if (is_array($result)) {
                $error =  "Activite déjà existant dans le système.";
            } else {
                try {
                    $sql = "INSERT INTO planing (Entraineur, jour, heureDebut, heureFin)
                     VALUES (?, ?, ?, ?)";
                    $stm = $pdo->prepare($sql);
                    $params = [$coach, $jour, $time, $timeFin];
                    $stm->execute($params);

                    $planningId = $pdo->lastInsertId();

                    $sql2 = "INSERT INTO planifier(Id_Activite, Id_Planning) VALUES (?, ?)";
                    $stm2 = $pdo->prepare($sql2);
                    $params = [$idActivity, $planningId];
                    $stm2->execute($params);
                } catch (Exception $ex) {
                    echo $ex->getMessage();
                }
                $validation = "Bien reçu.";
            }
        }
    } else if (isset($_POST["update"])) {

        if (!empty($_POST["coach"]) && !empty($_POST["jour"]) && !empty($_POST["time"]) && !empty($_POST["activity"]) && !empty($_POST["id"])) {

            $id = $_POST["id"];
            $coach = $_POST["coach"];
            $jour = $_POST["jour"];
            $time = $_POST["time"];
            $timeFin = date('H:i a', strtotime($time . '+1 Hour'));
            $activity = $_POST["activity"];

            if (isset($activites)) {
                foreach ($activites as $row) {
                    if ($activity === $row["Libelle_Activite"]) {
                        $idActivity = $row["Id_Activite"];
                    }
                }
            }

            $sql = "UPDATE planing SET Entraineur = ?, jour = ?, heureDebut = ?, heureFin = ? WHERE Id_Planning = ? ";
            $stm = $pdo->prepare($sql);
            $params = [$coach, $jour, $time, $timeFin, $id];
            $stm->execute($params);

            $sql2 = "UPDATE planifier SET Id_Activite = ? WHERE Id_Planning = ?";
            $stm2  = $pdo->prepare($sql2);
            $params = [$idActivity, $id];
            $stm2->execute($params);

            try {
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
        }
    }
}

try {
    $sql = " SELECT 
    p.Id_Planning,
    p.jour, 
    p.heureDebut, 
    p.heureFin, 
    p.Entraineur,
    a.Libelle_Activite AS activity_name
    FROM planing p
    JOIN  planifier ap ON p.Id_Planning = ap.Id_Planning
    JOIN  activite a ON ap.Id_Activite = a.Id_Activite 
    ORDER BY FIELD(p.jour, 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'), p.heureDebut ASC";
    $stm = $pdo->prepare($sql);
    $stm->execute();
    $plannings = $stm->fetchAll();
} catch (Exception $ex) {
    echo $ex->getMessage();
}
