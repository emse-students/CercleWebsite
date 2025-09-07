<?php
session_start();
include ("connexion.php");

include("validation_droits.php");


$req = $bdd -> prepare("UPDATE constante SET valeur=? WHERE id=?");

$req -> execute(array($_POST["valeur"],$_POST["id"]));


$answer=json_encode("ok");
	echo $answer;

?>