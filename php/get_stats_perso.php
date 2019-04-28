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

if (isset($_SESSION["id_cercle"]) AND $_SESSION["droit"]!="aucun")
{
    if (!isset($_GET["id"]) or $_GET["id"]==0){
        $_GET["id"]=$_SESSION["id_cercle"];
    }
	#Globale


    $req = $bdd -> prepare("(SELECT SUM(op.nb) AS nb, cu.type FROM transaction op, contenu cu, boisson b WHERE op.id_user=? AND op.B_C_A='B' AND op.id_B_C=b.id AND b.id_contenu=cu.id AND cu.id!=21 AND cu.id!=25 GROUP BY cu.type) UNION
                                       (SELECT SUM(op.nb) AS nb, cu.nom AS type FROM transaction op, contenu cu, boisson b WHERE op.id_user=? AND op.B_C_A='B' AND op.id_B_C=b.id AND b.id_contenu=cu.id AND (cu.id=21 OR cu.id=25) GROUP BY cu.id) ORDER BY nb desc");
    $req -> execute(array($_GET["id"],$_GET["id"]));
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
	for ($j=2013;$j<$year_max;$j++){
   		//echo $j."<br>";
   		$time_min=strtotime($j."-08-20");
        //echo "time min :".$time_min."<br>";
        $time_max=strtotime(($j+1)."-07-10");
        //echo "time max :".$time_max."<br>";

        $req = $bdd -> prepare("(SELECT SUM(op.nb) AS nb, cu.type FROM transaction op, contenu cu, boisson b WHERE op.id_user=? AND op.B_C_A='B' AND op.id_B_C=b.id AND b.id_contenu=cu.id AND cu.id!=21 AND cu.id!=25 and op.datee>? and op.datee<? GROUP BY cu.type) UNION
                                       (SELECT SUM(op.nb) AS nb, cu.nom AS type FROM transaction op, contenu cu, boisson b WHERE op.id_user=? AND op.B_C_A='B' AND op.id_B_C=b.id AND b.id_contenu=cu.id AND (cu.id=21 OR cu.id=25) and op.datee>? and op.datee<? GROUP BY cu.id)  ORDER BY nb desc");
        $req -> execute(array($_GET["id"],$time_min,$time_max,$_GET["id"],$time_min,$time_max));
        $i=0;
        while ($donnees = $req->fetch()){
            $annee[$j][$i]["key"]=$donnees["type"];
            $annee[$j][$i]["y"]=$donnees["nb"];
            $i++;
        }
	}


    $answer['globale']=$globale;
    $answer['annee']=$annee;
    header('Content-Type: application/json');
	$answer=json_encode($answer);
	echo $answer;
}
?>
