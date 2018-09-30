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

$req = $bdd -> query("SELECT np.nom, p.datee, p.total_vente, p.id FROM perm p, nom_perm np WHERE np.id=p.id_nom_perm ORDER BY p.id DESC LIMIT 1");

while ($donnees = $req->fetch())
{
    $derniere_perm["nom"]=$donnees["nom"];
    $derniere_perm["date"]=date("d/m/y",$donnees["datee"]);
    $derniere_perm["total_vente"]=prix($donnees["total_vente"]);
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

$req = $bdd -> query("SELECT nom, id, annee FROM  nom_perm WHERE id<>1 and isactiv=1");
$i=0;
while ($donnees = $req->fetch())
{
    $nom_perms[$i]["nom"]=$donnees["nom"];
    $nom_perms[$i]["id"]=$donnees["id"];
    $nom_perms[$i]["annee"]=$donnees["annee"];
    $i++;
}


$rep = $bdd -> query("SELECT b.id, cu.nom, cu.type, cu.degre, b.consigne, b.prix_vente, ct.capacite, ct.nom as nom_contenant, ct.type as fut_bouteille FROM boisson b, contenu cu, contenant ct WHERE b.id_contenant=ct.id and b.id_contenu=cu.id ORDER BY nom");


$i=0;
while ($donnees2 = $rep->fetch())
{
	$boissons[$i]["id"]=$donnees2["id"];
	$boissons[$i]["nom"]=$donnees2["nom"];
	$boissons[$i]["type"]=$donnees2["type"];
	$boissons[$i]["degre"]=$donnees2["degre"];
	$boissons[$i]["consigne"]=$donnees2["consigne"];
	$boissons[$i]["prix_vente"]=$donnees2["prix_vente"];
	$boissons[$i]["nom_contenant"]=$donnees2["nom_contenant"];
	$boissons[$i]["capacite"]=$donnees2["capacite"];
	$boissons[$i]["fut_bouteille"]=$donnees2["fut_bouteille"];
	$i++;
}

$rep = $bdd -> query("SELECT id, nom, type, degre, description FROM contenu ORDER BY nom");


$i=0;
while ($donnees = $rep->fetch())
{
	$contenus[$i]["id"]=$donnees["id"];
	$contenus[$i]["nom"]=$donnees["nom"];
	$contenus[$i]["type"]=$donnees["type"];
	$contenus[$i]["degre"]=$donnees["degre"];
	$contenus[$i]["description"]=$donnees["description"];

	$req = $bdd->prepare('SELECT ct.id, ct.nom, ct.capacite, ct.type, b.consigne, b.id as id_boisson, b.prix_vente FROM contenant ct, boisson b WHERE ct.id=b.id_contenant and b.id_contenu=? ');
    $req->execute(array($donnees["id"]));

	$j=0;
	while ($donnees2 = $req->fetch())
	{
		$contenus[$i]["contenants"][$j]["id"]=$donnees2["id"];
		$contenus[$i]["contenants"][$j]["nom"]=$donnees2["nom"];
		$contenus[$i]["contenants"][$j]["capacite"]=$donnees2["capacite"];
		$contenus[$i]["contenants"][$j]["type"]=$donnees2["type"];
		$contenus[$i]["contenants"][$j]["consigne"]=$donnees2["consigne"];
		$contenus[$i]["contenants"][$j]["id_boisson"]=$donnees2["id_boisson"];
		$contenus[$i]["contenants"][$j]["prix_vente"]=$donnees2["prix_vente"];
		$j++;
	}
	$i++;
}
if ($i==0) {
	$contenus=[];
}

$rep = $bdd -> query("SELECT id, nom, prix_vente FROM consommable ORDER BY nom");

$j=0;
while ($donnees2 = $rep->fetch())
{
	$consommables[$j]["id"]=$donnees2["id"];
	$consommables[$j]["nom"]=$donnees2["nom"];
	$consommables[$j]["prix_vente"]=$donnees2["prix_vente"];
	$j++;
}
$answer["contenus"]=$contenus;
$answer["derniere_perm"]=$derniere_perm;
$answer["nom_perms"]=$nom_perms;
$answer["boissons"]=$boissons;
$answer["consommables"]=$consommables;
$answer=json_encode($answer);
echo $answer;
?>
