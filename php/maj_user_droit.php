<?php
session_start();
include ("connexion.php");


$req = $bdd -> prepare("UPDATE user SET droit=? WHERE id_user=?");

$req -> execute(array($_POST["droit"],$_POST["id"]));


$answer=json_encode("ok");
	echo $answer;

?>