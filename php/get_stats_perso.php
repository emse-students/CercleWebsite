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
    if (!isset($_GET["id"]) or $_GET["id"]==0){
        $_GET["id"]=$_SESSION["id_cercle"];
    }
	#Globale

    $globale=[];

    $req = $bdd -> prepare("SELECT u.id_user, u.nom, u.prenom, u.login_user, u.promo_user, -a.depense-b.depense as depense, a.volume+b.volume as volume, a.alcool+b.alcool as alcool, c.perm FROM  
			(SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*ct.capacite) as volume, SUM(op.nb*ct.capacite*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user= ? and u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type='bouteille_unique'  GROUP BY u.id_user) a,  
			(SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*0.25) as volume, SUM(op.nb*0.25*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user= ? and u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type!='bouteille_unique' GROUP BY u.id_user) b, 
			(SELECT u.id_user, COUNT(DISTINCT op.id_perm) as perm FROM user u, operation_cercle op WHERE u.id_user= ? and u.id_user=op.id_user and op.B_C_A='B' GROUP BY u.id_user) c,
			user u 
			WHERE u.id_user=a.id_user and u.id_user=b.id_user and u.id_user=c.id_user ");
    $req -> execute(array($_GET["id"],$_GET["id"],$_GET["id"]));


    $donnees = $req->fetch();
    $globale["data"]["id"]=$donnees["id_user"];
    if ($donnees["nom"]==""){
        $donnees["nom"]=explode(".",$donnees["login_user"])[1];
        $donnees["prenom"]=explode(".",$donnees["login_user"])[0];
        }
    $globale["data"]["nom"]=$donnees["nom"];
    $globale["data"]["prenom"]=$donnees["prenom"];
    $globale["data"]["promo"]=$donnees["promo_user"];
    $globale["data"]["depense"]=$donnees["depense"];
    $globale["data"]["volume"]=$donnees["volume"];
    $globale["data"]["alcool"]=$donnees["alcool"];
    $globale["data"]["perm"]=$donnees["perm"];


		#Rang par dépense

    $bdd->exec('SET @num :=0, @rank := 1, @prev :=NULL');


    $req = $bdd -> prepare("SELECT f.id_user, f.Rank, f.depense FROM(
    SELECT @num := @num +1 AS row, @rank := if(@prev != depense, @num, @rank) AS Rank, e.id_user, @prev := depense AS depense FROM (
    SELECT u.id_user, u.login_user ,u.nom, u.prenom, u.promo_user, -a.depense-b.depense as depense, a.volume+b.volume as volume, a.alcool+b.alcool as alcool, c.perm FROM
    (SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*ct.capacite) as volume, SUM(op.nb*ct.capacite*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type='bouteille_unique' GROUP BY u.id_user) a,
	(SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*0.25) as volume, SUM(op.nb*0.25*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type!='bouteille_unique' GROUP BY u.id_user) b,
	(SELECT u.id_user, COUNT(DISTINCT op.id_perm) as perm FROM user u, operation_cercle op WHERE u.id_user=op.id_user and op.B_C_A='B' GROUP BY u.id_user) c,
	user u
	WHERE u.id_user=a.id_user and u.id_user=b.id_user and u.id_user=c.id_user ORDER BY depense desc) e ) f WHERE f.id_user=?");
    $req -> execute(array($_GET["id"]));
    $donnees = $req->fetch();
    $globale["rang_depense_g"]=$donnees["Rank"];

    $bdd->exec('SET @num :=0, @rank := 1, @prev :=NULL');


    $req = $bdd -> prepare("SELECT f.id_user, f.Rank, f.depense FROM(
    SELECT @num := @num +1 AS row, @rank := if(@prev != depense, @num, @rank) AS Rank, e.id_user, @prev := depense AS depense FROM (
    SELECT u.id_user, u.login_user ,u.nom, u.prenom, u.promo_user, -a.depense-b.depense as depense, a.volume+b.volume as volume, a.alcool+b.alcool as alcool, c.perm FROM
    (SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*ct.capacite) as volume, SUM(op.nb*ct.capacite*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type='bouteille_unique' and u.promo_user = ? GROUP BY u.id_user) a,
	(SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*0.25) as volume, SUM(op.nb*0.25*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type!='bouteille_unique' and u.promo_user = ? GROUP BY u.id_user) b,
	(SELECT u.id_user, COUNT(DISTINCT op.id_perm) as perm FROM user u, operation_cercle op WHERE u.id_user=op.id_user and op.B_C_A='B' and u.promo_user = ? GROUP BY u.id_user) c,
	user u
	WHERE u.id_user=a.id_user and u.id_user=b.id_user and u.id_user=c.id_user ORDER BY depense desc) e ) f WHERE f.id_user=?");
    $req -> execute(array($globale["data"]["promo"],$globale["data"]["promo"],$globale["data"]["promo"],$_GET["id"]));
    $donnees = $req->fetch();
    $globale["rang_depense_p"]=$donnees["Rank"];

    #Rang par volume

    $bdd->exec('SET @num :=0, @rank := 1, @prev :=NULL');


    $req = $bdd -> prepare("SELECT f.id_user, f.Rank, f.volume FROM(
    SELECT @num := @num +1 AS row, @rank := if(@prev != volume, @num, @rank) AS Rank, e.id_user, @prev := volume AS volume FROM (
    SELECT u.id_user, u.login_user ,u.nom, u.prenom, u.promo_user, -a.depense-b.depense as depense, a.volume+b.volume as volume, a.alcool+b.alcool as alcool, c.perm FROM
    (SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*ct.capacite) as volume, SUM(op.nb*ct.capacite*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type='bouteille_unique' GROUP BY u.id_user) a,
	(SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*0.25) as volume, SUM(op.nb*0.25*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type!='bouteille_unique' GROUP BY u.id_user) b,
	(SELECT u.id_user, COUNT(DISTINCT op.id_perm) as perm FROM user u, operation_cercle op WHERE u.id_user=op.id_user and op.B_C_A='B' GROUP BY u.id_user) c,
	user u
	WHERE u.id_user=a.id_user and u.id_user=b.id_user and u.id_user=c.id_user ORDER BY volume desc) e ) f WHERE f.id_user=?");
    $req -> execute(array($_GET["id"]));
    $donnees = $req->fetch();
    $globale["rang_volume_g"]=$donnees["Rank"];

    $bdd->exec('SET @num :=0, @rank := 1, @prev :=NULL');

    $req = $bdd -> prepare("SELECT f.id_user, f.Rank, f.volume FROM(
    SELECT @num := @num +1 AS row, @rank := if(@prev != volume, @num, @rank) AS Rank, e.id_user, @prev := volume AS volume FROM (
    SELECT u.id_user, u.login_user ,u.nom, u.prenom, u.promo_user, -a.depense-b.depense as depense, a.volume+b.volume as volume, a.alcool+b.alcool as alcool, c.perm FROM
    (SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*ct.capacite) as volume, SUM(op.nb*ct.capacite*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type='bouteille_unique' and u.promo_user = ? GROUP BY u.id_user) a,
	(SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*0.25) as volume, SUM(op.nb*0.25*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type!='bouteille_unique' and u.promo_user = ? GROUP BY u.id_user) b,
	(SELECT u.id_user, COUNT(DISTINCT op.id_perm) as perm FROM user u, operation_cercle op WHERE u.id_user=op.id_user and op.B_C_A='B' and u.promo_user = ? GROUP BY u.id_user) c,
	user u
	WHERE u.id_user=a.id_user and u.id_user=b.id_user and u.id_user=c.id_user ORDER BY volume desc) e ) f WHERE f.id_user=?");
    $req -> execute(array($globale["data"]["promo"],$globale["data"]["promo"],$globale["data"]["promo"],$_GET["id"]));
    $donnees = $req->fetch();
    $globale["rang_volume_p"]=$donnees["Rank"];

    #Rang par alcool

    $bdd->exec('SET @num :=0, @rank := 1, @prev :=NULL');


    $req = $bdd -> prepare("SELECT f.id_user, f.Rank, f.alcool FROM(
    SELECT @num := @num +1 AS row, @rank := if(@prev != alcool, @num, @rank) AS Rank, e.id_user, @prev := alcool AS alcool FROM (
    SELECT u.id_user, u.login_user ,u.nom, u.prenom, u.promo_user, -a.depense-b.depense as depense, a.volume+b.volume as volume, a.alcool+b.alcool as alcool, c.perm FROM
    (SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*ct.capacite) as volume, SUM(op.nb*ct.capacite*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type='bouteille_unique' GROUP BY u.id_user) a,
	(SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*0.25) as volume, SUM(op.nb*0.25*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type!='bouteille_unique' GROUP BY u.id_user) b,
	(SELECT u.id_user, COUNT(DISTINCT op.id_perm) as perm FROM user u, operation_cercle op WHERE u.id_user=op.id_user and op.B_C_A='B' GROUP BY u.id_user) c,
	user u
	WHERE u.id_user=a.id_user and u.id_user=b.id_user and u.id_user=c.id_user ORDER BY alcool desc) e ) f WHERE f.id_user=?");
    $req -> execute(array($_GET["id"]));
    $donnees = $req->fetch();
    $globale["rang_alcool_g"]=$donnees["Rank"];

    $bdd->exec('SET @num :=0, @rank := 1, @prev :=NULL');

    $req = $bdd -> prepare("SELECT f.id_user, f.Rank, f.alcool FROM(
    SELECT @num := @num +1 AS row, @rank := if(@prev != alcool, @num, @rank) AS Rank, e.id_user, @prev := alcool AS alcool FROM (
    SELECT u.id_user, u.login_user ,u.nom, u.prenom, u.promo_user, -a.depense-b.depense as depense, a.volume+b.volume as volume, a.alcool+b.alcool as alcool, c.perm FROM
    (SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*ct.capacite) as volume, SUM(op.nb*ct.capacite*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type='bouteille_unique' and u.promo_user = ? GROUP BY u.id_user) a,
	(SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*0.25) as volume, SUM(op.nb*0.25*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type!='bouteille_unique' and u.promo_user = ? GROUP BY u.id_user) b,
	(SELECT u.id_user, COUNT(DISTINCT op.id_perm) as perm FROM user u, operation_cercle op WHERE u.id_user=op.id_user and op.B_C_A='B' and u.promo_user = ? GROUP BY u.id_user) c,
	user u
	WHERE u.id_user=a.id_user and u.id_user=b.id_user and u.id_user=c.id_user ORDER BY alcool desc) e ) f WHERE f.id_user=?");
    $req -> execute(array($globale["data"]["promo"],$globale["data"]["promo"],$globale["data"]["promo"],$_GET["id"]));
    $donnees = $req->fetch();
    $globale["rang_alcool_p"]=$donnees["Rank"];

    #Rang par nb de perm

    $bdd->exec('SET @num :=0, @rank := 1, @prev :=NULL');


    $req = $bdd -> prepare("SELECT f.id_user, f.Rank, f.perm FROM(
    SELECT @num := @num +1 AS row, @rank := if(@prev != perm, @num, @rank) AS Rank, e.id_user, @prev := perm AS perm FROM (
    SELECT u.id_user, u.login_user ,u.nom, u.prenom, u.promo_user, -a.depense-b.depense as depense, a.volume+b.volume as volume, a.alcool+b.alcool as alcool, c.perm FROM
    (SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*ct.capacite) as volume, SUM(op.nb*ct.capacite*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type='bouteille_unique' GROUP BY u.id_user) a,
	(SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*0.25) as volume, SUM(op.nb*0.25*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type!='bouteille_unique' GROUP BY u.id_user) b,
	(SELECT u.id_user, COUNT(DISTINCT op.id_perm) as perm FROM user u, operation_cercle op WHERE u.id_user=op.id_user and op.B_C_A='B' GROUP BY u.id_user) c,
	user u
	WHERE u.id_user=a.id_user and u.id_user=b.id_user and u.id_user=c.id_user ORDER BY perm desc) e ) f WHERE f.id_user=?");
    $req -> execute(array($_GET["id"]));
    $donnees = $req->fetch();
    $globale["rang_perm_g"]=$donnees["Rank"];

    $bdd->exec('SET @num :=0, @rank := 1, @prev :=NULL');

    $req = $bdd -> prepare("SELECT f.id_user, f.Rank, f.perm FROM(
    SELECT @num := @num +1 AS row, @rank := if(@prev != perm, @num, @rank) AS Rank, e.id_user, @prev := perm AS perm FROM (
    SELECT u.id_user, u.login_user ,u.nom, u.prenom, u.promo_user, -a.depense-b.depense as depense, a.volume+b.volume as volume, a.alcool+b.alcool as alcool, c.perm FROM
    (SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*ct.capacite) as volume, SUM(op.nb*ct.capacite*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type='bouteille_unique' and u.promo_user = ? GROUP BY u.id_user) a,
	(SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*0.25) as volume, SUM(op.nb*0.25*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type!='bouteille_unique' and u.promo_user = ? GROUP BY u.id_user) b,
	(SELECT u.id_user, COUNT(DISTINCT op.id_perm) as perm FROM user u, operation_cercle op WHERE u.id_user=op.id_user and op.B_C_A='B' and u.promo_user = ? GROUP BY u.id_user) c,
	user u
	WHERE u.id_user=a.id_user and u.id_user=b.id_user and u.id_user=c.id_user ORDER BY perm desc) e ) f WHERE f.id_user=?");
    $req -> execute(array($globale["data"]["promo"],$globale["data"]["promo"],$globale["data"]["promo"],$_GET["id"]));
    $donnees = $req->fetch();
    $globale["rang_perm_p"]=$donnees["Rank"];


    $req = $bdd -> prepare("(SELECT SUM(op.nb) AS nb, cu.type FROM operation_cercle op, contenu cu, boisson b WHERE op.id_user=? AND op.B_C_A='B' AND op.id_B_C=b.id AND b.id_contenu=cu.id AND cu.id!=21 AND cu.id!=25 GROUP BY cu.type) UNION
                                       (SELECT SUM(op.nb) AS nb, cu.nom AS type FROM operation_cercle op, contenu cu, boisson b WHERE op.id_user=? AND op.B_C_A='B' AND op.id_B_C=b.id AND b.id_contenu=cu.id AND (cu.id=21 OR cu.id=25) GROUP BY cu.id) ORDER BY nb desc");
    $req -> execute(array($_GET["id"],$_GET["id"]));
    $i=0;
    while ($donnees = $req->fetch()){
        $globale["diagramme_biere"][$i]["key"]=$donnees["type"];
        $globale["diagramme_biere"][$i]["y"]=$donnees["nb"];
        $i++;
    }







    #Par année

    $annee=[];

	$k=0;

   	$year_max = intval(date('Y'));
   	$a = strtotime($year_max."-08-20");
   	if (time()>$a){
   		$year_max++;
	}
	for ($j=2013;$j<$year_max;$j++){
   		//echo $j."<br>";
   		$time_min=strtotime($j."-08-20");
        //echo "time min :".$time_min."<br>";
        $time_max=strtotime(($j+1)."-07-10");
        //echo "time max :".$time_max."<br>";

        $req = $bdd -> prepare("SELECT u.id_user, u.login_user ,u.nom, u.prenom, u.promo_user, -a.depense-b.depense as depense, a.volume+b.volume as volume, a.alcool+b.alcool as alcool, c.perm FROM  
			(SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*ct.capacite) as volume, SUM(op.nb*ct.capacite*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user= ? and u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type='bouteille_unique' and op.datee>? and op.datee<? GROUP BY u.id_user) a,  
			(SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*0.25) as volume, SUM(op.nb*0.25*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user= ? and u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type!='bouteille_unique' and op.datee>? and op.datee<? GROUP BY u.id_user) b, 
			(SELECT u.id_user, COUNT(DISTINCT op.id_perm) as perm FROM user u, operation_cercle op WHERE u.id_user= ? and u.id_user=op.id_user and op.B_C_A='B' and op.datee>? and op.datee<? GROUP BY u.id_user) c,
			user u 
			WHERE u.id_user=a.id_user and u.id_user=b.id_user and u.id_user=c.id_user");
        $req -> execute(array($_GET["id"],$time_min,$time_max,$_GET["id"],$time_min,$time_max,$_GET["id"],$time_min,$time_max));

        $i=0;
        while($donnees = $req->fetch()) {
            $annee["data"][$j]["id"] = $donnees["id_user"];
            if ($donnees["nom"] == "") {
                $donnees["nom"] = explode(".", $donnees["login_user"])[1];
                $donnees["prenom"] = explode(".", $donnees["login_user"])[0];
            }
            $annee["data"][$j]["nom"] = $donnees["nom"];
            $annee["data"][$j]["prenom"] = $donnees["prenom"];
            $annee["data"][$j]["promo"] = $donnees["promo_user"];
            $annee["data"][$j]["depense"] = $donnees["depense"];
            $annee["data"][$j]["volume"] = $donnees["volume"];
            $annee["data"][$j]["alcool"] = $donnees["alcool"];
            $annee["data"][$j]["perm"] = $donnees["perm"];
            $i=1;
        }

        if ($i!=0){
        	$annee['list'][$k]["id"]=$j;
            $annee['list'][$k]["name"]=$j."/".($j+1);
        	$k++;


        #Rang par dépense

        $bdd->exec('SET @num :=0, @rank := 1, @prev :=NULL');
        $req = $bdd -> prepare("SELECT f.id_user, f.Rank, f.depense FROM(
    SELECT @num := @num +1 AS row, @rank := if(@prev != depense, @num, @rank) AS Rank, e.id_user, @prev := depense AS depense FROM (
    SELECT u.id_user, u.login_user ,u.nom, u.prenom, u.promo_user, -a.depense-b.depense as depense, a.volume+b.volume as volume, a.alcool+b.alcool as alcool, c.perm FROM
    (SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*ct.capacite) as volume, SUM(op.nb*ct.capacite*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type='bouteille_unique' and op.datee>? and op.datee<? GROUP BY u.id_user) a,
	(SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*0.25) as volume, SUM(op.nb*0.25*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type!='bouteille_unique' and op.datee>? and op.datee<? GROUP BY u.id_user) b,
	(SELECT u.id_user, COUNT(DISTINCT op.id_perm) as perm FROM user u, operation_cercle op WHERE u.id_user=op.id_user and op.B_C_A='B' and op.datee>? and op.datee<? GROUP BY u.id_user) c,
	user u
	WHERE u.id_user=a.id_user and u.id_user=b.id_user and u.id_user=c.id_user ORDER BY depense desc) e ) f WHERE f.id_user=?");
        $req -> execute(array($time_min,$time_max,$time_min,$time_max,$time_min,$time_max,$_GET["id"]));
        $donnees = $req->fetch();
        $annee["data"][$j]["rang_depense"]=$donnees["Rank"];

        #Rang par volume

        $bdd->exec('SET @num :=0, @rank := 1, @prev :=NULL');
        $req = $bdd -> prepare("SELECT f.id_user, f.Rank, f.volume FROM(
    SELECT @num := @num +1 AS row, @rank := if(@prev != volume, @num, @rank) AS Rank, e.id_user, @prev := volume AS volume FROM (
    SELECT u.id_user, u.login_user ,u.nom, u.prenom, u.promo_user, -a.depense-b.depense as depense, a.volume+b.volume as volume, a.alcool+b.alcool as alcool, c.perm FROM
    (SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*ct.capacite) as volume, SUM(op.nb*ct.capacite*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type='bouteille_unique' and op.datee>? and op.datee<? GROUP BY u.id_user) a,
	(SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*0.25) as volume, SUM(op.nb*0.25*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type!='bouteille_unique' and op.datee>? and op.datee<? GROUP BY u.id_user) b,
	(SELECT u.id_user, COUNT(DISTINCT op.id_perm) as perm FROM user u, operation_cercle op WHERE u.id_user=op.id_user and op.B_C_A='B' and op.datee>? and op.datee<? GROUP BY u.id_user) c,
	user u
	WHERE u.id_user=a.id_user and u.id_user=b.id_user and u.id_user=c.id_user ORDER BY volume  desc) e ) f WHERE f.id_user=?");
        $req -> execute(array($time_min,$time_max,$time_min,$time_max,$time_min,$time_max,$_GET["id"]));
        $donnees = $req->fetch();
        $annee["data"][$j]["rang_volume"]=$donnees["Rank"];

        #Rang par alcool

        $bdd->exec('SET @num :=0, @rank := 1, @prev :=NULL');
        $req = $bdd -> prepare("SELECT f.id_user, f.Rank, f.alcool FROM(
    SELECT @num := @num +1 AS row, @rank := if(@prev != alcool, @num, @rank) AS Rank, e.id_user, @prev := alcool AS alcool FROM (
    SELECT u.id_user, u.login_user ,u.nom, u.prenom, u.promo_user, -a.depense-b.depense as depense, a.volume+b.volume as volume, a.alcool+b.alcool as alcool, c.perm FROM
    (SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*ct.capacite) as volume, SUM(op.nb*ct.capacite*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type='bouteille_unique' and op.datee>? and op.datee<? GROUP BY u.id_user) a,
	(SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*0.25) as volume, SUM(op.nb*0.25*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type!='bouteille_unique' and op.datee>? and op.datee<? GROUP BY u.id_user) b,
	(SELECT u.id_user, COUNT(DISTINCT op.id_perm) as perm FROM user u, operation_cercle op WHERE u.id_user=op.id_user and op.B_C_A='B' and op.datee>? and op.datee<? GROUP BY u.id_user) c,
	user u
	WHERE u.id_user=a.id_user and u.id_user=b.id_user and u.id_user=c.id_user ORDER BY alcool  desc) e ) f WHERE f.id_user=?");
        $req -> execute(array($time_min,$time_max,$time_min,$time_max,$time_min,$time_max,$_GET["id"]));
        $donnees = $req->fetch();
        $annee["data"][$j]["rang_alcool"]=$donnees["Rank"];

        #Rang par nb de perm

        $bdd->exec('SET @num :=0, @rank := 1, @prev :=NULL');
        $req = $bdd -> prepare("SELECT f.id_user, f.Rank, f.perm FROM(
    SELECT @num := @num +1 AS row, @rank := if(@prev != perm, @num, @rank) AS Rank, e.id_user, @prev := perm AS perm FROM (
    SELECT u.id_user, u.login_user ,u.nom, u.prenom, u.promo_user, -a.depense-b.depense as depense, a.volume+b.volume as volume, a.alcool+b.alcool as alcool, c.perm FROM
    (SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*ct.capacite) as volume, SUM(op.nb*ct.capacite*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type='bouteille_unique' and op.datee>? and op.datee<? GROUP BY u.id_user) a,
	(SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*0.25) as volume, SUM(op.nb*0.25*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type!='bouteille_unique' and op.datee>? and op.datee<? GROUP BY u.id_user) b,
	(SELECT u.id_user, COUNT(DISTINCT op.id_perm) as perm FROM user u, operation_cercle op WHERE u.id_user=op.id_user and op.B_C_A='B' and op.datee>? and op.datee<? GROUP BY u.id_user) c,
	user u
	WHERE u.id_user=a.id_user and u.id_user=b.id_user and u.id_user=c.id_user ORDER BY perm  desc) e ) f WHERE f.id_user=?");
        $req -> execute(array($time_min,$time_max,$time_min,$time_max,$time_min,$time_max,$_GET["id"]));
        $donnees = $req->fetch();
        $annee["data"][$j]["rang_perm"]=$donnees["Rank"];
        }

        $req = $bdd -> prepare("(SELECT SUM(op.nb) AS nb, cu.type FROM operation_cercle op, contenu cu, boisson b WHERE op.id_user=? AND op.B_C_A='B' AND op.id_B_C=b.id AND b.id_contenu=cu.id AND cu.id!=21 AND cu.id!=25 and op.datee>? and op.datee<? GROUP BY cu.type) UNION
                                       (SELECT SUM(op.nb) AS nb, cu.nom AS type FROM operation_cercle op, contenu cu, boisson b WHERE op.id_user=? AND op.B_C_A='B' AND op.id_B_C=b.id AND b.id_contenu=cu.id AND (cu.id=21 OR cu.id=25) and op.datee>? and op.datee<? GROUP BY cu.id)  ORDER BY nb desc");
        $req -> execute(array($_GET["id"],$time_min,$time_max,$_GET["id"],$time_min,$time_max));
        $i=0;
        while ($donnees = $req->fetch()){
            $annee["data"][$j]["diagramme_biere"][$i]["key"]=$donnees["type"];
            $annee["data"][$j]["diagramme_biere"][$i]["y"]=$donnees["nb"];
            $i++;
        }
	}


    $answer['globale']=$globale;
    $answer['annee']=$annee;

	$answer=json_encode($answer);
	echo $answer;
}
?>
