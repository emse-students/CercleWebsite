<?php
//$starttt = microtime(true);
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

if (isset($_SESSION["id_cercle"]) AND $_SESSION["droit"]!="aucun")
{
	#Globale

		$globale=[];

		$promo=[];

		$req = $bdd -> query("SELECT * FROM stats");
		$i=0;
		while ($donnees = $req->fetch()){
			$globale[$i]["id"]=$donnees["id_user"];
			$globale[$i]["nom"]=$donnees["nom"];
			$globale[$i]["prenom"]=$donnees["prenom"];
			$globale[$i]["promo"]=$donnees["promo"];
			$globale[$i]["depense"]=$donnees["depense"];
			$globale[$i]["volume"]=$donnees["volume"];
			$globale[$i]["alcool"]=$donnees["alcool"];
			$globale[$i]["perm"]=$donnees["perm"];
			if (!in_array($donnees["promo"], $promo)){
                array_push($promo, $donnees["promo"]);
            }
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

   	for ($j=2013;$j<$year_max-1;$j++){
            //echo $j."<br>";
            $time_min=strtotime($j."-08-20");
//            echo "time min :".$time_min."<br>";
            $time_max=strtotime(($j+1)."-07-10");
//            echo "time max :".$time_max."<br>";

            $req = $bdd -> prepare("SELECT u.id_user, u.login ,u.nom, u.prenom, u.promo, ys.depense, ys.volume, ys.alcool, ys.perm
            FROM user u
            JOIN year_stats ys ON (u.id_user=ys.id_user)
			WHERE ys.annee = ?");
            $req -> execute(array($j));

            $i=0;
            while ($donnees = $req->fetch()){
                $annee["data"][$j][$i]["id"]=$donnees["id_user"];
                $annee["data"][$j][$i]["nom"]=$donnees["nom"];
                $annee["data"][$j][$i]["prenom"]=$donnees["prenom"];
                $annee["data"][$j][$i]["promo"]=$donnees["promo"];
                $annee["data"][$j][$i]["depense"]=$donnees["depense"];
                $annee["data"][$j][$i]["volume"]=$donnees["volume"];
                $annee["data"][$j][$i]["alcool"]=$donnees["alcool"];
                $annee["data"][$j][$i]["perm"]=$donnees["perm"];
                $i++;
            }
            if ($i!=0){
                $annee['list'][$k]["id"]=$j;
                $annee['list'][$k]["name"]=$j."/".($j+1);
                $k++;
            } else {
                $r = $bdd -> prepare("SELECT u.id_user, u.login ,u.nom, u.prenom, u.promo, d.depense, d.volume, d.alcool, c.perm
FROM user u
JOIN (
    SELECT a.id_user, COALESCE(-a.depense,0)-COALESCE(b.depense,0) as depense, COALESCE(a.volume,0)+COALESCE(b.volume,0) as volume, COALESCE(a.alcool,0)+COALESCE(b.alcool,0) as alcool
    FROM (
        SELECT op.id_user, SUM(op.prix) as depense, SUM(op.nb*ct.capacite) as volume, SUM(op.nb*ct.capacite*cu.degre*0.01) as alcool
        FROM transaction op, boisson b, contenant ct, contenu cu
        WHERE op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type='bouteille_unique' and op.datee>? and op.datee<?
        GROUP BY op.id_user
    ) a
    LEFT JOIN (
        SELECT op.id_user, SUM(op.prix) as depense, SUM(op.nb*0.25) as volume, SUM(op.nb*0.25*cu.degre*0.01) as alcool
        FROM transaction op, boisson b, contenant ct, contenu cu
        WHERE op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type!='bouteille_unique' and op.datee>? and op.datee<?
        GROUP BY op.id_user
    ) b ON (a.id_user=b.id_user)
    UNION ALL
    SELECT b.id_user,  COALESCE(-a.depense,0)-COALESCE(b.depense,0) as depense, COALESCE(a.volume,0)+COALESCE(b.volume,0) as volume, COALESCE(a.alcool,0)+COALESCE(b.alcool,0) as alcool
    FROM (
        SELECT op.id_user, SUM(op.prix) as depense, SUM(op.nb*ct.capacite) as volume, SUM(op.nb*ct.capacite*cu.degre*0.01) as alcool
        FROM transaction op, boisson b, contenant ct, contenu cu
        WHERE op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type='bouteille_unique' and op.datee>? and op.datee<?
        GROUP BY op.id_user
    ) a
    RIGHT JOIN (
        SELECT op.id_user, SUM(op.prix) as depense, SUM(op.nb*0.25) as volume, SUM(op.nb*0.25*cu.degre*0.01) as alcool
        FROM transaction op, boisson b, contenant ct, contenu cu
        WHERE op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type!='bouteille_unique' and op.datee>? and op.datee<?
		GROUP BY op.id_user
	) b ON (a.id_user=b.id_user)
	WHERE a.id_user IS NULL
) d ON (u.id_user=d.id_user)
JOIN (
    SELECT op.id_user, COUNT(DISTINCT op.id_perm) as perm
    FROM transaction op
    WHERE op.B_C_A='B' and op.datee>? and op.datee<?
    GROUP BY op.id_user
) c on (u.id_user=c.id_user)");
                $r -> execute(array($time_min,$time_max,$time_min,$time_max,$time_min,$time_max,$time_min,$time_max,$time_min,$time_max));

                $i=0;
                while ($donnees = $r->fetch()){
                    $annee["data"][$j][$i]["id"]=$donnees["id_user"];
                    $annee["data"][$j][$i]["nom"]=$donnees["nom"];
                    $annee["data"][$j][$i]["prenom"]=$donnees["prenom"];
                    $annee["data"][$j][$i]["promo"]=$donnees["promo"];
                    $annee["data"][$j][$i]["depense"]=$donnees["depense"];
                    $annee["data"][$j][$i]["volume"]=$donnees["volume"];
                    $annee["data"][$j][$i]["alcool"]=$donnees["alcool"];
                    $annee["data"][$j][$i]["perm"]=$donnees["perm"];
                    $i++;

                    if ($j < ($year_max -1)) {
                        $request = $bdd -> prepare("INSERT INTO year_stats VALUES (?,?,?,?,?,?)");
                        $request -> execute(array($donnees["id_user"],$j,$donnees["depense"],$donnees["volume"],$donnees["alcool"],$donnees["perm"]));
                    }
                }
                if ($i!=0){
                    $annee['list'][$k]["id"]=$j;
                    $annee['list'][$k]["name"]=$j."/".($j+1);
                    $k++;
                }
            }
        }
    $time_min=strtotime(($year_max-1)."-08-20");
//            echo "time min :".$time_min."<br>";
    $time_max=strtotime($year_max."-07-10");
//            echo "time max :".$time_max."<br>";


    $r = $bdd -> prepare("SELECT u.id_user, u.login ,u.nom, u.prenom, u.promo, d.depense, d.volume, d.alcool, c.perm
FROM user u
JOIN (
SELECT a.id_user, COALESCE(-a.depense,0)-COALESCE(b.depense,0) as depense, COALESCE(a.volume,0)+COALESCE(b.volume,0) as volume, COALESCE(a.alcool,0)+COALESCE(b.alcool,0) as alcool
FROM (
    SELECT op.id_user, SUM(op.prix) as depense, SUM(op.nb*ct.capacite) as volume, SUM(op.nb*ct.capacite*cu.degre*0.01) as alcool
    FROM transaction op, boisson b, contenant ct, contenu cu
    WHERE op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type='bouteille_unique' and op.datee>? and op.datee<?
    GROUP BY op.id_user
) a
LEFT JOIN (
    SELECT op.id_user, SUM(op.prix) as depense, SUM(op.nb*0.25) as volume, SUM(op.nb*0.25*cu.degre*0.01) as alcool
    FROM transaction op, boisson b, contenant ct, contenu cu
    WHERE op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type!='bouteille_unique' and op.datee>? and op.datee<?
    GROUP BY op.id_user
) b ON (a.id_user=b.id_user)
UNION ALL
SELECT b.id_user,  COALESCE(-a.depense,0)-COALESCE(b.depense,0) as depense, COALESCE(a.volume,0)+COALESCE(b.volume,0) as volume, COALESCE(a.alcool,0)+COALESCE(b.alcool,0) as alcool
FROM (
    SELECT op.id_user, SUM(op.prix) as depense, SUM(op.nb*ct.capacite) as volume, SUM(op.nb*ct.capacite*cu.degre*0.01) as alcool
    FROM transaction op, boisson b, contenant ct, contenu cu
    WHERE op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type='bouteille_unique' and op.datee>? and op.datee<?
    GROUP BY op.id_user
) a
RIGHT JOIN (
    SELECT op.id_user, SUM(op.prix) as depense, SUM(op.nb*0.25) as volume, SUM(op.nb*0.25*cu.degre*0.01) as alcool
    FROM transaction op, boisson b, contenant ct, contenu cu
    WHERE op.B_C_A='B' and op.id_B_C=b.id and b.id_contenu=cu.id and b.id_contenant=ct.id and ct.type!='bouteille_unique' and op.datee>? and op.datee<?
    GROUP BY op.id_user
) b ON (a.id_user=b.id_user)
WHERE a.id_user IS NULL
) d ON (u.id_user=d.id_user)
JOIN (
SELECT op.id_user, COUNT(DISTINCT op.id_perm) as perm
FROM transaction op
WHERE op.B_C_A='B' and op.datee>? and op.datee<?
GROUP BY op.id_user
) c on (u.id_user=c.id_user)");
    $r -> execute(array($time_min,$time_max,$time_min,$time_max,$time_min,$time_max,$time_min,$time_max,$time_min,$time_max));

    $i=0;
    $donnees = $r->fetch();
    while ($donnees = $r->fetch()){
        $annee["data"][($year_max-1)][$i]["id"]=$donnees["id_user"];
        $annee["data"][($year_max-1)][$i]["nom"]=$donnees["nom"];
        $annee["data"][($year_max-1)][$i]["prenom"]=$donnees["prenom"];
        $annee["data"][($year_max-1)][$i]["promo"]=$donnees["promo"];
        $annee["data"][($year_max-1)][$i]["depense"]=$donnees["depense"];
        $annee["data"][($year_max-1)][$i]["volume"]=$donnees["volume"];
        $annee["data"][($year_max-1)][$i]["alcool"]=$donnees["alcool"];
        $annee["data"][($year_max-1)][$i]["perm"]=$donnees["perm"];
        $i++;

        $request = $bdd -> prepare("INSERT INTO year_stats VALUES (?,?,?,?,?,?)");
        $request -> execute(array($donnees["id_user"],($year_max-1),$donnees["depense"],$donnees["volume"],$donnees["alcool"],$donnees["perm"]));
    }
    if ($i!=0){
        $annee['list'][$k]["id"]=($year_max-1);
        $annee['list'][$k]["name"]=($year_max-1)."/".$year_max;
        $k++;
    }


	$answer["globale"]=$globale;
    $answer["annee"]=$annee;
    $answer["promo"]=$promo;
	$answer=json_encode($answer);


//    $end = microtime(true);
//    echo "This process used " . ($end-$starttt) .
//        " ms for running\n";

    header('Content-Type: application/json');
	echo $answer;
}
?>
