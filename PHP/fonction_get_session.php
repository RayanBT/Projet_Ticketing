<?php
//Script regroupant les fonctions pour avoir les infos sur la SESSION
//session_start();
function getRole(){
    return $_SESSION['user_role'];
}

function getLogin(){
    return $_SESSION['login'];
}

function getCaptcha(){
    return $_SESSION['captcha'];
}

function getMessage(){
    return $_SESSION['message'];
}

function getCouleur(){
    return $_SESSION['couleur'];
}

function getAll(){
    foreach ($_SESSION as $key => $value) {
        echo $key . ': ' . $value . '<br>';
    }
}