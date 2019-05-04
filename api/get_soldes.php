<?php
include ("connexion.php");
http_response_code(200);

$str_json = file_get_contents('php://input');
$json = json_decode($str_json);

$answer=[];
foreach ($json->logins as $key => $login) {
    $req = $bdd->prepare("SELECT solde, droit FROM user WHERE login=?");
    $req->execute(array($login));

    $user = $req->fetch();

    if (isset($user["solde"])) {
        $answer[$login] = [];
        $answer[$login]["balance"] = $user["solde"];
        $answer[$login]["contribute"] = $user["droit"] != 'aucun';
    }
}
$answer=json_encode($answer);

header('Content-Type: application/json');
echo $answer;