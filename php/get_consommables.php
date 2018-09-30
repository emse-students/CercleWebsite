<?php
session_start();
include ("connexion.php");

$req = $bdd -> query("SELECT id, nom FROM consommable order by nom");
		
$i=0;
while ($donnees = $req->fetch())
{
	$boissons[$i]["id"]=$donnees["id"];
	$boissons[$i]["nom"]=$donnees["nom"];
	$i++;
}

$answer=json_encode($boissons);
	echo $answer;
?>