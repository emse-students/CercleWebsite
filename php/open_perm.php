<?php
session_start();
include ("connexion.php");

include("validation_droits.php");

$req = $bdd -> query("SELECT np.nom, p.datee, p.total_vente, p.id FROM perm p, nom_perm np WHERE np.id=p.id_nom_perm ORDER BY p.id DESC LIMIT 1");

while ($donnees = $req->fetch())
{
    $_SESSION["perm"]["nom"]=$donnees["nom"];
    $_SESSION["perm"]["date"]=date("d/m/y",$donnees["datee"]);
    $_SESSION["perm"]["total_vente"]=$donnees["total_vente"];
    $_SESSION["perm"]["id"]=$donnees["id"];
}

header("location: ../perm.php");


?>

