<?php
session_start();
include ("connexion.php");

if ($_GET["old_id"]!=$_GET["new_id"]) {
	$req = $bdd -> prepare("UPDATE operation_cercle SET id_B_C=? WHERE B_C_A='C' and id_B_C=?");

	$req -> execute(array($_GET["new_id"],$_GET["old_id"]));	

	$req = $bdd -> prepare("DELETE FROM consommable WHERE id=?");

	$req -> execute(array($_GET["old_id"]));

	$answer=json_encode("ok");
		echo $answer;
}


?>