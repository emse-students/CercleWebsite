<?php
include ("connexion.php");

$str_json = file_get_contents('php://input');
$json = json_decode($str_json);

$answer["soldes"]=[];
foreach ($json["logins"] as $key => $login) {
    $req = $bdd->prepare("SELECT solde FROM user WHERE login=?");
    $req->execute(array($login));

    $user = $req->fetch();

    if (!isset($user["solde"])) {
        $answer["soldes"][$login] = $user["solde"];
    }
}
$answer=json_encode($answer);

http_response_code(200);
header('Content-Type: application/json');
echo $answer;