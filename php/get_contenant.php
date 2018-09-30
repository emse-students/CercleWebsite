<?php
session_start();
include ("connexion.php");



$req = $bdd -> query("SELECT id, nom FROM contenant");
$i=0;
while ($donnees = $req->fetch())
{
    $contenants[$i]["nom"]=$donnees["nom"];
    $contenants[$i]["id"]=$donnees["id"];
    $i++;
}


$answer["contenants"]=$contenants;

$answer=json_encode($answer);
echo $answer;
?>