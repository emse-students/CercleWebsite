<?php
session_start();
include ("connexion.php");



$req = $bdd -> prepare("SELECT SUM(o.nb), SUM(o.prix), o.id_B_C, cu.id as id_cu, cu.nom, ct.id as id_ct, ct.nom as contenant, ct.capacite, ct.type FROM operation_cercle o, boisson b, contenu cu, contenant ct WHERE o.id_perm=? AND o.B_C_A='B' AND o.id_B_C=b.id AND b.id_contenu=cu.id AND b.id_contenant=ct.id GROUP BY o.id_B_C  ");
$req -> execute(array($_GET["id"]));
$i=0;
while ($donnees = $req->fetch())
{

    $answer[$i]["id"]=$donnees["id_B_C"];
    $answer[$i]["type"]="B";
    $answer[$i]["nom"]=$donnees["nom"];
    $answer[$i]["nb"]=$donnees["SUM(o.nb)"];
    $answer[$i]["prix"]=-$donnees["SUM(o.prix)"];
    $answer[$i]["id_contenu"]=$donnees["id_cu"];
    $answer[$i]["contenant"]=$donnees["contenant"];
    $answer[$i]["id_contenant"]=$donnees["id_ct"];
    if ($donnees["type"]=="bouteille_unique") {
      $answer[$i]["total_litre"]=$donnees["SUM(o.nb)"]*$donnees["capacite"];
    }else{
      $answer[$i]["total_litre"]=$donnees["SUM(o.nb)"]*0.25;
    }
		$i++;
}

$req = $bdd -> prepare("SELECT SUM(o.nb), SUM(o.prix), o.id_B_C, cu.id as id_cu, cu.nom, ct.id as id_ct, ct.nom as contenant, ct.capacite, ct.type FROM operation_cercle o, boisson b, contenu cu, contenant ct WHERE o.id_perm=? AND o.B_C_A='F' AND o.id_B_C=b.id AND b.id_contenu=cu.id AND b.id_contenant=ct.id GROUP BY o.id_B_C  ");
$req -> execute(array($_GET["id"]));
while ($donnees = $req->fetch())
{

    $answer[$i]["id"]=$donnees["id_B_C"];
    $answer[$i]["type"]="F";
    $answer[$i]["nom"]=$donnees["nom"];
    $answer[$i]["nb"]=$donnees["SUM(o.nb)"];
    $answer[$i]["prix"]=-$donnees["SUM(o.prix)"];
    $answer[$i]["id_contenu"]=$donnees["id_cu"];
    $answer[$i]["contenant"]=$donnees["contenant"];
    $answer[$i]["id_contenant"]=$donnees["id_ct"];
    if ($donnees["type"]=="bouteille_unique") {
      $answer[$i]["total_litre"]=$donnees["SUM(o.nb)"]*$donnees["capacite"];
    }else{
      $answer[$i]["total_litre"]=$donnees["SUM(o.nb)"]*0.25;
    }
		$i++;
}

$req = $bdd -> prepare("SELECT SUM(o.nb), SUM(o.prix), o.id_B_C, c.nom FROM operation_cercle o, consommable c WHERE o.id_perm=? AND o.B_C_A='C' AND o.id_B_C=c.id GROUP BY o.id_B_C  ");
$req -> execute(array($_GET["id"]));

while ($donnees = $req->fetch())
{

    $answer[$i]["id"]=$donnees["id_B_C"];
    $answer[$i]["type"]="C";
    $answer[$i]["nom"]=$donnees["nom"];
    $answer[$i]["nb"]=$donnees["SUM(o.nb)"];
    $answer[$i]["prix"]=-$donnees["SUM(o.prix)"];
		$i++;
}



$answer=json_encode($answer);
echo $answer;
?>
