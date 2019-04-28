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



        $req = $bdd -> query("(SELECT SUM(op.nb) AS nb, cu.type FROM transaction op, contenu cu, boisson b WHERE op.B_C_A='B' AND op.id_B_C=b.id AND b.id_contenu=cu.id AND cu.id!=21 AND cu.id!=25 GROUP BY cu.type) UNION
                                       (SELECT SUM(op.nb) AS nb, cu.nom AS type FROM transaction op, contenu cu, boisson b WHERE op.B_C_A='B' AND op.id_B_C=b.id AND b.id_contenu=cu.id AND (cu.id=21 OR cu.id=25) GROUP BY cu.id) ORDER BY nb desc");
        $i=0;
        while ($donnees = $req->fetch()){
            $globale[$i]["key"]=$donnees["type"];
            $globale[$i]["y"]=$donnees["nb"];
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

   	for ($j=2013;$j<$year_max;$j++) {
        //echo $j."<br>";
        $time_min = strtotime($j . "-08-20");
//            echo "time min :".$time_min."<br>";
        $time_max = strtotime(($j + 1) . "-07-10");
//            echo "time max :".$time_max."<br>";

        $req = $bdd -> prepare("SELECT ysb.clef, ysb.y
            FROM year_stats_biere ysb
			WHERE ysb.annee = ?");
        $req -> execute(array($j));

        $i=0;
        while ($donnees = $req->fetch()) {
            $annee["data"][$j][$i]["key"] = $donnees["clef"];
            $annee["data"][$j][$i]["y"] = $donnees["y"];
            $i++;
        }
        if ($i != 0) {
            $annee['list'][$k]["id"] = $j;
            $annee['list'][$k]["name"] = $j . "/" . ($j + 1);
            $k++;
        } else {
            $req = $bdd->prepare("(SELECT SUM(op.nb) AS nb, cu.type FROM transaction op, contenu cu, boisson b WHERE op.B_C_A='B' AND op.id_B_C=b.id AND b.id_contenu=cu.id AND cu.id!=21 AND cu.id!=25 and op.datee>? and op.datee<? GROUP BY cu.type) UNION
                                       (SELECT SUM(op.nb) AS nb, cu.nom AS type FROM transaction op, contenu cu, boisson b WHERE op.B_C_A='B' AND op.id_B_C=b.id AND b.id_contenu=cu.id AND (cu.id=21 OR cu.id=25) and op.datee>? and op.datee<? GROUP BY cu.id) ORDER BY nb desc");
            $req->execute(array($time_min, $time_max, $time_min, $time_max));
            $i = 0;
            while ($donnees = $req->fetch()){
                $annee["data"][$j][$i]["key"] = $donnees["type"];
                $annee["data"][$j][$i]["y"] = $donnees["nb"];
                $i++;

                if ($j < ($year_max - 1)) {
                    $request = $bdd -> prepare("INSERT INTO year_stats_biere VALUES (?,?,?)");
                    $request -> execute(array($j,$donnees["type"],$donnees["nb"]));
                }
            }
            if ($i!=0){
                $annee['list'][$k]["id"]=$j;
                $annee['list'][$k]["name"]=$j."/".($j+1);
                $k++;
            }
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
        $req = $bdd -> prepare("(SELECT SUM(op.nb) AS nb, cu.type FROM transaction op, user u, contenu cu, boisson b WHERE u.id_user=op.id_user and u.promo=? AND op.B_C_A='B' AND op.id_B_C=b.id AND b.id_contenu=cu.id AND cu.id!=21 AND cu.id!=25 GROUP BY cu.type) UNION
                                       (SELECT SUM(op.nb) AS nb, cu.nom AS type FROM transaction op, user u, contenu cu, boisson b WHERE u.id_user=op.id_user and u.promo=? AND op.B_C_A='B' AND op.id_B_C=b.id AND b.id_contenu=cu.id AND (cu.id=21 OR cu.id=25) GROUP BY cu.id) ORDER BY nb desc");
        $req -> execute(array($j,$j));
        $i=0;
        while ($donnees = $req->fetch()){
            $promo["data"][$j][$i]["key"]=$donnees["type"];
            $promo["data"][$j][$i]["y"]=$donnees["nb"];
            $i++;
        }
    }


	$answer["globale"]=$globale;
   	$answer["promo"]=$promo;
    $answer["annee"]=$annee;
	$answer=json_encode($answer);


//    $end = microtime(true);
//    echo "This process used " . ($end-$starttt) .
//        " ms for running\n";

    header('Content-Type: application/json');
	echo $answer;
}
?>
