<?php 
session_start();
include ("connexion.php");

$req = $bdd -> query("SELECT nom, id, annee FROM  nom_perm WHERE id<>1 and isactiv=1");
$i=0;
while ($donnees = $req->fetch())
{
    $nom_perms[$i]["nom"]=$donnees["nom"];
    $nom_perms[$i]["id"]=$donnees["id"];
    $nom_perms[$i]["annee"]=$donnees["annee"];
    $rep = $bdd -> prepare("SELECT u.id_user, u.prenom, u.nom, u.login_user FROM user u, membre_perm mp WHERE mp.id_user=u.id_user and id_nom_perm=?");
    $rep->execute(array($donnees["id"]));
	$j=0;
	while ($donnees2 = $rep->fetch())
	{
	    $nom_perms[$i]["membres"][$j]["id"]=$donnees2["id_user"];
	    if ($donnees2["nom"]==""){
			$donnees2["nom"]=explode(".",$donnees2["login_user"])[1];
			$donnees2["prenom"]=explode(".",$donnees2["login_user"])[0];
		}
	    $nom_perms[$i]["membres"][$j]["prenom"]=$donnees2["prenom"];
	    $nom_perms[$i]["membres"][$j]["nom"]=$donnees2["nom"];
	    $j++;
	}
	if ($j==0) {
		$nom_perms[$i]["membres"]=[];
	}
    $i++;
}

$answer=json_encode($nom_perms);
echo $answer;
?>