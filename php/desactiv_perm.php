<?php
session_start();
include ("connexion.php");


$req = $bdd -> prepare("UPDATE nom_perm SET isactiv=0 WHERE id=?");

$req -> execute(array($_POST["id"]));

$answer=json_encode("ok");
echo $answer;

?>