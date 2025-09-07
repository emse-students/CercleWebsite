<?php
session_start();
include ("connexion.php");

include("validation_droits.php");

if (isset($_POST["nom"]))
{
	$req = $bdd -> prepare("SELECT id FROM contenant WHERE nom=?");
	$req->execute(array($_POST["nom"]));

	$donnees = $req->fetch();
	if (!isset($donnees["id"])) {
		$req = $bdd->prepare('INSERT INTO contenant VALUES (null,?,?,?)');
		$req->execute(array($_POST["nom"],$_POST["capacite"],$_POST["type"]));


		$req = $bdd -> prepare("SELECT id, nom, type, capacite FROM contenant WHERE nom=?");
		$req->execute(array($_POST["nom"]));

		$donnees2 = $req->fetch();

		$answer["contenant"]["id"]=$donnees2["id"];
		$answer["contenant"]["nom"]=$donnees2["nom"];
		$answer["contenant"]["type"]=$donnees2["type"];
		$answer["contenant"]["capacite"]=$donnees2["capacite"];


		$answer['ok']=true;
		
	}else{
		$answer['ok']=false;
	}
}else{
	$answer['ok']=false;
}
$answer=json_encode($answer);
		echo $answer;
?>