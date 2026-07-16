<?php
session_start();

include __DIR__ . "../../Modules/Connecter.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!empty($_POST["userName"]) && !empty($_POST["password"])) {

        $userName = $_POST["userName"];
        $password = $_POST["password"];

        try {
            $sql  = "SELECT 
            id, 
            UserName_Adm, 
            Password_Adm 
            FROM admin WHERE UserName_Adm  = ?";
            $stm = $pdo->prepare($sql);
            $params = [$userName];
            $stm->execute($params);
            $user = $stm->fetch();

            $userPassword = $user["Passowrd_Adm"];

            echo $userPassword;

            $sql2 = "SELECT 
            id, 
            UserName, 
            Password
            FROM responsable WHERE UserName  = ?";
            $stm2 = $pdo->prepare($sql2);
            $params2 = [$userName];
            $stm2->execute($params2);
            $user2 = $stm2->fetch();


            if ($userName === $user["UserName_Adm"]  && $password === $user["Password_Adm"]) {
                $_SESSION["logged_in"] = true;
                $_SESSION["userName"] = $user["UserName_Adm"];
                $_SESSION["user_id"] = $user["id"];

                header("Location: ../view/Admin/dashboard.php");
                exit();
            } else if ($userName === $user2["UserName"] && $password === $user2["Password"]) {
                $_SESSION["logged_in"] = true;
                $_SESSION["userName"] = $user2["UserName"];
                $_SESSION["user_id"] = $user2["id"];

                header("Location: ../view/Responsable/dashboard.php");
                exit();
            } else {
                header("Location: ../view/login.php");
                exit();
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    } else {
        header("Location: ../view/login.php");
        exit();
    }
}
