<?php
include_once("..\Header.php");
?>

<?php
$Mail = htmlspecialchars($_POST["Mail"], ENT_QUOTES);
if(CnxBDDReq("SELECT MAIL FROM utilisateurs WHERE MAIL = '$Mail'") == true){ // Mail déjà utilisé ?
    echo "Cet email est déjà utilisé !<br>";
}
else{
    ?>
    <script>
        $(".error").empty();
    </script>
    <?php
}
?>