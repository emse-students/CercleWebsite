<?php
session_start();
include ("connexion.php");



$req = $bdd -> query("SELECT id_user, login, prenom, nom, promo, solde, droit, type FROM user WHERE droit<>'aucun' order by promo desc");
$i=0;
$solde_positif=0;
$solde_negatif=0;
while ($donnees = $req->fetch())
{
    
	$users[$i]["id"]=$donnees["id_user"];
    $users[$i]["login"]=$donnees["login"];
    $users[$i]["promo"]=$donnees["promo"];
    $users[$i]["type"]=$donnees["type"];
    if ($donnees["nom"]=="")
    {
		$donnees["nom"]=explode(".",$donnees["login"])[1];
		$donnees["prenom"]=explode(".",$donnees["login"])[0];
	}
    $users[$i]["nom"]=$donnees["nom"];
    $users[$i]["prenom"]=$donnees["prenom"];
    $users[$i]["easy_search"]=$users[$i]["prenom"]." ".$users[$i]["nom"];
    $users[$i]["solde"]=$donnees["solde"];
    if ($donnees["solde"]>0) {
        $solde_positif+=$donnees["solde"];
    }else{
        $solde_negatif+=$donnees["solde"];
    }
    $users[$i]["droit"]=$donnees["droit"];
    $i++;
}


$answer["users"]=$users;
$answer["solde_negatif"]=$solde_negatif;
$answer["solde_positif"]=$solde_positif;

$answer=json_encode($answer);
echo $answer;
?>