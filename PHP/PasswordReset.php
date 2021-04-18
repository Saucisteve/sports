<link rel="stylesheet" type="text/css" href="css.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<meta charset="UTF-8">
<title>EARL OCCHIPINTI</title>
<?php
include 'bdd.php';
$message = "";
if(isset($_POST['usr_id']))
{
    if($_GET['usr_id_hash'] != hash('sha512',$_GET['usr_id'].$gds.'CM'))
    {
        //$message .= "Piratage du cookie<br><br>";
        header('location:index.php');
    }
    elseif($_GET['date_hash']!=hash('sha512', $_GET['date'].$gds.'DMO'))
    {
        //$message .= "Piratage de la date<br><br>";
        header('location:index.php');
    }
    elseif(strlen($_POST['pwd1']) < 6)
    {
        $message .= "Le mot de passe doit faire au minimum 6 caractères.";
    }
    elseif($_POST['pwd1'] != $_POST['pwd2'])
    {
        $message .= "Le mot de passe est mal confirmé<br><br>";
    }
    else
    {
        $pwd=hash('sha512', $_POST['pwd1'].$gds.'IN');
        $MDPOublieModif = ("UPDATE user SET usr_password = '$pwd' WHERE usr_id = {$_GET['usr_id']}");
        $CmdMDPOublieModifMDP = $CnxBDD->query($MDPOublieModif); //Execution de la requête
        $message = "Mot de passe modifié.<br>Me <a href=Connexion.php>connecter</a>.";
    }
}
if(isset($_GET['usr_id']))
{
    if($_GET['usr_id_hash'] != hash('sha512', $_GET['usr_id'].$gds.'DMO'))
    { 
        if(time()>$_GET['date']+600)
        {
            echo "<div id=MessagesErreur>Ce lien est obsolète</div>";
        }
        else
        { ?>
            <div id="FormCenter">
                <div id="Form">
                    <div>
                        <div><h2>Réinitialisation du mot de passe:</h2><h4>Saisissez votre nouveau mot de passe.</h4></div>
                            <div id="MessagesErreur"><?php echo $message; ?></div>
                            <div id="Barre"></div>
                        </div>
                        <div>
                        <form name=FormInscription method='POST'>
                            <input class="FormInputText" type=hidden name=usr_id value={$_GET['usr_id']}>
                            <input class="FormInputText" type=hidden name=usr_id_hash value={$_GET['usr_id_hash']}>
                            <input class="FormInputText" type=hidden name=date value={$_GET['date']}>
                            <input class="FormInputText" type=hidden name=date_hash value={$_GET['date_hash']}>
                            Nouveau mot de passe: <div class=InputInscrip><input onkeyup='return validePassword(this)' id=pwd1 name=pwd1 class="FormInputText" type=password autofocus><br></div>
                            Confirmez le mot de passe: <div class=InputInscrip><input onkeyup='return validePasswordConfirm(this)' id=pwd2 name=pwd2 class="FormInputText" type=password><br></div>
                            <div class="InputInscrip"><input type=submit></div>
                        </form>
                    </div>
                </div>
            </div>
       <?php }
     }
} ?>
<script>
function validePassword()
{
    ///////////////////////////////
    ////////// PASSWORD ///////////
    ///////////////////////////////
    pwd1_pattern = /.{6,}/ ;
    pwd1_a_tester = document.getElementById("pwd1").value;
    if (pwd1_pattern.test(pwd1_a_tester))
        $('#pwd1').css('border-color', '#1fb426');
    else
        $('#pwd1').css('border-color', 'red');
}
function validePasswordConfirm()
{
    ///////////////////////////////
    ////// PASSWORDCONFIRM ////////
    ///////////////////////////////
    if (document.getElementById("pwd2").value == document.getElementById("pwd1").value)
        $('#pwd2').css('border-color', '#1fb426');
    else
        $('#pwd2').css('border-color', 'red');
}
</script>