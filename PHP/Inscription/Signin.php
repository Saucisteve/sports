<?php
include_once("..\Header.php");
?>

<form method="POST" onsubmit="return NewUser()"> <!-- Formulaire d'inscription, lance un appel AJAX lors de son envoi -->
    Mail: <input type="text" id="NewUserMail" required autofocus><br>
    Mot de passe: <input type="password" id="NewUserPassword" required><br>
    Confirmation Mot de passe: <input type="password" id="NewUserPasswordConfirm" required><br>
    Nom: <input type="text" id="NewUserLastname"><br>
    Prenom: <input type="text" id="NewUserFirstname"><br>
    Date de naissance: <input type="date" id="NewUserBirthday"><br>
    Téléphone: <input type="text" id="NewUserPhone"><br>
    <button>S'inscrire</button>
</form>
<div class="error"></div>
<div class="debug"></div>

<script>

document.querySelector("#NewUserMail").addEventListener("input", (Mail) => { // Fonction de vérification du mail afin de savoir si il est déjà utilisé
    $.ajax({
            url: "AJAXCheckUsedMail.php",
            type: "POST",
            data: {
                Mail: $("#NewUserMail").val(),
            },
            success: function(result){
                $(".error").append(result);
            }
        });
})

function NewUser(){ //Fonction d'inscription d'un nouveau compte en AJAX
    let error = false;
    if($("#NewUserPassword").val() != $("#NewUserPasswordConfirm").val()){ // Vérification de la confirmation du mot de passe
        $(".error").append("Mot de passe mal confirmé !<br>");
        error = true;
    }
    if($("#NewUserPassword").val().length <= 3){ // Vérification de la taille du mot de passe
        $(".error").append("Votre mot de passe doit faire plus de 3 caractères !<br>");
        error = true;
    }

    let ValidMail = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if(!ValidMail.test(String($("#NewUserMail").val()).toLowerCase())){ // Vérification de la validité du mail
        $(".error").append("EMail invalide !<br>");
        error = true;
    }

    let ValidPhone = /^0[0-9]{9}$/;
    if(!ValidPhone.test(String($("#NewUserPhone").val()))){ // Vérification du numéro de téléphone
        $(".error").append("Numéro de téléphone invalide !<br>");
        error = true;
    }

    if(error === false){ // On continue si aucune erreur
        $.ajax({
            url: "AJAXNewUser.php",
            type: "POST",
            data: {
                Mail: $("#NewUserMail").val(),
                Password: $("#NewUserPassword").val(),
                Lastname: $("#NewUserLastname").val(),
                Firstname: $("#NewUserFirstname").val(),
                Birthday: $("#NewUserBirthday").val(),
                Phone: $("#NewUserPhone").val(),
            },
            success: function(result){
                $(".debug").empty();
                $(".debug").append(result);
            }
        });
    }
    return false;
}

</script>

<title>EARL OCCHIPINTI</title>
<?php
if(is_authentified())
{
  header('location:index.php');
}
if(isset($_GET['valide_mail']))
{
  if($_GET['valide_mail_hash'] != hash('sha512',$_GET['valide_mail'].$gds.'CM'))
  {
    die("Tentative de piratage de mail !");
  }
  $reqMailValide = ("UPDATE user SET usr_mail_valide = 1 WHERE usr_id = {$_GET['valide_mail']}");
  $CmdMailValide = $CnxBDD->query($reqMailValide);
  header('location:Inscription.php?message=mailconfirm');
  exit;
}

$message = "";
if(isset($_POST['login']))
{
  $reqInscription = $CnxBDD->prepare("SELECT * FROM user WHERE usr_login = ?"); //Préparation de la requête
  $reqInscription->execute(array($_POST['login'])); //Exécution de la requête avec les paramètres
  $RsInscription = $reqInscription->fetch(); //Récupération du résultat de la requête (RecordSet)
  if($_POST['login'] == $RsInscription['usr_login'])
  {
    $message .= "Ce login est déjà utilisé !<br><br>";
  }
  if(empty($_POST['login']))
  {
    $message .= "Vous devez saisir un login !<br><br>";
  }
  if($_POST['pwd1'] != $_POST['pwd2'])
  {
    $message .= "Confirmation du mot de passe incorrect !<br><br>";
  }
  if(empty($_POST['mail']))
  {
    $message .= "Vous devez saisir une adresse Mail !<br><br>";
  }
  $reqInscription = $CnxBDD->prepare("SELECT * FROM user WHERE usr_mail = ?"); //Préparation de la requête
  $reqInscription->execute(array($_POST['mail'])); //Exécution de la requête avec les paramètres
  $RsInscription = $reqInscription->fetch(); //Récupération du résultat de la requête (RecordSet)
  if($_POST['mail'] == $RsInscription['usr_mail'])
  {
    $message .= "Cette adresse mail est déjà utilisé !<br><br>";
  }
  elseif(empty($message))
  {
    $pwdHash = hash('sha512', $_POST['pwd1'].$gds.'IN');
    $req = $CnxBDD->prepare("INSERT INTO user (usr_login, usr_password, usr_mail, usr_nom, usr_prenom, usr_ip_inscription, usr_date_inscription, usr_civilite) VALUES (?, ?, ?, ?, ?, ?, NOW(), 0)");
    $req->execute(array($_POST['login'], $pwdHash, $_POST['mail'], $_POST['nom'], $_POST['prenom'], $_SERVER['REMOTE_ADDR']));
    $id = $CnxBDD->lastInsertId();
    $hash = hash('sha512', $id.$gds.'CM');
    $url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
    $message = "Aller dans votre boite mail pour confirmer votre inscription.";
    $mailMessage = "Bonjour,\nVous venez de vous inscrire sur EARL OCCHIPINTI.\nMerci de cliquer sur le lien ci-dessous pour confirmer votre inscription.\n$url?valide_mail=$id&valide_mail_hash=$hash\n\n\nCeci est un message automatique, merci de ne pas y répondre.";
    mail($_POST['mail'], "Inscription sur {$_SERVER['HTTP_HOST']}", $mailMessage);
  }
}
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="css.css" />

<div id="FormCenter">
  <div id="Form">
    <div>
      <div><h2>Inscription</h2><h4>Saisissez les informations demandées afin de vous inscrire.</h4></div>
      <div id="MessagesErreur"><?php echo $message; ?></div>
      <div id="Barre"></div>
    </div>
    <div>
      <form name=FormInscription method='POST'>
        <div id="resultLogin"></div>
        <div class="FormDivInput"><input onkeyup='valideLogin(this)' id="Login" class="FormInputText" autofocus name=login <?php echo((isset($_POST['login'])?"value='".htmlspecialchars($_POST['login'], ENT_QUOTES)."'":'')) ?> placeholder="Login" required></div>
        <div class="FormDivInput"><input id="Nom" class="FormInputText" name=nom <?php echo((isset($_POST['nom'])?"value='".htmlspecialchars($_POST['nom'], ENT_QUOTES)."'":'')) ?> placeholder="Nom" required></div>
        <div class="FormDivInput"><input id="Prenom" class="FormInputText" name=prenom <?php echo((isset($_POST['prenom'])?"value='".htmlspecialchars($_POST['prenom'], ENT_QUOTES)."'":'')) ?> placeholder="Prénom" required></div>
        <div class="FormDivInput"><input onkeyup='return validePassword(this)' id="Password" class="FormInputText" name=pwd1 placeholder="Mot de passe" type="password" required></div>
        <div class="FormDivInput"><input onkeyup='return validePasswordConfirm(this)' id="PasswordConfirm" class="FormInputText" name=pwd2 placeholder="Confirmation du mot de passe" type="password" required></div>
        <div class="FormDivInput"><input onkeyup='return valideMail(this)' id="Mail" class="FormInputText" name=mail <?php echo((isset($_POST['mail'])?"value='".htmlspecialchars($_POST['mail'], ENT_QUOTES)."'":'')) ?> placeholder="EMail" required></div>
        <div class="FormDivInput"><input type="submit" class="InputInscripButton" value="Inscription"></div>
        <div id="GDeja">Vous possèdez déjà un compte ? <a href=Connexion.php style="font-weight:bold;"> Je me connecte !</a></div><br>
        <!-- <div id="GDeja">Vous ne recevez pas de mail ? <a href=mailto: style="font-weight:bold;"> Renvoyer le mail.</a></div> -->
      </form>
    </div>
  </div>
</div>

<script>
function valideLogin()
{
    ///////////////////////////////
    //////////// LOGIN ////////////
    ///////////////////////////////
    login_pattern = /^[A-Za-zéèùôûîçàïëöêæœ0-9]{4,} ?/ ;
    login_a_tester = document.getElementById("Login").value;
    if (login_pattern.test(login_a_tester))
        $('#Login').css('border-color', '#1fb426');       
    else
        $('#Login').css('border-color', 'red');
}
function validePassword()
{
    ///////////////////////////////
    ////////// PASSWORD ///////////
    ///////////////////////////////
    pwd1_pattern = /.{6,}/ ;
    pwd1_a_tester = document.getElementById("Password").value;
    if (pwd1_pattern.test(pwd1_a_tester))
        $('#Password').css('border-color', '#1fb426');
    else
        $('#Password').css('border-color', 'red');
}
function validePasswordConfirm()
{
    ///////////////////////////////
    ////// PASSWORDCONFIRM ////////
    ///////////////////////////////
    if (document.getElementById("PasswordConfirm").value == document.getElementById("Password").value)
        $('#PasswordConfirm').css('border-color', '#1fb426');
    else
        $('#PasswordConfirm').css('border-color', 'red');
}
function valideMail()
{
    ///////////////////////////////
    //////////// MAIL /////////////
    ///////////////////////////////
    mail_pattern = /^[\w\.-]*@[a-zA-Z0-9][a-zA-Z0-9-]*[a-zA-Z0-9]\.[a-zA-Z]*$/ ;
    mail_a_tester = document.getElementById("Mail").value;
    if (mail_pattern.test(mail_a_tester))
        $('#Mail').css('border-color', '#1fb426');
    else
        $('#Mail').css('border-color', 'red');
}
</script>