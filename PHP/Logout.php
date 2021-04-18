<?php
include 'Header.php';
setcookie('user_id',false);
setcookie('user_id_hash',false);
header('location:index.php');
include 'Footer.php';
?>