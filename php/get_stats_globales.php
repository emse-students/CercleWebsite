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
	#Globale

		$globale=[];

		#par dépense


		$req = $bdd -> query("SELECT u.id_user, u.login_user ,u.nom, u.prenom, u.promo_user, -a.depense-b.depense as depense, a.volume+b.volume as volume, a.alcool+b.alcool as alcool, c.perm FROM  
	(SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*ct.capacite) as volume, SUM(op.nb*ct.capacite*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type='bouteille_unique' GROUP BY u.id_user) a,  
	(SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*0.25) as volume, SUM(op.nb*0.25*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type!='bouteille_unique' GROUP BY u.id_user) b, 
	(SELECT u.id_user, COUNT(DISTINCT op.id_perm) as perm FROM user u, operation_cercle op WHERE u.id_user=op.id_user and op.B_C_A='B' GROUP BY u.id_user) c,
	user u 
	WHERE u.id_user=a.id_user and u.id_user=b.id_user and u.id_user=c.id_user ORDER BY depense desc LIMIT 20");
		$i=0;
		while ($donnees = $req->fetch()){
			$globale["data"]["depense"][$i]["classement"]=$i+1;
			$globale["data"]["depense"][$i]["id"]=$donnees["id_user"];
			if ($donnees["nom"]==""){
				$donnees["nom"]=explode(".",$donnees["login_user"])[1];
				$donnees["prenom"]=explode(".",$donnees["login_user"])[0];
			}
			$globale["data"]["depense"][$i]["nom"]=$donnees["nom"];
			$globale["data"]["depense"][$i]["prenom"]=$donnees["prenom"];
			$globale["data"]["depense"][$i]["promo"]=$donnees["promo_user"];
			$globale["data"]["depense"][$i]["depense"]=$donnees["depense"];
			$globale["data"]["depense"][$i]["volume"]=$donnees["volume"];
			$globale["data"]["depense"][$i]["alcool"]=$donnees["alcool"];
			$globale["data"]["depense"][$i]["perm"]=$donnees["perm"];
			$i++;
		}

		#par volume


		$req = $bdd -> query("SELECT u.id_user, u.login_user ,u.nom, u.prenom, u.promo_user, -a.depense-b.depense as depense, a.volume+b.volume as volume, a.alcool+b.alcool as alcool, c.perm FROM  
	(SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*ct.capacite) as volume, SUM(op.nb*ct.capacite*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type='bouteille_unique' GROUP BY u.id_user) a,  
	(SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*0.25) as volume, SUM(op.nb*0.25*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type!='bouteille_unique' GROUP BY u.id_user) b, 
	(SELECT u.id_user, COUNT(DISTINCT op.id_perm) as perm FROM user u, operation_cercle op WHERE u.id_user=op.id_user and op.B_C_A='B' GROUP BY u.id_user) c,
	user u 
	WHERE u.id_user=a.id_user and u.id_user=b.id_user and u.id_user=c.id_user ORDER BY volume desc LIMIT 20");
		$i=0;
		while ($donnees = $req->fetch()){
			$globale["data"]["volume"][$i]["classement"]=$i+1;
			$globale["data"]["volume"][$i]["id"]=$donnees["id_user"];
			if ($donnees["nom"]==""){
				$donnees["nom"]=explode(".",$donnees["login_user"])[1];
				$donnees["prenom"]=explode(".",$donnees["login_user"])[0];
			}
			$globale["data"]["volume"][$i]["nom"]=$donnees["nom"];
			$globale["data"]["volume"][$i]["prenom"]=$donnees["prenom"];
			$globale["data"]["volume"][$i]["promo"]=$donnees["promo_user"];
			$globale["data"]["volume"][$i]["depense"]=$donnees["depense"];
			$globale["data"]["volume"][$i]["volume"]=$donnees["volume"];
			$globale["data"]["volume"][$i]["alcool"]=$donnees["alcool"];
			$globale["data"]["volume"][$i]["perm"]=$donnees["perm"];
			$i++;
		}

		#par alcool


		$req = $bdd -> query("SELECT u.id_user, u.login_user ,u.nom, u.prenom, u.promo_user, -a.depense-b.depense as depense, a.volume+b.volume as volume, a.alcool+b.alcool as alcool, c.perm FROM  
	(SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*ct.capacite) as volume, SUM(op.nb*ct.capacite*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type='bouteille_unique' GROUP BY u.id_user) a,  
	(SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*0.25) as volume, SUM(op.nb*0.25*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type!='bouteille_unique' GROUP BY u.id_user) b, 
	(SELECT u.id_user, COUNT(DISTINCT op.id_perm) as perm FROM user u, operation_cercle op WHERE u.id_user=op.id_user and op.B_C_A='B' GROUP BY u.id_user) c,
	user u 
	WHERE u.id_user=a.id_user and u.id_user=b.id_user and u.id_user=c.id_user ORDER BY alcool desc LIMIT 20");
		$i=0;
		while ($donnees = $req->fetch()){
			$globale["data"]["alcool"][$i]["classement"]=$i+1;
			$globale["data"]["alcool"][$i]["id"]=$donnees["id_user"];
			if ($donnees["nom"]==""){
				$donnees["nom"]=explode(".",$donnees["login_user"])[1];
				$donnees["prenom"]=explode(".",$donnees["login_user"])[0];
			}
			$globale["data"]["alcool"][$i]["nom"]=$donnees["nom"];
			$globale["data"]["alcool"][$i]["prenom"]=$donnees["prenom"];
			$globale["data"]["alcool"][$i]["promo"]=$donnees["promo_user"];
			$globale["data"]["alcool"][$i]["depense"]=$donnees["depense"];
			$globale["data"]["alcool"][$i]["volume"]=$donnees["volume"];
			$globale["data"]["alcool"][$i]["alcool"]=$donnees["alcool"];
			$globale["data"]["alcool"][$i]["perm"]=$donnees["perm"];
			$i++;
		}

		#par perm


		$req = $bdd -> query("SELECT u.id_user, u.login_user ,u.nom, u.prenom, u.promo_user, -a.depense-b.depense as depense, a.volume+b.volume as volume, a.alcool+b.alcool as alcool, c.perm FROM  
	(SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*ct.capacite) as volume, SUM(op.nb*ct.capacite*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type='bouteille_unique' GROUP BY u.id_user) a,  
	(SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*0.25) as volume, SUM(op.nb*0.25*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type!='bouteille_unique' GROUP BY u.id_user) b, 
	(SELECT u.id_user, COUNT(DISTINCT op.id_perm) as perm FROM user u, operation_cercle op WHERE u.id_user=op.id_user and op.B_C_A='B' GROUP BY u.id_user) c,
	user u 
	WHERE u.id_user=a.id_user and u.id_user=b.id_user and u.id_user=c.id_user ORDER BY perm desc LIMIT 20");
		$i=0;
		while ($donnees = $req->fetch()){
			$globale["data"]["perm"][$i]["classement"]=$i+1;
			$globale["data"]["perm"][$i]["id"]=$donnees["id_user"];
			if ($donnees["nom"]==""){
				$donnees["nom"]=explode(".",$donnees["login_user"])[1];
				$donnees["prenom"]=explode(".",$donnees["login_user"])[0];
			}
			$globale["data"]["perm"][$i]["nom"]=$donnees["nom"];
			$globale["data"]["perm"][$i]["prenom"]=$donnees["prenom"];
			$globale["data"]["perm"][$i]["promo"]=$donnees["promo_user"];
			$globale["data"]["perm"][$i]["depense"]=$donnees["depense"];
			$globale["data"]["perm"][$i]["volume"]=$donnees["volume"];
			$globale["data"]["perm"][$i]["alcool"]=$donnees["alcool"];
			$globale["data"]["perm"][$i]["perm"]=$donnees["perm"];
			$i++;
		}

        $req = $bdd -> query("(SELECT SUM(op.nb) AS nb, cu.type FROM operation_cercle op, contenu cu, boisson b WHERE op.B_C_A='B' AND op.id_B_C=b.id AND b.id_contenu=cu.id AND cu.id!=21 AND cu.id!=25 GROUP BY cu.type) UNION
                                       (SELECT SUM(op.nb) AS nb, cu.nom AS type FROM operation_cercle op, contenu cu, boisson b WHERE op.B_C_A='B' AND op.id_B_C=b.id AND b.id_contenu=cu.id AND (cu.id=21 OR cu.id=25) GROUP BY cu.id) ORDER BY nb desc");
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

        #par dépense
        $req = $bdd -> prepare("SELECT u.id_user, u.login_user ,u.nom, u.prenom, u.promo_user, -a.depense-b.depense as depense, a.volume+b.volume as volume, a.alcool+b.alcool as alcool, c.perm FROM  
			(SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*ct.capacite) as volume, SUM(op.nb*ct.capacite*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type='bouteille_unique' and op.datee>? and op.datee<? GROUP BY u.id_user) a,  
			(SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*0.25) as volume, SUM(op.nb*0.25*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type!='bouteille_unique' and op.datee>? and op.datee<? GROUP BY u.id_user) b, 
			(SELECT u.id_user, COUNT(DISTINCT op.id_perm) as perm FROM user u, operation_cercle op WHERE u.id_user=op.id_user and op.B_C_A='B' and op.datee>? and op.datee<? GROUP BY u.id_user) c,
			user u 
			WHERE u.id_user=a.id_user and u.id_user=b.id_user and u.id_user=c.id_user ORDER BY depense desc LIMIT 20");
        $req -> execute(array($time_min,$time_max,$time_min,$time_max,$time_min,$time_max));

        $i=0;
        while ($donnees = $req->fetch()){
            $annee["data"][$j]["depense"][$i]["classement"]=$i+1;
            $annee["data"][$j]["depense"][$i]["id"]=$donnees["id_user"];
            if ($donnees["nom"]==""){
                $donnees["nom"]=explode(".",$donnees["login_user"])[1];
                $donnees["prenom"]=explode(".",$donnees["login_user"])[0];
            }
            $annee["data"][$j]["depense"][$i]["nom"]=$donnees["nom"];
            $annee["data"][$j]["depense"][$i]["prenom"]=$donnees["prenom"];
            $annee["data"][$j]["depense"][$i]["promo"]=$donnees["promo_user"];
            $annee["data"][$j]["depense"][$i]["depense"]=$donnees["depense"];
            $annee["data"][$j]["depense"][$i]["volume"]=$donnees["volume"];
            $annee["data"][$j]["depense"][$i]["alcool"]=$donnees["alcool"];
            $annee["data"][$j]["depense"][$i]["perm"]=$donnees["perm"];
            $i++;
        }
        if ($i!=0){
        	$annee['list'][$k]["id"]=$j;
            $annee['list'][$k]["name"]=$j."/".($j+1);
        	$k++;
		}

        #par volume
        $req = $bdd -> prepare("SELECT u.id_user, u.login_user ,u.nom, u.prenom, u.promo_user, -a.depense-b.depense as depense, a.volume+b.volume as volume, a.alcool+b.alcool as alcool, c.perm FROM  
			(SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*ct.capacite) as volume, SUM(op.nb*ct.capacite*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type='bouteille_unique' and op.datee>? and op.datee<? GROUP BY u.id_user) a,  
			(SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*0.25) as volume, SUM(op.nb*0.25*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type!='bouteille_unique' and op.datee>? and op.datee<? GROUP BY u.id_user) b, 
			(SELECT u.id_user, COUNT(DISTINCT op.id_perm) as perm FROM user u, operation_cercle op WHERE u.id_user=op.id_user and op.B_C_A='B' and op.datee>? and op.datee<? GROUP BY u.id_user) c,
			user u 
			WHERE u.id_user=a.id_user and u.id_user=b.id_user and u.id_user=c.id_user ORDER BY volume desc LIMIT 20");
        $req -> execute(array($time_min,$time_max,$time_min,$time_max,$time_min,$time_max));

        $i=0;
        while ($donnees = $req->fetch()){
            $annee["data"][$j]["volume"][$i]["classement"]=$i+1;
            $annee["data"][$j]["volume"][$i]["id"]=$donnees["id_user"];
            if ($donnees["nom"]==""){
                $donnees["nom"]=explode(".",$donnees["login_user"])[1];
                $donnees["prenom"]=explode(".",$donnees["login_user"])[0];
            }
            $annee["data"][$j]["volume"][$i]["nom"]=$donnees["nom"];
            $annee["data"][$j]["volume"][$i]["prenom"]=$donnees["prenom"];
            $annee["data"][$j]["volume"][$i]["promo"]=$donnees["promo_user"];
            $annee["data"][$j]["volume"][$i]["depense"]=$donnees["depense"];
            $annee["data"][$j]["volume"][$i]["volume"]=$donnees["volume"];
            $annee["data"][$j]["volume"][$i]["alcool"]=$donnees["alcool"];
            $annee["data"][$j]["volume"][$i]["perm"]=$donnees["perm"];
            $i++;
        }

        #par alcool
        $req = $bdd -> prepare("SELECT u.id_user, u.login_user ,u.nom, u.prenom, u.promo_user, -a.depense-b.depense as depense, a.volume+b.volume as volume, a.alcool+b.alcool as alcool, c.perm FROM  
			(SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*ct.capacite) as volume, SUM(op.nb*ct.capacite*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type='bouteille_unique' and op.datee>? and op.datee<? GROUP BY u.id_user) a,  
			(SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*0.25) as volume, SUM(op.nb*0.25*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type!='bouteille_unique' and op.datee>? and op.datee<? GROUP BY u.id_user) b, 
			(SELECT u.id_user, COUNT(DISTINCT op.id_perm) as perm FROM user u, operation_cercle op WHERE u.id_user=op.id_user and op.B_C_A='B' and op.datee>? and op.datee<? GROUP BY u.id_user) c,
			user u 
			WHERE u.id_user=a.id_user and u.id_user=b.id_user and u.id_user=c.id_user ORDER BY alcool desc LIMIT 20");
        $req -> execute(array($time_min,$time_max,$time_min,$time_max,$time_min,$time_max));

        $i=0;
        while ($donnees = $req->fetch()){
            $annee["data"][$j]["alcool"][$i]["classement"]=$i+1;
            $annee["data"][$j]["alcool"][$i]["id"]=$donnees["id_user"];
            if ($donnees["nom"]==""){
                $donnees["nom"]=explode(".",$donnees["login_user"])[1];
                $donnees["prenom"]=explode(".",$donnees["login_user"])[0];
            }
            $annee["data"][$j]["alcool"][$i]["nom"]=$donnees["nom"];
            $annee["data"][$j]["alcool"][$i]["prenom"]=$donnees["prenom"];
            $annee["data"][$j]["alcool"][$i]["promo"]=$donnees["promo_user"];
            $annee["data"][$j]["alcool"][$i]["depense"]=$donnees["depense"];
            $annee["data"][$j]["alcool"][$i]["volume"]=$donnees["volume"];
            $annee["data"][$j]["alcool"][$i]["alcool"]=$donnees["alcool"];
            $annee["data"][$j]["alcool"][$i]["perm"]=$donnees["perm"];
            $i++;
        }

        #par perm
        $req = $bdd -> prepare("SELECT u.id_user, u.login_user ,u.nom, u.prenom, u.promo_user, -a.depense-b.depense as depense, a.volume+b.volume as volume, a.alcool+b.alcool as alcool, c.perm FROM  
			(SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*ct.capacite) as volume, SUM(op.nb*ct.capacite*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type='bouteille_unique' and op.datee>? and op.datee<? GROUP BY u.id_user) a,  
			(SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*0.25) as volume, SUM(op.nb*0.25*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type!='bouteille_unique' and op.datee>? and op.datee<? GROUP BY u.id_user) b, 
			(SELECT u.id_user, COUNT(DISTINCT op.id_perm) as perm FROM user u, operation_cercle op WHERE u.id_user=op.id_user and op.B_C_A='B' and op.datee>? and op.datee<? GROUP BY u.id_user) c,
			user u 
			WHERE u.id_user=a.id_user and u.id_user=b.id_user and u.id_user=c.id_user ORDER BY perm desc LIMIT 20");
        $req -> execute(array($time_min,$time_max,$time_min,$time_max,$time_min,$time_max));

        $i=0;
        while ($donnees = $req->fetch()){
            $annee["data"][$j]["perm"][$i]["classement"]=$i+1;
            $annee["data"][$j]["perm"][$i]["id"]=$donnees["id_user"];
            if ($donnees["nom"]==""){
                $donnees["nom"]=explode(".",$donnees["login_user"])[1];
                $donnees["prenom"]=explode(".",$donnees["login_user"])[0];
            }
            $annee["data"][$j]["perm"][$i]["nom"]=$donnees["nom"];
            $annee["data"][$j]["perm"][$i]["prenom"]=$donnees["prenom"];
            $annee["data"][$j]["perm"][$i]["promo"]=$donnees["promo_user"];
            $annee["data"][$j]["perm"][$i]["depense"]=$donnees["depense"];
            $annee["data"][$j]["perm"][$i]["volume"]=$donnees["volume"];
            $annee["data"][$j]["perm"][$i]["alcool"]=$donnees["alcool"];
            $annee["data"][$j]["perm"][$i]["perm"]=$donnees["perm"];
            $i++;
        }

        $req = $bdd -> prepare("(SELECT SUM(op.nb) AS nb, cu.type FROM operation_cercle op, contenu cu, boisson b WHERE op.B_C_A='B' AND op.id_B_C=b.id AND b.id_contenu=cu.id AND cu.id!=21 AND cu.id!=25 and op.datee>? and op.datee<? GROUP BY cu.type) UNION
                                       (SELECT SUM(op.nb) AS nb, cu.nom AS type FROM operation_cercle op, contenu cu, boisson b WHERE op.B_C_A='B' AND op.id_B_C=b.id AND b.id_contenu=cu.id AND (cu.id=21 OR cu.id=25) and op.datee>? and op.datee<? GROUP BY cu.id) ORDER BY nb desc");
        $req -> execute(array($time_min,$time_max,$time_min,$time_max));
        $i=0;
        while ($donnees = $req->fetch()){
            $annee["data"][$j]["diagramme_biere"][$i]["key"]=$donnees["type"];
            $annee["data"][$j]["diagramme_biere"][$i]["y"]=$donnees["nb"];
            $i++;
        }
	}

    #Par promo

    $promo=[];

    $k=0;

    $promo_max = intval(date('Y'));
    $a = strtotime($promo_max."-08-20");
    if (time()>$a){
        $year_max++;
    }
    for ($j=2013;$j<$year_max;$j++){

        #par dépense
        $req = $bdd -> prepare("SELECT u.id_user, u.login_user ,u.nom, u.prenom, u.promo_user, -a.depense-b.depense as depense, a.volume+b.volume as volume, a.alcool+b.alcool as alcool, c.perm FROM  
			(SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*ct.capacite) as volume, SUM(op.nb*ct.capacite*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type='bouteille_unique' and u.promo_user = ? GROUP BY u.id_user) a,  
			(SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*0.25) as volume, SUM(op.nb*0.25*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type!='bouteille_unique' and u.promo_user = ? GROUP BY u.id_user) b, 
			(SELECT u.id_user, COUNT(DISTINCT op.id_perm) as perm FROM user u, operation_cercle op WHERE u.id_user=op.id_user and op.B_C_A='B' and u.promo_user = ? GROUP BY u.id_user) c,
			user u 
			WHERE u.id_user=a.id_user and u.id_user=b.id_user and u.id_user=c.id_user ORDER BY depense desc LIMIT 20");
        $req -> execute(array($j,$j,$j));

        $i=0;
        while ($donnees = $req->fetch()){
            $promo["data"][$j]["depense"][$i]["classement"]=$i+1;
            $promo["data"][$j]["depense"][$i]["id"]=$donnees["id_user"];
            if ($donnees["nom"]==""){
                $donnees["nom"]=explode(".",$donnees["login_user"])[1];
                $donnees["prenom"]=explode(".",$donnees["login_user"])[0];
            }
            $promo["data"][$j]["depense"][$i]["nom"]=$donnees["nom"];
            $promo["data"][$j]["depense"][$i]["prenom"]=$donnees["prenom"];
            $promo["data"][$j]["depense"][$i]["promo"]=$donnees["promo_user"];
            $promo["data"][$j]["depense"][$i]["depense"]=$donnees["depense"];
            $promo["data"][$j]["depense"][$i]["volume"]=$donnees["volume"];
            $promo["data"][$j]["depense"][$i]["alcool"]=$donnees["alcool"];
            $promo["data"][$j]["depense"][$i]["perm"]=$donnees["perm"];
            $i++;
        }
        if ($i!=0){
            $promo['list'][$k]=$j;
            $k++;
        }

        #par volume
        $req = $bdd -> prepare("SELECT u.id_user, u.login_user ,u.nom, u.prenom, u.promo_user, -a.depense-b.depense as depense, a.volume+b.volume as volume, a.alcool+b.alcool as alcool, c.perm FROM  
			(SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*ct.capacite) as volume, SUM(op.nb*ct.capacite*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type='bouteille_unique' and u.promo_user = ? GROUP BY u.id_user) a,  
			(SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*0.25) as volume, SUM(op.nb*0.25*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type!='bouteille_unique' and u.promo_user = ? GROUP BY u.id_user) b, 
			(SELECT u.id_user, COUNT(DISTINCT op.id_perm) as perm FROM user u, operation_cercle op WHERE u.id_user=op.id_user and op.B_C_A='B' and u.promo_user = ? GROUP BY u.id_user) c,
			user u 
			WHERE u.id_user=a.id_user and u.id_user=b.id_user and u.id_user=c.id_user ORDER BY volume desc LIMIT 20");
        $req -> execute(array($j,$j,$j));

        $i=0;
        while ($donnees = $req->fetch()){
            $promo["data"][$j]["volume"][$i]["classement"]=$i+1;
            $promo["data"][$j]["volume"][$i]["id"]=$donnees["id_user"];
            if ($donnees["nom"]==""){
                $donnees["nom"]=explode(".",$donnees["login_user"])[1];
                $donnees["prenom"]=explode(".",$donnees["login_user"])[0];
            }
            $promo["data"][$j]["volume"][$i]["nom"]=$donnees["nom"];
            $promo["data"][$j]["volume"][$i]["prenom"]=$donnees["prenom"];
            $promo["data"][$j]["volume"][$i]["promo"]=$donnees["promo_user"];
            $promo["data"][$j]["volume"][$i]["depense"]=$donnees["depense"];
            $promo["data"][$j]["volume"][$i]["volume"]=$donnees["volume"];
            $promo["data"][$j]["volume"][$i]["alcool"]=$donnees["alcool"];
            $promo["data"][$j]["volume"][$i]["perm"]=$donnees["perm"];
            $i++;
        }

        #par alcool
        $req = $bdd -> prepare("SELECT u.id_user, u.login_user ,u.nom, u.prenom, u.promo_user, -a.depense-b.depense as depense, a.volume+b.volume as volume, a.alcool+b.alcool as alcool, c.perm FROM  
			(SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*ct.capacite) as volume, SUM(op.nb*ct.capacite*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type='bouteille_unique' and u.promo_user = ? GROUP BY u.id_user) a,  
			(SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*0.25) as volume, SUM(op.nb*0.25*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type!='bouteille_unique' and u.promo_user = ? GROUP BY u.id_user) b, 
			(SELECT u.id_user, COUNT(DISTINCT op.id_perm) as perm FROM user u, operation_cercle op WHERE u.id_user=op.id_user and op.B_C_A='B' and u.promo_user = ? GROUP BY u.id_user) c,
			user u 
			WHERE u.id_user=a.id_user and u.id_user=b.id_user and u.id_user=c.id_user ORDER BY alcool desc LIMIT 20");
        $req -> execute(array($j,$j,$j));

        $i=0;
        while ($donnees = $req->fetch()){
            $promo["data"][$j]["alcool"][$i]["classement"]=$i+1;
            $promo["data"][$j]["alcool"][$i]["id"]=$donnees["id_user"];
            if ($donnees["nom"]==""){
                $donnees["nom"]=explode(".",$donnees["login_user"])[1];
                $donnees["prenom"]=explode(".",$donnees["login_user"])[0];
            }
            $promo["data"][$j]["alcool"][$i]["nom"]=$donnees["nom"];
            $promo["data"][$j]["alcool"][$i]["prenom"]=$donnees["prenom"];
            $promo["data"][$j]["alcool"][$i]["promo"]=$donnees["promo_user"];
            $promo["data"][$j]["alcool"][$i]["depense"]=$donnees["depense"];
            $promo["data"][$j]["alcool"][$i]["volume"]=$donnees["volume"];
            $promo["data"][$j]["alcool"][$i]["alcool"]=$donnees["alcool"];
            $promo["data"][$j]["alcool"][$i]["perm"]=$donnees["perm"];
            $i++;
        }

        #par perm
        $req = $bdd -> prepare("SELECT u.id_user, u.login_user ,u.nom, u.prenom, u.promo_user, -a.depense-b.depense as depense, a.volume+b.volume as volume, a.alcool+b.alcool as alcool, c.perm FROM  
			(SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*ct.capacite) as volume, SUM(op.nb*ct.capacite*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type='bouteille_unique' and u.promo_user = ? GROUP BY u.id_user) a,  
			(SELECT u.id_user, SUM(op.prix) as depense, SUM(op.nb*0.25) as volume, SUM(op.nb*0.25*cu.degre*0.01) as alcool FROM user u, operation_cercle op, boisson b, contenant ct, contenu cu WHERE u.id_user=op.id_user and op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type!='bouteille_unique' and u.promo_user = ? GROUP BY u.id_user) b, 
			(SELECT u.id_user, COUNT(DISTINCT op.id_perm) as perm FROM user u, operation_cercle op WHERE u.id_user=op.id_user and op.B_C_A='B' and u.promo_user = ? GROUP BY u.id_user) c,
			user u 
			WHERE u.id_user=a.id_user and u.id_user=b.id_user and u.id_user=c.id_user ORDER BY perm desc LIMIT 20");
        $req -> execute(array($j,$j,$j));

        $i=0;
        while ($donnees = $req->fetch()){
            $promo["data"][$j]["perm"][$i]["classement"]=$i+1;
            $promo["data"][$j]["perm"][$i]["id"]=$donnees["id_user"];
            if ($donnees["nom"]==""){
                $donnees["nom"]=explode(".",$donnees["login_user"])[1];
                $donnees["prenom"]=explode(".",$donnees["login_user"])[0];
            }
            $promo["data"][$j]["perm"][$i]["nom"]=$donnees["nom"];
            $promo["data"][$j]["perm"][$i]["prenom"]=$donnees["prenom"];
            $promo["data"][$j]["perm"][$i]["promo"]=$donnees["promo_user"];
            $promo["data"][$j]["perm"][$i]["depense"]=$donnees["depense"];
            $promo["data"][$j]["perm"][$i]["volume"]=$donnees["volume"];
            $promo["data"][$j]["perm"][$i]["alcool"]=$donnees["alcool"];
            $promo["data"][$j]["perm"][$i]["perm"]=$donnees["perm"];
            $i++;
        }

        $req = $bdd -> prepare("(SELECT SUM(op.nb) AS nb, cu.type FROM operation_cercle op, user u, contenu cu, boisson b WHERE u.id_user=op.id_user and u.promo_user=? AND op.B_C_A='B' AND op.id_B_C=b.id AND b.id_contenu=cu.id AND cu.id!=21 AND cu.id!=25 GROUP BY cu.type) UNION
                                       (SELECT SUM(op.nb) AS nb, cu.nom AS type FROM operation_cercle op, user u, contenu cu, boisson b WHERE u.id_user=op.id_user and u.promo_user=? AND op.B_C_A='B' AND op.id_B_C=b.id AND b.id_contenu=cu.id AND (cu.id=21 OR cu.id=25) GROUP BY cu.id) ORDER BY nb desc");
        $req -> execute(array($j,$j));
        $i=0;
        while ($donnees = $req->fetch()){
            $promo["data"][$j]["diagramme_biere"][$i]["key"]=$donnees["type"];
            $promo["data"][$j]["diagramme_biere"][$i]["y"]=$donnees["nb"];
            $i++;
        }
    }


	$answer["globale"]=$globale;
   	$answer["promo"]=$promo;
    $answer["annee"]=$annee;
	$answer=json_encode($answer);
	echo $answer;
}
?>
