<?php
session_start();
include ("connexion.php");



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
if ($i==0) {
	$boissons=[];
}

$answer=json_encode($boissons);
	echo $answer;
?>