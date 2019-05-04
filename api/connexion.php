<?php
include_once ("../env/env.php");

if ($_ENV['env_name'] == "dev") {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}


try
{
    $bdd = new PDO('mysql:host='.$_ENV["bdd"]["host"].';dbname='.$_ENV["bdd"]["bdd_name"].';charset=utf8', $_ENV['bdd']['login'], $_ENV['bdd']['pwd'], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    $bdd->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
}catch (Exception $e)
{
    die('Erreur : ' . $e->getMessage());
}

if (
    $_ENV['api']['secure'] and
    (
        !isset($_SERVER['HTTP_LOGIN']) or
        !isset($_SERVER['HTTP_PWD']) or
        $_ENV["api"]["login"] != $_SERVER['HTTP_LOGIN'] or
        $_ENV["api"]["pwd"] != $_SERVER['HTTP_PWD']
    )
) {
    http_response_code(401);
    exit('Unauthorized !');
}

function error_handler($errno, $errstr) {
    http_response_code(500);
    exit();
}

set_error_handler("error_handler");