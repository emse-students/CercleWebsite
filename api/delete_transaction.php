<?php
include ("connexion.php");
http_response_code(204);

$str_json = file_get_contents('php://input');
$json =json_decode($str_json);

$req = $bdd->prepare('SELECT prix, id_user FROM transaction WHERE id=?');
$req->execute(array(
    $json->id
));

$transaction = $req->fetch();
if (!isset($transaction["prix"])) {
    http_response_code(400);
    exit('Transaction does not exist !');
}

$req = $bdd->prepare('UPDATE user set solde=solde-? where id_user=?');
$req->execute(array($transaction["prix"],$transaction["id_user"]));

$req = $bdd->prepare('DELETE FROM transaction WHERE id=?');
$req->execute(array(
    $json->id
));