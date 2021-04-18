<?php
function CnxBDD(){ // Fonction de connexion à appeler dans les pages (au lieu d'initialiser l'entiereté de la connexion à chaque fois)
    try{
        $CnxBDD = new PDO('mysql:host=10.53.130.250;dbname=projet_sports_db;charset=utf8',"projet_sports", "sdgv58qs04bv583q40df64bqfd75b075qb", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }
    catch(Exception $error){
        $CnxBDD = null;
        die($error->getMessage());
    }
    return $CnxBDD;
}

function CnxBDDReq($Req){ // Même chose qu'au dessus, mais execute une requête en plus (SELECT, INSERT, ...)
    try{
        $CnxBDD = CnxBDD();
    }
    catch(Exception $error){
        $CnxBDD = null;
        die('Erreur : ' . $error->getMessage());
    }
    if($CnxBDD != null){
        try{
            $TypeReq = explode(" ", $Req);
            if($TypeReq[0] == "SELECT"){
                $sql = $CnxBDD->query($Req);
                return $sql->fetchAll();
            }
            else{
                $sql = $CnxBDD->prepare($Req);
                return $sql->execute();
            }
        }
        catch(Exception $error){
            $sql = null;
            die($error->getMessage());
        }
    }
}

function CnxBDDReqFirst($Req){ // Même chose qu'au dessus, mais retourne qu'un seul enregistrement (SELECT, INSERT, ...)
    try{
        $CnxBDD = CnxBDD();
    }
    catch(Exception $error){
        $CnxBDD = null;
        die('Erreur : ' . $error->getMessage());
    }
    if($CnxBDD != null){
        try{
            $TypeReq = explode(" ", $Req);
            if($TypeReq[0] == "SELECT"){
                $sql = $CnxBDD->query($Req);
                return $sql->fetch();
            }
            else{
                $sql = $CnxBDD->prepare($Req);
                return $sql->execute();
            }
        }
        catch(Exception $error){
            $sql = null;
            die($error->getMessage());
        }
    }
}
?>