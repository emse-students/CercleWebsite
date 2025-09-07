<?php
session_start();
include ("connexion.php");

include("validation_droits.php");

if (isset($_POST["nom"])) {
	$req = $bdd -> prepare("UPDATE nom_perm SET nom=? WHERE id=?");

	$req -> execute(array($_POST["nom"],$_POST["id"]));
}

if (isset($_POST["id_membre"])) {
	if ($_POST["id_membre"]>0) {
		$add=true;
	}else{
		$add=false;
		$_POST["id_membre"]=-$_POST["id_membre"];
	}

	$req = $bdd -> prepare("SELECT id FROM membre_perm WHERE id_user=? and id_nom_perm=?");

	$req -> execute(array($_POST["id_membre"],$_POST["id"]));

	$donnees=$req->fetch();

	if ($add) 
	{
		if (!isset($donnees["id"])) {
			$req = $bdd -> prepare("INSERT into membre_perm Values (null,?,?)");

			$req -> execute(array($_POST["id_membre"],$_POST["id"]));
		}
	}else{
		
		

			$req = $bdd -> prepare("DELETE FROM membre_perm WHERE id=?");

			$req -> execute(array($donnees["id"]));
		
	}

	
}



$answer=json_encode("ok");
	echo $answer;

?>