<?php
include("connexion.php");

function prix ($float)
{

	if ($float<0)
	{
		$float=-$float;
		$cent=($float*100)%100;
		$euro=floor($float);
		if ($cent==0)
		{
			return "- ".$euro."€";
		}else{
			if ($cent<10) {
				$cent="0".$cent;
			}
			return "- ".$euro."€".$cent;
		}

	}else{
		$cent=($float*100)%100;
		$euro=floor($float);
		if ($cent==0)
		{
			return "+ ".$euro."€";
		}else{
			if ($cent<10) {
				$cent="0".$cent;
			}
			return "+ ".$euro."€".$cent;
		}
	}
}

if (isset($_SESSION["id_cercle"]) AND $_SESSION["droit"]!="aucun")
{




	$operations=[];


		$user=[];
		$depense=0;
		$req = $bdd -> query("SELECT id_perm FROM `transaction` WHERE `B_C_A` = 'F' ORDER BY `transaction`.`datee` DESC LIMIT 1");

		$donnees = $req->fetch();
		$id_perm=$donnees['id_perm'];
		//echo "id=".$_GET["id"]."<br>";
		//echo "date_debut=".$_GET["date_debut"]."<br>";
		//echo "date_fin=".$_GET["date_fin"]."<br>";
		$req = $bdd -> prepare("SELECT id, id_B_C, datee, nb, prix FROM transaction WHERE B_C_A='F' and datee>? and id_perm=? ORDER BY datee");

		$req -> execute(array($_GET["time"],$id_perm));
		$i=0;
		$data=[];
		$last_x=$_GET['time'];
		while ($donnees = $req->fetch())
	  {
			$operations[$i]["id"]=$donnees["id"];
			$operations[$i]["date"]=$donnees["datee"];
			$point['x_time']=$donnees["datee"]*1000;
			$operations[$i]["prix"]=-$donnees["prix"];
			$operations[$i]["nb"]=$donnees["nb"];
			$operations[$i]["id_boisson"]=$donnees["id_B_C"];



			$rep = $bdd -> prepare("SELECT cu.id, cu.nom FROM boisson b, contenu cu WHERE b.id_contenu=cu.id and b.id=?");
			$rep -> execute(array($donnees["id_B_C"]));

			$donnees2 = $rep->fetch();
			$operations[$i]["nom"]=$donnees2["nom"];
			$operations[$i]["id_contenu"]=$donnees2["id"];

			if (array_key_exists($donnees2["nom"],$data))
			{
				$j=count($data[$donnees2["nom"]]);
			}else{
				$j=0;
				$data[$donnees2["nom"]]=[];
			}
			if ($donnees["nb"]>0) {
				$point['y_prix']=-$donnees["prix"]/$donnees["nb"];
			}else{
				$point['y_prix']=-$donnees["prix"];
			}
			$point['z_nb']=$donnees["nb"];


			$data[$donnees2["nom"]][$j]=$point;
			$last_x=$donnees["datee"];
			$i++;
		}




		$req = $bdd -> prepare("SELECT B_C, id_B_C FROM inventaire_perm WHERE id_perm = ? AND B_C='F'");

		$req -> execute(array($id_perm));

		$k=0;
		while ($donnees = $req->fetch())
		{
			if ($donnees["B_C"]=="F") {
				$rep = $bdd -> prepare("SELECT b.id, cu.nom, b.prix_vente FROM boisson b, contenu cu WHERE b.id_contenu=cu.id and b.id = ?");

				$rep -> execute(array($donnees["id_B_C"]));

				while ($donnees2 = $rep->fetch())
				{
					$boissons[$k]["id"]=$donnees2["id"];
					$boissons[$k]["nom"]=$donnees2["nom"];
					$boissons[$k]["prix"]=$donnees2["prix_vente"];
					$k++;
				}
			}
		}

		if ($k==0) {
			$boissons["forums"]=[];
		}

	$answer['boissons']=$boissons;
	$answer["operations"]=$operations;
	$answer["data"]=$data;
	$answer["last_x"]=$last_x;
	$answer=json_encode($answer);
	echo $answer;
}
?>
