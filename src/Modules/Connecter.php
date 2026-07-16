<?php

// DB connection 

$host = "localhost";
$port = 3306;
$dbName = "topsport";
$userName = "root";
$password = "";
$dsn = "mysql:host={$host};port={$port};dbname={$dbName};charset=utf8";

try {
    $pdo = new PDO($dsn, $userName, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $ex) {
    echo "DABASE NOT OK {$ex->getMessage()}";
}
