<?php
session_start();
include ("connexion.php");

$req = $bdd -> query("SELECT id, nom, valeur FROM constante order by id ");
		
$i=0;
while ($donnees = $req->fetch())
{
	$constantes[$i]["id"]=$donnees["id"];
	$constantes[$i]["nom"]=$donnees["nom"];
	//$constantes[$i]["valeur"]=floatval($donnees["valeur"]);
	$constantes[$i]["valeur"]=$donnees["valeur"];
	$i++;
}

$answer=json_encode($constantes);
	echo $answer;
?>