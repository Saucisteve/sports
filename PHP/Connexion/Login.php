<?php
include 'bdd.php';
?>
<title>EARL OCCHIPINTI</title>
<?php
$message = "";
if(isset($_POST['login']))
{
  $login = $_POST['login'];
  $pwd = hash('sha512', $_POST['pwd'].$gds.'IN');
  $reqConnexion = ("SELECT * FROM user WHERE usr_login = '$login' AND usr_password = '$pwd'"); //Requête
  $CmdConnexion = $CnxBDD->query($reqConnexion); //Execution de la requête
  $user = $CmdConnexion->fetch(); //Récupération du résultat de la requête (RecordSet)
  if($user)
  {
    if($user['usr_mail_valide'] == 1)
    {
      $reqIncrementationNbrCon = ("UPDATE user SET usr_last_co = CURDATE(), usr_nbr_co = usr_nbr_co + 1 WHERE usr_id = {$user['usr_id']}");
      $CmdIncrementationNbrCon = $CnxBDD->query($reqIncrementationNbrCon);
      $expire = isset($_POST['rememberme'])?time()+3600*24*365:0;
      setcookie('user_id', $user['usr_id']);
      setcookie('user_id_hash',hash('sha512', $user['usr_id'].$gds.'CO'));
      header('location:index.php');
    }
    else
    {
      $message = "Vous devez confirmez votre mail pour vous connecter !";
    }
  }
  else
  {
    $message = "Login ou mot de passe incorrect !";
  }
}
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="css.css" />
<div id="FormCenter">
  <div id="Form">
    <div>
      <div><h2>Connexion</h2><h4>Saisissez vos identifiants afin de vous connecter.</h4></div>
      <div id="MessagesErreur"><?php echo $message; ?></div>
      <div id="Barre"></div>
    </div>
    <div>
      <form name=FormInscription method='POST'>
        <div style="margin-bottom:5px;">Login</div>
        <div class="FormDivInput"><input id="Login" autofocus class="FormInputText" name=login <?php echo((isset($_POST['login'])?"value='".htmlspecialchars($_POST['login'], ENT_QUOTES)."'":'')) ?> placeholder="Login" required></div>
        <div style="margin-bottom:5px;">Mot de passe</div>
        <div class="FormDivInput"><input id="Password" class="FormInputText" name=pwd placeholder="Mot de passe" type="password" required></div>
        <div style="margin:15px;"><input type="checkbox" name="rememberme" id="rm"><label for=rm style="color:white;">Se souvenir de moi.</label></div>
        <div class="InputConn"><input type="submit" class="InputConnButton" value="Connexion"></div>
        <div style="margin-top:20px;"><a href="MDPOublie.php" style="color:white;">Mot de passe oublié ?</a></div>
        <div id="GDeja">Pas encore inscrit ? <a href=Inscription.php style="font-weight:bold;">Je m'inscris !</a></div>
      </form>
    </div>
  </div>
</div>
<?php
include 'Footer.php';
?>