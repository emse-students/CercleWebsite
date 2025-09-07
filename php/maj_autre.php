<?php
session_start();
include ("connexion.php");

include("validation_droits.php");

$req = $bdd -> prepare("SELECT id from consommable WHERE nom=?");

$req -> execute(array($_GET["old_nom"]));

$donnees=$req->fetch();

if (!isset($donnees["id"])) {
	$req = $bdd -> prepare("INSERT into consommable VALUES (NULL,?, 0) ");

	$req -> execute(array($_GET["old_nom"]));

	$req = $bdd -> prepare("SELECT id from consommable WHERE nom=?");

	$req -> execute(array($_GET["old_nom"]));

	$donnees=$req->fetch();
}


$req = $bdd -> prepare("UPDATE transaction SET B_C_A='C', id_B_C=? WHERE B_C_A='B' and id_B_C=?");

$req -> execute(array($donnees["id"],$_GET["old_id"]));	

$req = $bdd -> prepare("DELETE FROM boisson WHERE id=?");

$req -> execute(array($_GET["old_id"]));

$answer=json_encode("ok");
	echo $answer;
?>