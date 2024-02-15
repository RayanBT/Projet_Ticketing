<?php
session_start();
function checkLoggedIn() {
    if (!isset($_SESSION['login'])) {
        header("Location: ../PHP/Deconnexion.php");
        exit();
    }else{
        return true;
    }
}
?>