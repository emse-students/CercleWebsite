<?php
session_start();
include ("connexion.php");

$str_json = file_get_contents('php://input');
$str_json =json_decode($str_json);
$data=$str_json->data;
$type=$str_json->type;
$i=0;
foreach ($data as $value)
{

	$array[$i]["id"]=$value->id;
	$array[$i]["nom"]=$value->nom;
	$array[$i]["prix_vente"]=$value->prix_vente;

	if (isset($value->type))
	{
		$array[$i]["type"]=$value->type;
		$array[$i]["degre"]=$value->degre;
		$array[$i]["consigne"]=$value->consigne;
		$array[$i]["capacite"]=$value->capacite;
		$array[$i]["fut_bouteille"]=$value->fut_bouteille;
	}

	$i++;
}
if ($i==0) {
	$array=[];
}
$req = $bdd -> query("SELECT id FROM perm  ORDER BY id DESC LIMIT 1");

while ($donnees = $req->fetch())
{

    $perm["id"]=$donnees["id"];
}



if ($type=="boisson")
{
	$rep = $bdd->prepare('SELECT id_B_C FROM inventaire_perm WHERE B_C="B" AND id_perm= ?');

	$rep->execute(array($perm["id"]));
	$i=0;
	while ($donnees = $rep->fetch())
	{
	    $previous_boissons[$i]= $donnees['id_B_C'];
	    $i++;
	}
	if ($i==0) {
	    $previous_boissons=[];
	}

	$j=0;

	foreach ($array as $key => $value)
	{
		$rep = $bdd -> prepare("UPDATE boisson SET prix_vente=? WHERE id=?");
		$rep -> execute(array($value["prix_vente"],$value["id"]));




        $new_boissons[$j]=$value["id"];
        $j++;


        if (!in_array($value["id"], $previous_boissons)) {

                $req = $bdd->prepare('INSERT INTO inventaire_perm VALUES (null,?,"B",?)');
                $req->execute(array($perm["id"],$value["id"]));

                $previous_boissons[$i]=$value["id"];
                $i++;

        }

	}

	if ($j==0) {
	    $new_boissons=[];
	}
	foreach ($previous_boissons as $key => $value) {
            if (!in_array($value, $new_boissons)) {
                //Supprimer la ligne

                $rep = $bdd->prepare('SELECT id FROM inventaire_perm WHERE B_C="B" AND id_B_C=? AND id_perm=?');
                $rep->execute(array($value,$perm["id"]));

                while ($donnees = $rep->fetch())
                {
                    $id_suppr= $donnees['id'];

                }
                if (isset($id_suppr))
                {
	                $req = $bdd->prepare('DELETE FROM inventaire_perm WHERE id = ?');
	                $req->execute(array($id_suppr));
	            }
            }
        }
}elseif($type=="consommable"){
	$rep = $bdd->prepare('SELECT id_B_C FROM inventaire_perm WHERE B_C="C" AND id_perm= ?');

	$rep->execute(array($perm["id"]));
	$i=0;
	while ($donnees = $rep->fetch())
	{
	    $previous_consommables[$i]= $donnees['id_B_C'];
	    $i++;
	}
	if ($i==0) {
	     $previous_consommables=[];
	}

	$j=0;
	foreach ($array as $key => $value)
	{
		if ($value["id"] == NULL){
            $rep = $bdd -> prepare("INSERT INTO consommable VALUES (null,?,?)");
            $rep -> execute(array($value["nom"],$value["prix_vente"]));

            $rep = $bdd -> prepare("SELECT id FROM consommable WHERE nom=? and prix_vente=?");
            $rep -> execute(array($value["nom"],$value["prix_vente"]));
            $donnees = $rep->fetch();
            $value["id"] = $donnees["id"];
		}
		$rep = $bdd -> prepare("UPDATE consommable SET prix_vente=? WHERE id=?");
		$rep -> execute(array($value["prix_vente"],$value["id"]));




        $new_consommables[$j]=$value["id"];
        $j++;


        if (!in_array($value["id"], $previous_consommables)) {

                $req = $bdd->prepare('INSERT INTO inventaire_perm VALUES (null,?,"C",?)');
                $req->execute(array($perm["id"],$value["id"]));

                $previous_consommables[$i]=$perm["id"];
                $i++;

        }

	}

	if ($j==0) {
	    $new_consommables=[];
	}

	foreach ($previous_consommables as $key => $value) {
            if (!in_array($value, $new_consommables)) {
                //Supprimer la ligne

                $rep = $bdd->prepare('SELECT id FROM inventaire_perm WHERE B_C="C" AND id_B_C=? AND id_perm=?');
                $rep->execute(array($value,$perm["id"]));

                while ($donnees = $rep->fetch())
                {
                    $id_suppr= $donnees['id'];

                }
                if (isset($id_suppr))
                {
					$req = $bdd->prepare('DELETE FROM inventaire_perm WHERE id = ?');
					$req->execute(array($id_suppr));
                }

            }
        }
	}elseif($type=="forum"){
		$rep = $bdd->prepare('SELECT id_B_C FROM inventaire_perm WHERE B_C="F" AND id_perm= ?');

		$rep->execute(array($perm["id"]));
		$i=0;
		while ($donnees = $rep->fetch())
		{
		    $previous_boissons_F[$i]= $donnees['id_B_C'];
		    $i++;
		}
		if ($i==0) {
		    $previous_boissons_F=[];
		}

		$j=0;

		foreach ($array as $key => $value)
		{
			
			$rep = $bdd -> prepare("UPDATE boisson SET prix_vente=? WHERE id=?");
			$rep -> execute(array($value["prix_vente"],$value["id"]));




	        $new_boissons_F[$j]=$value["id"];
	        $j++;


	        if (!in_array($value["id"], $previous_boissons_F)) {

	                $req = $bdd->prepare('INSERT INTO inventaire_perm VALUES (null,?,"F",?)');
	                $req->execute(array($perm["id"],$value["id"]));

	                $previous_boissons_F[$i]=$value["id"];
	                $i++;

	        }

		}

		if ($j==0) {
		    $new_boissons_F=[];
		}
		foreach ($previous_boissons_F as $key => $value) {
	            if (!in_array($value, $new_boissons_F)) {
	                //Supprimer la ligne

	                $rep = $bdd->prepare('SELECT id FROM inventaire_perm WHERE B_C="F" AND id_B_C=? AND id_perm=?');
	                $rep->execute(array($value,$perm["id"]));

	                while ($donnees = $rep->fetch())
	                {
	                    $id_suppr= $donnees['id'];

	                }
	                if (isset($id_suppr))
	                {
		                $req = $bdd->prepare('DELETE FROM inventaire_perm WHERE id = ?');
		                $req->execute(array($id_suppr));
		            }
	            }
	        }
	}
$answer['ok']=true;
$answer=json_encode($answer);
echo $answer;
?>
