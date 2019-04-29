<?php
include ("connexion.php");

$str_json = file_get_contents('php://input');
$json =json_decode($str_json);



$req = $bdd -> prepare("SELECT id_user FROM user WHERE login=?");
$req->execute(array($json->login));

$user = $req->fetch();
if (!isset($user["id_user"])) {
    exit('User does not exist !');
}

$req = $bdd -> prepare("SELECT id FROM consommable WHERE nom=?");
$req->execute(array($json->eventName));

$conso = $req->fetch();
if (!isset($donnees["id"])) {
    $req = $bdd->prepare('INSERT INTO consommable VALUES (null,?,0)');
    $req->execute(array($json->eventName));

    $req = $bdd -> prepare("SELECT id FROM consommable WHERE nom=?");
    $req->execute(array($json->eventName));

    $conso = $req->fetch();
}

$req = $bdd->prepare('INSERT INTO transaction VALUES (null,?,?,?,"C",?,?,?,?)');
$req->execute(array(
    $user["id_user"],
    0,
    0,
    $conso["id"],
    time(),
    1,
    -$json->amount
));

$req = $bdd->prepare('UPDATE user set solde=solde-? where id_user=?');
$req->execute(array($json->amount,$user["id_user"]));

http_response_code(204);