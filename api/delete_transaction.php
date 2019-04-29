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

$req = $bdd->prepare('SELECT prix FROM transaction WHERE id=?');
$req->execute(array(
    $json->id
));

$transaction = $req->fetch();
if (!isset($transaction["prix"])) {
    exit('Transaction does not exist !');
}

$req = $bdd->prepare('UPDATE user set solde=solde-? where id_user=?');
$req->execute(array($transaction["prix"],$user["id_user"]));

$req = $bdd->prepare('DELETE FROM prix WHERE id=?');
$req->execute(array(
    $json->id
));

http_response_code(204);