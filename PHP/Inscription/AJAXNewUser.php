<?php
include_once("..\Header.php");
?>

<?php
$Mail = htmlspecialchars($_POST["Mail"], ENT_QUOTES);
if(CnxBDDReq("SELECT MAIL FROM utilisateurs WHERE MAIL = '$Mail'") == true){ // Mail déjà utilisé ?
    echo "Cet email est déjà utilisé !<br>";
}
else{
    $Password = hash("sha512", $_POST["Password"].$gds."GENERIC_PASSWORD");
    $Nom = addslashes($_POST["Lastname"]);
    $Prenom = addslashes($_POST["Firstname"]);
    $req = CnxBDDReq("INSERT INTO 
    utilisateurs(ID_VILLE, ID_TYPE, NOM, PRENOM, DATE_NAISSANCE, MAIL, Password, TEL, GENRE, Date_Inscription, Date_Connexion) 
    VALUES(1, 1, '$Nom', '$Prenom', '{$_POST["Birthday"]}', '$Mail', '$Password', '{$_POST["Phone"]}', 1, NOW(), NOW())"); // INSERT INTO d'un nouveau compte dans la BDD

    //Envoi du mail pour confirmer l'inscription
    $IDUser = CnxBDDReqFirst("SELECT ID_UTILISATEUR FROM utilisateurs WHERE MAIL = '$Mail'");
    $IDUserHash = hash("sha512", $IDUser["ID_UTILISATEUR"].$gds."ValidMail");
    $Message = "Bienvenue ".strtoupper($Nom)." ".$Prenom." !\n
    Vous venez de vous inscrire sur ".$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST']."\n
    Merci de confimer votre inscription en cliquant sur le lien ci-dessous:\n
    ".$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST']."/PHP/Inscription/ValidMail.php?UserID=".$IDUser["ID_UTILISATEUR"]."&HashID=".$IDUserHash."\n\n\n
    Ceci est un message automatique, merci de ne pas y répondre";
    if(mail($Mail, "Confirmez votre inscription sur ".$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'], $Message)){
        ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Inscription',
                text: 'Merci de confirmer votre inscription via l\'email qui vient de vous être envoyé',
            })
        </script>
        <?php
    }
}
?>