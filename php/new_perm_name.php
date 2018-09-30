<?php
session_start();
include ("connexion.php");

$str_json = file_get_contents('php://input');
$str_json =json_decode($str_json);
$data=$str_json->data;

$req = $bdd->prepare('INSERT INTO nom_perm VALUES (null,?,?,1)');
$req->execute(array($str_json->perm_name,date("Y")));

$req = $bdd->prepare('SELECT id from nom_perm WHERE nom=?');
$req->execute(array($str_json->perm_name));

$donnees=$req->fetch();

foreach ($data as $value)
{
    $req = $bdd->prepare('INSERT INTO membre_perm VALUES (null,?,?)');
    $req->execute(array($value,$donnees["id"]));

    $req = $bdd->prepare('SELECT droit_cercle FROM user WHERE id_user=?');
    $req->execute(array($value));

    $donnees2=$req->fetch();

    if ($donnees2['droit_cercle']!='cercle'){
      $req = $bdd->prepare('UPDATE user SET droit_cercle="cercleux" WHERE id_user=?');
      $req->execute(array($value));
    }    
}


$answer['ok']=true;
$answer=json_encode($answer);
echo $answer;
?>
