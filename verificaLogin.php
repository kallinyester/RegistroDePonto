<?php
    session_start();
    if(!isset($_SESSION['login'])) { 
        header('Location: ../CH2/painel/login.php'); 
    } 
?>
