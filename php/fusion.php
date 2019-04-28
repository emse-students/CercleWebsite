<?php
session_start();
include ("connexion.php");

if($_SESSION["droit"]=="cercle"){

	$req = $bdd -> prepare("SELECT login, nom, prenom FROM user WHERE id_user=?");
	$req->execute(array($_POST['id_user2']));

	$donnees = $req->fetch();

	$req = $bdd->prepare('UPDATE user SET login=?, nom=?, prenom=? WHERE id_user=?');
	$req->execute(array($donnees['login'],$donnees['nom'],$donnees['prenom'],$_POST['id_user1']));

	$req = $bdd->prepare('DELETE FROM user WHERE id_user=?');
	$req->execute(array($_POST['id_user2']));

	$answer=json_encode('ok');
	echo $answer;
}


?>
