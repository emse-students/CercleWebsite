<?php
session_start();
include ("connexion.php");

if (isset($_POST["nom"]))
{
	$req = $bdd -> prepare("SELECT id FROM contenu WHERE nom=?");
	$req->execute(array($_POST["nom"]));

	$donnees = $req->fetch();
	if (!isset($donnees["id"])) {
		$req = $bdd->prepare('INSERT INTO contenu VALUES (null,?,"inconnu",0,"")');
		$req->execute(array($_POST["nom"]));


		$req = $bdd -> prepare("SELECT id, nom, type, degre, description FROM contenu WHERE nom=?");
		$req->execute(array($_POST["nom"]));

		$donnees2 = $req->fetch();

		$answer["contenu"]["id"]=$donnees2["id"];
		$answer["contenu"]["nom"]=$donnees2["nom"];
		$answer["contenu"]["type"]=$donnees2["type"];
		$answer["contenu"]["degre"]=$donnees2["degre"];
		$answer["contenu"]["description"]=$donnees2["description"];
		$answer["contenu"]["contenants"]=[];

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