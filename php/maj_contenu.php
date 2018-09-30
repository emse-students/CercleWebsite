<?php
session_start();
include ("connexion.php");

if (isset($_POST["nom"])) {
	$req = $bdd -> prepare("UPDATE contenu SET nom=? WHERE id=?");

	$req -> execute(array($_POST["nom"],$_POST["id"]));
}
if (isset($_POST["type"])) {
	$req = $bdd -> prepare("UPDATE contenu SET type=? WHERE id=?");

	$req -> execute(array($_POST["type"],$_POST["id"]));
}
if (isset($_POST["degre"])) {
	$req = $bdd -> prepare("UPDATE contenu SET degre=? WHERE id=?");

	$req -> execute(array($_POST["degre"],$_POST["id"]));
}
if (isset($_POST["description"])) {
	$req = $bdd -> prepare("UPDATE contenu SET description=? WHERE id=?");

	$req -> execute(array($_POST["description"],$_POST["id"]));
}
if (isset($_POST["contenant"])) {
	if ($_POST["contenant"]>0) {
		$add=true;
	}else{
		$add=false;
		$_POST["contenant"]=-$_POST["contenant"];
	}

	$req = $bdd -> prepare("SELECT id FROM boisson WHERE id_contenant=? and id_contenu=?");

	$req -> execute(array($_POST["contenant"],$_POST["id"]));

	$donnees=$req->fetch();

	if ($add) 
	{
		if (!isset($donnees["id"])) {
			$req = $bdd -> prepare("INSERT into boisson Values (null,?,?,0,0,0,0,0,0)");

			$req -> execute(array($_POST["id"],$_POST["contenant"]));
		}
	}else{
		$req = $bdd -> prepare("SELECT id FROM operation_cercle WHERE B_C_A='B' and id_B_C=?");

		$req -> execute(array($donnees["id"]));

		$donnees2=$req->fetch();

		if (!isset($donnees2["id"])) {

			$req = $bdd -> prepare("DELETE FROM boisson WHERE id=?");

			$req -> execute(array($donnees["id"]));
		}
	}

	
}

if (isset($_POST["consigne"])) {
	

	$req = $bdd -> prepare("SELECT id FROM boisson WHERE id_contenant=? and id_contenu=?");

	$req -> execute(array($_POST["id_contenant"],$_POST["id"]));

	$donnees=$req->fetch();

	$req = $bdd -> prepare("UPDATE boisson SET consigne=? WHERE id=?");

	$req -> execute(array($_POST["consigne"],$donnees["id"]));	
}

$answer=json_encode("ok");
	echo $answer;

?>