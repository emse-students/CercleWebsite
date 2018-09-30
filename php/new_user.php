<?php
session_start();
include ("connexion.php");

if($_SESSION["droit_cercle"]=="cercle"){
	$solde=$_POST["montant"]-$_POST["cotis"];

	$req = $bdd -> prepare("SELECT droit_cercle, id_user FROM user WHERE login_user=?");
	$req->execute(array($_POST["login"]));

	$donnees = $req->fetch();
	if (isset($donnees["droit_cercle"])) {
		if ($donnees["droit_cercle"]=="aucun") {
			$req = $bdd->prepare('UPDATE user SET droit_cercle="user", solde_cercle=? WHERE id_user=?');
			$req->execute(array($solde,$donnees["id_user"]));

			$req = $bdd->prepare('INSERT INTO operation_cercle VALUES (null,?,?,2,"A",0,?,1,?)');
				$req->execute(array($donnees["id_user"],
						$_SESSION["id_cercle"],
						time(),
						$_POST["montant"]
						));

				$req = $bdd->prepare('INSERT INTO operation_cercle VALUES (null,?,?,2,"C",2,?,1,?)');
				$req->execute(array($donnees["id_user"],
						$_SESSION["id_cercle"],
						time(),
						-$_POST["cotis"]
						));

			$answer["statu"]="ok";
		}else{
			$answer["statu"]="exist";
		}
	}else{
		$req = $bdd->prepare('INSERT into user values (null,?,"","",?,?,0,"aucun",?,"user")');
		$req->execute(array($_POST["login"],$_POST["type"],$_POST["promo"],$solde));

		$req = $bdd -> prepare("SELECT droit_cercle, id_user FROM user WHERE login_user=?");
		$req->execute(array($_POST["login"]));

		$donnees = $req->fetch();

		$req = $bdd->prepare('INSERT INTO operation_cercle VALUES (null,?,?,2,"A",0,?,1,?)');
			$req->execute(array($donnees["id_user"],
					$_SESSION["id_cercle"],
					time(),
					$_POST["montant"]
					));

			$req = $bdd->prepare('INSERT INTO operation_cercle VALUES (null,?,?,2,"C",2,?,1,?)');
			$req->execute(array($donnees["id_user"],
					$_SESSION["id_cercle"],
					time(),
					-$_POST["cotis"]
					));

		$answer["statu"]="ok";
	}
	$answer=json_encode($answer);
	echo $answer;
}


?>
