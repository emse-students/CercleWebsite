<?php
session_start();
include ("connexion.php");

include("validation_droits.php");


$req = $bdd -> prepare("SELECT id FROM boisson WHERE id_contenu=? and id_contenant=?");

$req -> execute(array($_POST["id_contenu"],$_POST["old_id"]));
$old_id=$req->fetch();

$req = $bdd -> prepare("SELECT id FROM boisson WHERE id_contenu=? and id_contenant=?");

$req -> execute(array($_POST["id_contenu"],$_POST["new_id"]));
$new_id=$req->fetch();

if(!isset($new_id['id']))
{
	$req = $bdd -> prepare("INSERT into boisson Values (null,?,?,0,0,0,0,0,0)");

	$req -> execute(array($_POST["id_contenu"],$_POST["new_id"]));

	$req = $bdd -> prepare("SELECT id FROM boisson WHERE id_contenu=? and id_contenant=?");

	$req -> execute(array($_POST["id_contenu"],$_POST["new_id"]));
	$new_id=$req->fetch();
}

$req = $bdd -> prepare("UPDATE transaction SET id_B_C=? WHERE B_C_A='B' and id_B_C=?");

$req -> execute(array($new_id['id'],$old_id['id']));

$req = $bdd -> prepare("DELETE FROM boisson WHERE id=?");

$req -> execute(array($old_id['id']));

$answer=json_encode("ok");
	echo $answer;

?>