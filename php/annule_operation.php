<?php
session_start();
include ("connexion.php");

$req = $bdd -> prepare("SELECT id_user, id_perm, prix, B_C_A, id_B_C, nb FROM operation_cercle Where id=?");

$req -> execute(array($_POST["id"]));

$donnees=$req->fetch();

if ($donnees["id_perm"]!=0) {
	if ($donnees['B_C_A']=="B") {
		$req = $bdd -> prepare("SELECT ct.capacite FROM contenant ct, boisson b Where b.id=? and ct.id=b.id_contenant");

		$req -> execute(array($donnees["id_B_C"]));

		$donnees2=$req->fetch();
		$capacite=$donnees2["capacite"];
	}else{
		$capacite=0;
	}

	$req = $bdd -> prepare("UPDATE perm SET total_vente=total_vente+?, total_litre=total_litre-? WHERE id=?");

	$req -> execute(array($donnees["prix"],$donnees['nb']*$capacite,$donnees["id_perm"]));
}
	



$req = $bdd -> prepare("UPDATE user SET solde_cercle=solde_cercle-? WHERE id_user=?");

$req -> execute(array($donnees["prix"],$donnees['id_user']));



$req = $bdd -> prepare("DELETE FROM operation_cercle Where id=?");

$req -> execute(array($_POST["id"]));


$answer=json_encode("ok");
	echo $answer;

?>