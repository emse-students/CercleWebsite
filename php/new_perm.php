<?php
session_start();
include ("connexion.php");

if (isset($_GET["name"]))
{
	$req = $bdd -> prepare("SELECT id FROM nom_perm WHERE nom=?");
	$req->execute(array($_GET["name"]));

	$donnees = $req->fetch();
	if (isset($donnees["id"])) {
		$req = $bdd->prepare('INSERT INTO perm VALUES (null,?,?,0,0)');
		$req->execute(array($donnees["id"],time()));


		$answer['ok']=true;
		$answer=json_encode($answer);
		echo $answer;
	}
}

?>