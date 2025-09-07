<?php
session_start();
include ("connexion.php");

include("validation_droits.php");

$req = $bdd -> prepare("UPDATE contenant SET nom=?, capacite=?, type=? WHERE id=?");

$req -> execute(array($_POST["nom"],$_POST["capacite"],$_POST["type"],$_POST["id"]));


$answer=json_encode("ok");
	echo $answer;

?>