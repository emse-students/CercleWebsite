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



	if ($_SESSION["droit"]=="user") {
		$_GET["id"]=$_SESSION["id_cercle"];
	}
	if (!isset($_GET["nb"])) {
		$_GET["nb"]=200;
	}



	$operations=[];
	//print_r($_GET);
	//echo "<br>";


		$user=[];
		$depense=0;
		//echo "id=".$_GET["id"]."<br>";
		//echo "date_debut=".$_GET["date_debut"]."<br>";
		//echo "date_fin=".$_GET["date_fin"]."<br>";
		$req = $bdd -> prepare("SELECT o.id, o.id_debiteur, o.B_C_A, o.id_B_C, o.datee as odatee, o.nb, o.prix, o.id_perm, o.id_user, p.datee as pdatee, np.nom as npnom, u.login, u.prenom, u.nom as unom, u.promo FROM transaction o, perm p, nom_perm np, user u WHERE ( np.id=p.id_nom_perm AND p.id=o.id_perm AND o.id_user=u.id_user AND o.B_C_A='A') ORDER BY o.datee DESC LIMIT ?");

		$req -> execute(array($_GET["nb"]));
		$i=0;
		//print_r($donnees);
		while ($donnees = $req->fetch())
	    {		//echo "boucle ".$i;
			$operations[$i]["id"]=$donnees["id"];
			if ($donnees["id_debiteur"]==0) {
				$operations[$i]["debiteur"]["id"]=$donnees["id_debiteur"];
				$operations[$i]["debiteur"]["login"]="inconnu";
				$operations[$i]["debiteur"]["prenom"]="inconnu";
				$operations[$i]["debiteur"]["nom"]="";
				$operations[$i]["debiteur"]["easy_search"]="inconnu";
				$operations[$i]["debiteur"]["promo"]="inconnu";
			}else{
				$rep = $bdd -> prepare("SELECT login, prenom, nom, promo FROM user WHERE id_user=?");
				$rep -> execute(array($donnees["id_debiteur"]));
				$donnees3 = $rep->fetch();
				$operations[$i]["debiteur"]["id"]=$donnees["id_debiteur"];
				$operations[$i]["debiteur"]["login"]=$donnees3["login"];
				$operations[$i]["debiteur"]["prenom"]=$donnees3["prenom"];
				$operations[$i]["debiteur"]["nom"]=$donnees3["nom"];
				$operations[$i]["debiteur"]["easy_search"]=$donnees3["prenom"]." ".$donnees3["nom"];
				$operations[$i]["debiteur"]["promo"]=$donnees3["promo"];
			}

			$operations[$i]["type"]=$donnees["B_C_A"];
			//$operations[$i]["date"]=date("d/m/y H:i",$donnees["odatee"]);
			$operations[$i]["date"]=$donnees["odatee"];
			$operations[$i]["prix"]=$donnees["prix"];

			$operations[$i]["user"]["id"]=$donnees["id_user"];
			$operations[$i]["user"]["login"]=$donnees["login"];
			if ($donnees["unom"]==""){
				$donnees["unom"]=explode(".",$donnees["login"])[1];
				$donnees["prenom"]=explode(".",$donnees["login"])[0];
			}
			$operations[$i]["user"]["prenom"]=$donnees["prenom"];
			$operations[$i]["user"]["nom"]=$donnees["unom"];
			$operations[$i]["user"]["easy_search"]=$donnees["prenom"]." ".$donnees["unom"];
			$operations[$i]["user"]["promo"]=$donnees["promo"];

			$operations[$i]["perm"]["id"]=$donnees["id_perm"];
			$operations[$i]["perm"]["nom"]=$donnees["npnom"];
			$operations[$i]["perm"]["date"]=$donnees["pdatee"];
			$operations[$i]["nb"]=$donnees["nb"];
			$operations[$i]["achat"]["type"]="A";
			$operations[$i]["achat"]["id"]=0;
			$operations[$i]["achat"]["nom"]="Rechargement";




			$i++;
		}


	$user["nb"]=$i;
	$user["depense"]=prix($depense);
	$answer["user"]=$user;
	$answer["operations"]=$operations;
	$answer=json_encode($answer);
	echo $answer;
}
?>
