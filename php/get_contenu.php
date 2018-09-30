<?php
session_start();
include ("connexion.php");



$rep = $bdd -> query("SELECT id, nom, type, degre, description FROM contenu ORDER BY nom");


$i=0;
while ($donnees = $rep->fetch())
{
	$contenus[$i]["id"]=$donnees["id"];
	$contenus[$i]["nom"]=$donnees["nom"];
	$contenus[$i]["type"]=$donnees["type"];
	$contenus[$i]["degre"]=$donnees["degre"];
	$contenus[$i]["description"]=$donnees["description"];

	$req = $bdd->prepare('SELECT ct.id, ct.nom, ct.capacite, ct.type, b.consigne FROM contenant ct, boisson b WHERE ct.id=b.id_contenant and b.id_contenu=? ');
    $req->execute(array($donnees["id"]));
	
	$j=0;
	while ($donnees2 = $req->fetch())
	{
		$contenus[$i]["contenants"][$j]["id"]=$donnees2["id"];
		$contenus[$i]["contenants"][$j]["nom"]=$donnees2["nom"];
		$contenus[$i]["contenants"][$j]["capacite"]=$donnees2["capacite"];
		$contenus[$i]["contenants"][$j]["type"]=$donnees2["type"];
		$contenus[$i]["contenants"][$j]["consigne"]=$donnees2["consigne"];
		$j++;
	}
	if ($j==0) {
		$contenus[$i]["contenants"]=[];
	}
	$i++;
}
if ($i==0) {
	$contenus=[];
}

$req = $bdd -> query("SELECT id, nom, capacite, type FROM contenant");
$i=0;
while ($donnees = $req->fetch())
{
    $contenants[$i]["nom"]=$donnees["nom"];
    $contenants[$i]["id"]=$donnees["id"];
    $contenants[$i]["capacite"]=$donnees["capacite"];
    $contenants[$i]["type"]=$donnees["type"];
    $i++;
}
if ($i==0) {
	$contenants=[];
}


$answer["contenants"]=$contenants;
$answer["contenus"]=$contenus;

$answer=json_encode($answer);
	echo $answer;
?>