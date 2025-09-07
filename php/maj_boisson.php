<?php
session_start();
include ("connexion.php");

include("validation_droits.php");

if ($_GET["old_id"]!=$_GET["new_id"]) {
$req = $bdd -> prepare("UPDATE transaction SET id_B_C=? WHERE B_C_A='B' and id_B_C=?");

$req -> execute(array($_GET["new_id"],$_GET["old_id"]));

$req = $bdd -> prepare("SELECT id_contenu FROM boisson WHERE id=?");

$req -> execute(array($_GET["old_id"]));
$donnees=$req->fetch();

$req = $bdd -> prepare("DELETE FROM contenu WHERE id=?");

$req -> execute(array($donnees["id_contenu"]));

$req = $bdd -> prepare("DELETE FROM boisson WHERE id=?");

$req -> execute(array($_GET["old_id"]));

$answer=json_encode("ok");
	echo $answer;
}
?>