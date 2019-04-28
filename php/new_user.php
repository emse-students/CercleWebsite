<?php
session_start();
include ("connexion.php");

if($_SESSION["droit"]=="cercle"){
	$solde=$_POST["montant"]-$_POST["cotis"];

	if (isset($_POST['id_user'])) {
		$req = $bdd->prepare('UPDATE user SET droit="user", solde=? WHERE id_user=?');
		$req->execute(array($solde,$_POST['id_user']));

		$req = $bdd->prepare('INSERT INTO transaction VALUES (null,?,?,2,"A",0,?,1,?)');
		$req->execute(array($_POST['id_user'],
			$_SESSION["id_cercle"],
			time(),
			$_POST["montant"]
		));

		$req = $bdd->prepare('INSERT INTO transaction VALUES (null,?,?,2,"C",2,?,1,?)');
		$req->execute(array($_POST['id_user'],
			$_SESSION["id_cercle"],
			time(),
			-$_POST["cotis"]
		));

		$answer["statu"]="ok";
	} else {
		$login = strtolower($_POST["prenom"]).'.'.strtolower($_POST["nom"]);
		$req = $bdd -> prepare("SELECT droit, id_user FROM user WHERE login=?");
		$req->execute(array($login));

		$donnees = $req->fetch();
		if (isset($donnees["droit"])) {
			$answer["statu"]="exist";
		}else{
			$req = $bdd->prepare('INSERT into user values (null,?,?,?,?,?,?,"user")');
			$req->execute(array($login,$_POST["prenom"], $_POST["nom"],"autre",$_POST["promo"],$solde));

			$req = $bdd -> prepare("SELECT droit, id_user FROM user WHERE login=?");
			$req->execute(array($login));

			$donnees = $req->fetch();

			$req = $bdd->prepare('INSERT INTO transaction VALUES (null,?,?,2,"A",0,?,1,?)');
			$req->execute(array($donnees["id_user"],
				$_SESSION["id_cercle"],
				time(),
				$_POST["montant"]
			));

			$req = $bdd->prepare('INSERT INTO transaction VALUES (null,?,?,2,"C",2,?,1,?)');
			$req->execute(array($donnees["id_user"],
				$_SESSION["id_cercle"],
				time(),
				-$_POST["cotis"]
			));

			$answer["statu"]="ok";
		}
	}
	$answer=json_encode($answer);
	echo $answer;
}


?>
