<?php
session_start();
include ("connexion.php");

$req = $bdd -> prepare("SELECT id from contenu WHERE nom=?");

$req -> execute(array($_GET["old_nom"]));

$donnees=$req->fetch();

if (!isset($donnees["id"])) {
	$req = $bdd -> prepare("INSERT into contenu VALUES (NULL,?, 'inconnu',0,'') ");

	$req -> execute(array($_GET["old_nom"]));

	$req = $bdd -> prepare("SELECT id from contenu WHERE nom=?");

	$req -> execute(array($_GET["old_nom"]));

	$donnees=$req->fetch();
}

$req = $bdd -> prepare("SELECT id from boisson WHERE id_contenu=? and id_contenant=?");

$req -> execute(array($donnees["id"],$_GET["id_contenant"]));

$donnees2=$req->fetch();

if (!isset($donnees2["id"])) {
	$req = $bdd -> prepare("INSERT into boisson VALUES (NULL,?,?,0,0,0,0,0,0) ");

	$req -> execute(array($donnees["id"],$_GET["id_contenant"]));

	$req = $bdd -> prepare("SELECT id from boisson WHERE id_contenu=? and id_contenant=?");

	$req -> execute(array($donnees["id"],$_GET["id_contenant"]));

	$donnees2=$req->fetch();
}


$req = $bdd -> prepare("UPDATE operation_cercle SET B_C_A='B', id_B_C=? WHERE  B_C_A='C' and id_B_C=?");

$req -> execute(array($donnees2["id"],$_GET["old_id"]));	

$req = $bdd -> prepare("DELETE FROM consommable WHERE id=?");

$req -> execute(array($_GET["old_id"]));

$answer=json_encode("ok");
	echo $answer;
?>