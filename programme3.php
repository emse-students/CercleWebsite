<?php
session_start();
include ("php/connexion.php");
$a=0.1;
$req = $bdd -> query("SELECT id_user, login, solde FROM user WHERE droit<>'aucun'");
//$req = $bdd -> query("SELECT id_user, solde FROM user WHERE id_user=542");

while($users=$req->fetch())
{
	$rep = $bdd -> prepare("SELECT id, B_C_A, prix FROM transaction WHERE id_user=?");
	$rep -> execute(array($users["id_user"]));
	$i=0;

	$depense=0;
	$operations=[];
	while ($donnees = $rep->fetch())
    {
		$operations[$i]["id"]=$donnees["id"];
		$operations[$i]["type"]=$donnees["B_C_A"];	
		$operations[$i]["prix"]=$donnees["prix"];
		$depense+=$donnees["prix"];
		$i++;
	}
	echo "initialisation ".$users["login"]."<br>";
	echo "depense = ".$depense."<br>";
	echo "solde = ".$users["solde"]."<br>";
	if ($depense>$users["solde"]) {
		$reste=round($depense-$users["solde"],2);
		$j=0;
		while ($reste>0) {
			//echo "boucle ".$j." reste=".$reste."<br>";
			foreach ($operations as $key => $value) {
				if ($value["type"]=="B") {
					if ($reste>=0.1) {
						$rep2 = $bdd -> prepare("UPDATE transaction SET prix=prix-? WHERE id=?");
						$rep2 -> execute(array($a,$value["id"]));
						echo "operation ".$value["id"]." -0.1<br>";
						$reste=round($reste-0.1,2);
					}elseif ($reste>0 and $reste<0.1) {
						$rep2 = $bdd -> prepare("UPDATE transaction SET prix=prix-? WHERE id=?");
						$rep2 -> execute(array($reste,$value["id"]));
						echo "operation ".$value["id"]." -reste=".$reste."<br>";
						$reste=0;
					}
					echo "boucle ".$j." reste=".$reste."<br>";
				}
			}
			$j++;
		}
	}
}


?>