<?php

// Configuration de la base de données
    $host = "mysql-melain.alwaysdata.net";
    $user = "melain_dev";
    $password = "Dev123.";
    $database = "melain_restocampus";

try {
    $conn = new PDO("mysql:host=$host;dbname=$database", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   # echo "Connected successfully to database";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>