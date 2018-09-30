<?php
session_start();
include ("connexion.php");




$req = $bdd -> query("SELECT np.nom, p.datee, p.total_vente, p.id, p.total_litre FROM perm p, nom_perm np WHERE np.id=p.id_nom_perm ORDER BY p.id DESC LIMIT 240");
$i=0;
while ($donnees = $req->fetch())
{
    $answer[$i]["nom"]=$donnees["nom"];
    $answer[$i]["date"]=date("d/m/y",$donnees["datee"]);
    $answer[$i]["total_vente"]=$donnees["total_vente"];
    $answer[$i]["total_litre"]=$donnees["total_litre"];
    $answer[$i]["id"]=$donnees["id"];
		$i++;
}



$answer=json_encode($answer);
echo $answer;
?>
