<?php
include ("connexion.php");
http_response_code(200);

$str_json = file_get_contents('php://input');
$json = json_decode($str_json);


$req = $bdd -> prepare("SELECT solde, droit FROM user WHERE login=?");
$req->execute(array($json->login));

$user = $req->fetch();

if (!isset($user["solde"])) {
    $answer["balance"]=0;
    $answer["contribute"]=false;
} else {
    $answer["balance"]=$user["solde"];
    $answer["contribute"]=$user["droit"]!='aucun';
}

$answer=json_encode($answer);

header('Content-Type: application/json');
echo $answer;