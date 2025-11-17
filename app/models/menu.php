<?php
require_once(__DIR__ . '/../../config/bdd.php');

class Menu {
    public static function AjouterUnMenu($libelle, $Desc) {
        global $conn;
        $stmt = $conn->prepare("INSERT INTO Article (libelleArt, Description) 
                                VALUES (:lebelle, :Description)");
        $stmt->bindParam(':lebelle', $libelle, PDO::PARAM_INT);
        $stmt->bindParam(':Descriptions', $Desc, PDO::PARAM_INT);
        $stmt->execute();
    
        if ($stmt){
            return true;
        }else{
            return false;
        }

    }
}