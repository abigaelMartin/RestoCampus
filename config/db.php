<?php
class Db {
    private static $host = "127.0.0.1";
    private static $port = "8889";    // port MySQL de MAMP
    private static $dbname = "RestoCampus";
    private static $user = "root";    // par défaut MAMP = root
    private static $pass = "root";    // par défaut MAMP = root (change si besoin)
    private static $pdo = null;

    public static function getDb() {
        if (self::$pdo === null) {
            $dsn = "mysql:host=" . self::$host . ";port=" . self::$port . ";dbname=" . self::$dbname . ";charset=utf8";
            try {
                self::$pdo = new PDO($dsn, self::$user, self::$pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]);
            } catch (PDOException $e) {
                error_log("Erreur de connexion BDD : " . $e->getMessage());
                die("Impossible de se connecter à la base de données.");
            }
        }
        return self::$pdo;
    }
}
