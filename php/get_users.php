<?php
session_start();
include ("connexion.php");



$req = $bdd -> query("SELECT id_user, login_user, prenom, nom, promo_user, solde_cercle, droit_cercle FROM user WHERE droit_cercle<>'aucun'");
$i=0;
$solde_positif=0;
$solde_negatif=0;
while ($donnees = $req->fetch())
{
    
	$users[$i]["id"]=$donnees["id_user"];
    $users[$i]["login"]=$donnees["login_user"];
    $users[$i]["promo"]=$donnees["promo_user"];
    if ($donnees["nom"]=="")
    {
		$donnees["nom"]=explode(".",$donnees["login_user"])[1];
		$donnees["prenom"]=explode(".",$donnees["login_user"])[0];
	}
    $users[$i]["nom"]=$donnees["nom"];
    $users[$i]["prenom"]=$donnees["prenom"];
    $users[$i]["easy_search"]=$users[$i]["prenom"]." ".$users[$i]["nom"];
    $users[$i]["solde"]=$donnees["solde_cercle"];
    if ($donnees["solde_cercle"]>0) {
        $solde_positif+=$donnees["solde_cercle"];
    }else{
        $solde_negatif+=$donnees["solde_cercle"];
    }
    $users[$i]["droit"]=$donnees["droit_cercle"];    
    $i++;
}


$answer["users"]=$users;
$answer["solde_negatif"]=$solde_negatif;
$answer["solde_positif"]=$solde_positif;

$answer=json_encode($answer);
echo $answer;
?>