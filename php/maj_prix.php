<?php
session_start();
include ("connexion.php");
include("validation_droits.php");

$req = $bdd -> prepare("UPDATE boisson SET prix_vente=? WHERE id=?");

$req -> execute(array($_POST["prix"],$_POST["id"]));


$answer=json_encode("ok");
	echo $answer;

?>
