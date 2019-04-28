<?php
session_start();
include ("connexion.php");



$req = $bdd -> query("SELECT id_user, login, prenom, nom, promo, type, solde, droit FROM user order by promo desc ");
$i=0;
while ($donnees = $req->fetch())
{
    
	$users[$i]["id"]=$donnees["id_user"];
    $users[$i]["login"]=$donnees["login"];
    $users[$i]["promo"]=$donnees["promo"];
    $users[$i]["nom"]=$donnees["nom"];
    $users[$i]["prenom"]=$donnees["prenom"];
    $users[$i]["type"]=$donnees["type"];
    $users[$i]["easy_search"]=$users[$i]["prenom"]." ".$users[$i]["nom"];
    $users[$i]["solde"]=$donnees["solde"];
    $users[$i]["droit"]=$donnees["droit"];
    $i++;
}


$answer["users"]=$users;

$answer=json_encode($answer);
echo $answer;
?>