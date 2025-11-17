<?php
require_once(__DIR__ . '/../../config/bdd.php');

class Article {
    public static function AjouterUnArticle($libelle, $Desc) {
        global $conn;

        $stmt = $conn->prepare("INSERT INTO Article (libelleArt, Description) 
                                VALUES (:libelle, :Description)");

        $stmt->bindParam(':libelle', $libelle, PDO::PARAM_STR);
        $stmt->bindParam(':Description', $Desc, PDO::PARAM_STR);

        $stmt->execute();
        if($stmt){
            $result=true;
        }else{
            $result=false;
        }
        return $result;
    }
}