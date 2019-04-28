<?php
session_start();
include ("connexion.php");

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
			return $euro."€";
		}else{
			if ($cent<10) {
				$cent="0".$cent;
			}
			return $euro."€".$cent;
		}
	}
}

$req = $bdd -> query("SELECT valeur FROM constante WHERE id=1");
$donnees = $req->fetch();
$answer["limite"]=-floatval($donnees['valeur']);

$req = $bdd -> query("SELECT valeur FROM constante WHERE id=5");
$donnees = $req->fetch();
$answer["coef_forum"]=floatval($donnees['valeur']);

$req = $bdd -> query("SELECT valeur FROM constante WHERE id=6");
$donnees = $req->fetch();
$answer["limite_forum"]=floatval($donnees['valeur']);

$req = $bdd -> query("SELECT valeur FROM constante WHERE id=4");
$donnees = $req->fetch();
$answer["forum"]=floatval($donnees['valeur']);

$req = $bdd -> query("SELECT valeur FROM constante WHERE id=7");
$donnees = $req->fetch();
$answer["moy_forum"]=floatval($donnees['valeur']);

$req = $bdd -> query("SELECT valeur FROM constante WHERE id=3");
$donnees = $req->fetch();
$answer["code"]=floatval($donnees['valeur']);

$req = $bdd -> query("SELECT valeur FROM constante WHERE id=8");
$donnees = $req->fetch();
$answer["happy"]["prix"]=floatval($donnees['valeur']);

$req = $bdd -> query("SELECT valeur FROM constante WHERE id=9");
$donnees = $req->fetch();
$answer["happy"]["coef"]=floatval($donnees['valeur']);

$req = $bdd -> query("SELECT valeur FROM constante WHERE id=10");
$donnees = $req->fetch();
$answer["happy"]["bypercent"]=floatval($donnees['valeur']);

$answer["droits"]=$_SESSION["droit"];


$req = $bdd -> query("SELECT np.nom, p.datee, p.total_vente, p.id, p.total_litre FROM perm p, nom_perm np WHERE np.id=p.id_nom_perm ORDER BY p.id DESC LIMIT 1");

while ($donnees = $req->fetch())
{
    $derniere_perm["nom"]=$donnees["nom"];
    $derniere_perm["date"]=date("d/m/y",$donnees["datee"]);
    $derniere_perm["total_vente"]=$donnees["total_vente"];
    $derniere_perm["total_litre"]=$donnees["total_litre"];
    $derniere_perm["id"]=$donnees["id"];
}

$req = $bdd -> prepare("SELECT B_C, id_B_C FROM inventaire_perm WHERE id_perm = ?");

$req -> execute(array($derniere_perm["id"]));
$i=0;
$j=0;
$k=0;
while ($donnees = $req->fetch())
{
	if ($donnees["B_C"]=="B") {
		$rep = $bdd -> prepare("SELECT b.id, cu.nom, cu.type, cu.degre, b.consigne, b.prix_vente, ct.capacite, ct.type as fut_bouteille FROM boisson b, contenu cu, contenant ct WHERE b.id_contenant=ct.id and b.id_contenu=cu.id and b.id = ?");

		$rep -> execute(array($donnees["id_B_C"]));

		while ($donnees2 = $rep->fetch())
		{
			$derniere_perm["boissons"][$i]["id"]=$donnees2["id"];
			$derniere_perm["boissons"][$i]["nom"]=$donnees2["nom"];
			$derniere_perm["boissons"][$i]["type"]=$donnees2["type"];
			$derniere_perm["boissons"][$i]["degre"]=$donnees2["degre"];
			$derniere_perm["boissons"][$i]["consigne"]=$donnees2["consigne"];
			$derniere_perm["boissons"][$i]["prix_vente"]=$donnees2["prix_vente"];
			$derniere_perm["boissons"][$i]["capacite"]=$donnees2["capacite"];
			$derniere_perm["boissons"][$i]["fut_bouteille"]=$donnees2["fut_bouteille"];
			$derniere_perm["boissons"][$i]["quantite"]=0;
			$i++;
		}
	}elseif ($donnees["B_C"]=="C") {
		$rep = $bdd -> prepare("SELECT id, nom, prix_vente FROM consommable WHERE id = ?");

		$rep -> execute(array($donnees["id_B_C"]));

		while ($donnees2 = $rep->fetch())
		{
			$derniere_perm["consommables"][$j]["id"]=$donnees2["id"];
			$derniere_perm["consommables"][$j]["nom"]=$donnees2["nom"];
			$derniere_perm["consommables"][$j]["prix_vente"]=$donnees2["prix_vente"];
			$derniere_perm["consommables"][$j]["quantite"]=0;
			$j++;
		}
	}elseif ($donnees["B_C"]=="F") {
		$rep = $bdd -> prepare("SELECT b.id, cu.nom, cu.type, cu.degre, b.consigne, b.prix_vente, ct.capacite, ct.type as fut_bouteille FROM boisson b, contenu cu, contenant ct WHERE b.id_contenant=ct.id and b.id_contenu=cu.id and b.id = ?");

		$rep -> execute(array($donnees["id_B_C"]));

		while ($donnees2 = $rep->fetch())
		{
			$derniere_perm["forums"][$k]["id"]=$donnees2["id"];
			$derniere_perm["forums"][$k]["nom"]=$donnees2["nom"];
			$derniere_perm["forums"][$k]["type"]=$donnees2["type"];
			$derniere_perm["forums"][$k]["degre"]=$donnees2["degre"];
			$derniere_perm["forums"][$k]["consigne"]=$donnees2["consigne"];
			$derniere_perm["forums"][$k]["prix_vente"]=$donnees2["prix_vente"];
			$derniere_perm["forums"][$k]["capacite"]=$donnees2["capacite"];
			$derniere_perm["forums"][$k]["fut_bouteille"]=$donnees2["fut_bouteille"];
			$derniere_perm["forums"][$k]["quantite"]=0;
			$k++;
		}
	}
}
if ($i==0) {
	$derniere_perm["boissons"]=[];
}

if ($j==0) {
	$derniere_perm["consommables"]=[];
}

if ($k==0) {
	$derniere_perm["forums"]=[];
}

$req = $bdd -> query("SELECT id_user, login, prenom, nom, promo, solde, droit, type FROM user WHERE droit<>'aucun' order by promo desc");
$i=0;
while ($donnees = $req->fetch())
{

	$users[$i]["id"]=$donnees["id_user"];
    $users[$i]["login"]=$donnees["login"];
    $users[$i]["promo"]=$donnees["promo"];
    if ($donnees["nom"]=="")
    {
		$donnees["nom"]=explode(".",$donnees["login"])[1];
		$donnees["prenom"]=explode(".",$donnees["login"])[0];
	}
    $users[$i]["nom"]=$donnees["nom"];
    $users[$i]["prenom"]=$donnees["prenom"];
    $users[$i]["easy_search"]=$users[$i]["prenom"]." ".$users[$i]["nom"];
    $users[$i]["solde"]=$donnees["solde"];
	$users[$i]["type"]=$donnees["type"];
    $i++;
}

$answer["perm"]=$derniere_perm;
$answer["users"]=$users;

$answer=json_encode($answer);
echo $answer;
?>
