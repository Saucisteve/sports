<link rel="stylesheet" type="text/css" href="css.css" />
<meta charset="UTF-8">
<title>EARL OCCHIPINTI</title>
<?php
include 'bdd.php';
$message = "";
if(isset($_POST['login_mail']))
{
    $lm = $_POST['login_mail'];
    $reqMDPOublie = ("SELECT usr_id, usr_mail, usr_mail_valide FROM user WHERE usr_mail = '$lm'"); //Requête
    $CmdMDPOublie = $CnxBDD->query($reqMDPOublie); //Execution de la requête
    $RsMDPOublie = $CmdMDPOublie->fetch(); //Récupération du résultat de la requête (RecordSet)
    if($RsMDPOublie)
    {
        if($RsMDPOublie['usr_mail_valide'] == 0)
        {
            $message = "Vous devez valider votre mail pour modifier votre mot de passe !";
        }
        else
        {
            $id = $RsMDPOublie['usr_id'];
            $mail = $RsMDPOublie['usr_mail'];
            $hash = hash('sha512', $id.$gds.'CM');
            $date = time();
            $date_hash = hash('sha512', $date.$gds.'DMO');
            $url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'MDPReset.php';
            $messageMail = "Bonjour, \nMerci de cliquer sur le lien ci-dessous pour réinitialiser votre mot de passe.\n$url?usr_id=$id&usr_id_hash=$hash&date=$date&date_hash=$date_hash \n\nSi ce n'est pas vous qui avez demandé une réinitialisation du mot de passe, supprimez le message et ne modifiez rien, la sécurité de votre compte n'est pas affectée.\n\n\nCeci est un message automatique, merci de ne pas y répondre.";
            $message = "Un mail vient de vous être envoyé.";
            mail($_POST['login_mail'], "Demande de réinitialisation du mot de passe sur {$_SERVER['HTTP_HOST']}.", $messageMail);
        }
    }
    else
    {
        $message = "Aucun mail correspondant";
    }
} ?>
    <div id="FormCenter">
        <div id="Form">
            <div>
                <div><h2>Mot de passe oublié:</h2><h4>Saisissez votre adresse mail.</h4></div>
                <div id="MessagesErreur"><?php echo $message; ?></div>
                <div id="Barre"></div>
                </div>
                <div>
                <form name=FormInscription method='POST'>
                    <div class="InputInscrip"><input name="login_mail" class="FormInputText" placeholder="Saisissez votre mail" required autofocus></div>
                    <div class="InputInscrip"><input type=submit></div>
                    <div id="GDeja">Retourner à la <a href=Connexion.php style="font-weight:bold;">connexion</a>.</div>
                </form>
            </div>
        </div>
    </div>
<?php
include 'Footer.php';
?>