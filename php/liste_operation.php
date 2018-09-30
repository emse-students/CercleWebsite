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

if (isset($_SESSION["id_cercle"]) AND $_SESSION["droit_cercle"]!="aucun")
{



	if ($_SESSION["droit_cercle"]=="user") {
		$_GET["id"]=$_SESSION["id_cercle"];
	}
	if (!isset($_GET["nb"])) {
		$_GET["nb"]=50;
	}

	if (!isset($_GET["date_debut"]))
	{
		$_GET["date_debut"]=1356994800;
	}

	if (!isset($_GET["date_fin"]))
	{
		$_GET["date_fin"]=time();
	}

	if (!isset($_GET["id"]))
	{
		$_GET["id"]=0;
	}


	$operations=[];
	//print_r($_GET);
	//echo "<br>";
	if ($_GET["id"]==0) {

		$user=[];
		$depense=0;
		//echo "id=".$_GET["id"]."<br>";
		//echo "date_debut=".$_GET["date_debut"]."<br>";
		//echo "date_fin=".$_GET["date_fin"]."<br>";
		$req = $bdd -> prepare("SELECT o.id, o.id_debiteur, o.B_C_A, o.id_B_C, o.datee as odatee, o.nb, o.prix, o.id_perm, o.id_user, p.datee as pdatee, np.nom as npnom, u.login_user, u.prenom, u.nom as unom, u.promo_user FROM operation_cercle o, perm p, nom_perm np, user u WHERE ( np.id=p.id_nom_perm AND p.id=o.id_perm AND o.id_user=u.id_user AND o.datee>? AND o.datee<?) ORDER BY o.datee DESC LIMIT ?");

		$req -> execute(array($_GET["date_debut"],$_GET["date_fin"],$_GET["nb"]));
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
				$rep = $bdd -> prepare("SELECT login_user, prenom, nom, promo_user FROM user WHERE id_user=?");
				$rep -> execute(array($donnees["id_debiteur"]));
				$donnees3 = $rep->fetch();
				$operations[$i]["debiteur"]["id"]=$donnees["id_debiteur"];
				$operations[$i]["debiteur"]["login"]=$donnees3["login_user"];
				$operations[$i]["debiteur"]["prenom"]=$donnees3["prenom"];
				$operations[$i]["debiteur"]["nom"]=$donnees3["nom"];
				$operations[$i]["debiteur"]["easy_search"]=$donnees3["prenom"]." ".$donnees3["nom"];
				$operations[$i]["debiteur"]["promo"]=$donnees3["promo_user"];
			}

			$operations[$i]["type"]=$donnees["B_C_A"];
			//$operations[$i]["date"]=date("d/m/y H:i",$donnees["odatee"]);
			$operations[$i]["date"]=$donnees["odatee"];
			$operations[$i]["prix"]=$donnees["prix"];

			$operations[$i]["user"]["id"]=$donnees["id_user"];
			$operations[$i]["user"]["login"]=$donnees["login_user"];
			if ($donnees["unom"]==""){
				$donnees["unom"]=explode(".",$donnees["login_user"])[1];
				$donnees["prenom"]=explode(".",$donnees["login_user"])[0];
			}
			$operations[$i]["user"]["prenom"]=$donnees["prenom"];
			$operations[$i]["user"]["nom"]=$donnees["unom"];
			$operations[$i]["user"]["easy_search"]=$donnees["prenom"]." ".$donnees["unom"];
			$operations[$i]["user"]["promo"]=$donnees["promo_user"];

			$operations[$i]["perm"]["id"]=$donnees["id_perm"];
			$operations[$i]["perm"]["nom"]=$donnees["npnom"];
			$operations[$i]["perm"]["date"]=$donnees["pdatee"];
			$operations[$i]["nb"]=$donnees["nb"];

			if ($donnees["B_C_A"]=='A') {
				$operations[$i]["achat"]["type"]="A";
				$operations[$i]["achat"]["id"]=0;
				$operations[$i]["achat"]["nom"]="Rechargement";

			}elseif ($donnees["B_C_A"]=='B') {
				$operations[$i]["achat"]["type"]="B";


				$rep = $bdd -> prepare("SELECT cu.id, cu.nom FROM boisson b, contenu cu WHERE b.id_contenu=cu.id and b.id=?");
				$rep -> execute(array($donnees["id_B_C"]));

				$donnees2 = $rep->fetch();
				$operations[$i]["achat"]["nom"]=$donnees2["nom"];
				$operations[$i]["achat"]["id"]=$donnees2["id"];
			}elseif ($donnees["B_C_A"]=='F') {
				$operations[$i]["achat"]["type"]="B";


				$rep = $bdd -> prepare("SELECT cu.id, cu.nom FROM boisson b, contenu cu WHERE b.id_contenu=cu.id and b.id=?");
				$rep -> execute(array($donnees["id_B_C"]));

				$donnees2 = $rep->fetch();
				$operations[$i]["achat"]["nom"]=$donnees2["nom"];
				$operations[$i]["achat"]["id"]=$donnees2["id"];
			}elseif ($donnees["B_C_A"]=='C') {

				$operations[$i]["achat"]["type"]="C";
				$operations[$i]["achat"]["id"]=$donnees["id_B_C"];

				$rep = $bdd -> prepare("SELECT nom FROM consommable WHERE id=?");
				$rep -> execute(array($donnees["id_B_C"]));

				$donnees2 = $rep->fetch();
				$operations[$i]["achat"]["nom"]=$donnees2["nom"];
			}


			$i++;
		}
	}else{
		$req = $bdd -> prepare("SELECT solde_cercle, prenom, nom, login_user FROM user WHERE id_user=? ");
		$req -> execute(array($_GET["id"]));
		$donnees = $req->fetch();

		$user["solde"]=prix($donnees["solde_cercle"]);
		if ($donnees["nom"]==""){
			$donnees["nom"]=explode(".",$donnees["login_user"])[1];
			$donnees["prenom"]=explode(".",$donnees["login_user"])[0];
		}

		$user["nom"]=$donnees["nom"];
		$user["prenom"]=$donnees["prenom"];



		$req = $bdd -> prepare("SELECT o.id, o.id_debiteur, o.B_C_A, o.id_B_C, o.datee as odatee, o.nb, o.prix, o.id_perm, p.datee as pdatee, np.nom as npnom, u.login_user, u.prenom, u.nom as unom, u.promo_user FROM operation_cercle o, perm p, nom_perm np, user u WHERE ( np.id=p.id_nom_perm AND p.id=o.id_perm AND o.id_user=? AND o.id_user=u.id_user AND o.datee>? AND o.datee<? ) ORDER BY o.datee DESC");
		$req -> execute(array($_GET["id"],$_GET["date_debut"],$_GET["date_fin"]));
		$i=0;

		$depense=0;
		while ($donnees = $req->fetch())
	    {
			$operations[$i]["id"]=$donnees["id"];
			if ($donnees["id_debiteur"]==0) {
				$operations[$i]["debiteur"]["id"]=$donnees["id_debiteur"];
				$operations[$i]["debiteur"]["login"]="inconnu";
				$operations[$i]["debiteur"]["prenom"]="inconnu";
				$operations[$i]["debiteur"]["nom"]="";
				$operations[$i]["debiteur"]["easy_search"]="inconnu";
				$operations[$i]["debiteur"]["promo"]="inconnu";
			}else{
				$rep = $bdd -> prepare("SELECT login_user, prenom, nom, promo_user FROM user WHERE id_user=?");
				$rep -> execute(array($donnees["id_debiteur"]));
				$donnees2 = $rep->fetch();

				$operations[$i]["debiteur"]["id"]=$donnees["id_debiteur"];
				$operations[$i]["debiteur"]["login"]=$donnees2["login_user"];
				$operations[$i]["debiteur"]["prenom"]=$donnees2["prenom"];
				$operations[$i]["debiteur"]["nom"]=$donnees2["nom"];
				$operations[$i]["debiteur"]["easy_search"]=$donnees2["prenom"]." ".$donnees2["nom"];
				$operations[$i]["debiteur"]["promo"]=$donnees2["promo_user"];
			}

			$operations[$i]["type"]=$donnees["B_C_A"];
			$operations[$i]["date"]=$donnees["odatee"];
			//$operations[$i]["date"]=date("d/m/y H:i",$donnees["odatee"]);
			$operations[$i]["prix"]=$donnees["prix"];

			$operations[$i]["user"]["id"]=$_GET["id"];
			$operations[$i]["user"]["login"]=$donnees["login_user"];
			$operations[$i]["user"]["prenom"]=$donnees["prenom"];
			$operations[$i]["user"]["nom"]=$donnees["unom"];
			$operations[$i]["user"]["easy_search"]=$donnees["prenom"]." ".$donnees["unom"];
			$operations[$i]["user"]["promo"]=$donnees["promo_user"];

			$operations[$i]["perm"]["id"]=$donnees["id_perm"];
			$operations[$i]["perm"]["nom"]=$donnees["npnom"];
			$operations[$i]["perm"]["date"]=$donnees["pdatee"];
			$operations[$i]["nb"]=$donnees["nb"];

			if ($donnees["B_C_A"]=='A') {
				$operations[$i]["achat"]["type"]="A";
				$operations[$i]["achat"]["id"]=0;
				$operations[$i]["achat"]["nom"]="Rechargement";

				$depense+=$donnees["prix"];

			}elseif ($donnees["B_C_A"]=='B') {
				$operations[$i]["achat"]["type"]="B";


				$rep = $bdd -> prepare("SELECT cu.id, cu.nom FROM boisson b, contenu cu WHERE b.id_contenu=cu.id and b.id=?");
				$rep -> execute(array($donnees["id_B_C"]));

				$donnees2 = $rep->fetch();
				$operations[$i]["achat"]["nom"]=$donnees2["nom"];
				$operations[$i]["achat"]["id"]=$donnees2["id"];

				$depense+=$donnees["prix"];
			}elseif ($donnees["B_C_A"]=='F') {
				$operations[$i]["achat"]["type"]="B";


				$rep = $bdd -> prepare("SELECT cu.id, cu.nom FROM boisson b, contenu cu WHERE b.id_contenu=cu.id and b.id=?");
				$rep -> execute(array($donnees["id_B_C"]));

				$donnees2 = $rep->fetch();
				$operations[$i]["achat"]["nom"]=$donnees2["nom"];
				$operations[$i]["achat"]["id"]=$donnees2["id"];
			}elseif ($donnees["B_C_A"]=='C') {

				$operations[$i]["achat"]["type"]="C";
				$operations[$i]["achat"]["id"]=$donnees["id_B_C"];

				$rep = $bdd -> prepare("SELECT nom FROM consommable WHERE id=?");
				$rep -> execute(array($donnees["id_B_C"]));

				$donnees2 = $rep->fetch();
				$operations[$i]["achat"]["nom"]=$donnees2["nom"];

				$depense+=$donnees["prix"];
			}


			$i++;
		}
	}
	$user["nb"]=$i;
	$user["depense"]=prix($depense);
	$answer["user"]=$user;
	$answer["operations"]=$operations;
	$answer=json_encode($answer);
	echo $answer;
}
?>
