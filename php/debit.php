<?php
session_start();
include ("connexion.php");

$str_json = file_get_contents('php://input');
$str_json =json_decode($str_json);
$data=$str_json->data;


foreach ($data as $value)
{
	if ($value->type=="B") {
		$req = $bdd->prepare('INSERT INTO transaction VALUES (null,?,?,?,"B",?,?,?,?)');
        $req->execute(array($str_json->id_user,
					$_SESSION["id_cercle"],
        	$str_json->id_perm,
        	$value->id,
        	time(),
        	$value->nb,
        	-$value->prix
        	));
        $req = $bdd->prepare('UPDATE perm set total_litre=total_litre+? where id=?');
        $req->execute(array($value->litre,$str_json->id_perm));
	}elseif ($value->type=="C") {
		$req = $bdd->prepare('INSERT INTO transaction VALUES (null,?,?,?,"C",?,?,?,?)');
        $req->execute(array($str_json->id_user,
					$_SESSION["id_cercle"],
        	$str_json->id_perm,
        	$value->id,
        	time(),
        	$value->nb,
        	-$value->prix
        	));
	}elseif ($value->type=="add") {
		$req = $bdd->prepare('SELECT id FROM consommable where nom=?');
        $req->execute(array($value->id));

        $donnees=$req->fetch();
        if (isset($donnees["id"])) {
        	$id=$donnees["id"];
        }else{
        	$req = $bdd->prepare('INSERT INTO consommable VALUES (null,?,?)');
        	$req->execute(array($value->id,$value->prix));

        	$req = $bdd->prepare('SELECT id FROM consommable where nom=?');
	        $req->execute(array($value->id));

	        $donnees=$req->fetch();
	        $id=$donnees["id"];

        }
        $req = $bdd->prepare('INSERT INTO transaction VALUES (null,?,?,?,"C",?,?,?,?)');
        $req->execute(array($str_json->id_user,
					$_SESSION["id_cercle"],
        	$str_json->id_perm,
        	$id,
        	time(),
        	$value->nb,
        	-$value->prix
        	));
	}elseif ($value->type=="F") {
		$req = $bdd->prepare('INSERT INTO transaction VALUES (null,?,?,?,"F",?,?,?,?)');
        $req->execute(array($str_json->id_user,
					$_SESSION["id_cercle"],
        	$str_json->id_perm,
        	$value->id,
        	time(),
        	$value->nb,
        	-$value->prix
        	));
        $req = $bdd->prepare('UPDATE perm set total_litre=total_litre+? where id=?');
        $req->execute(array($value->litre,$str_json->id_perm));
	}
	if ($value->nb>0) {
		$req = $bdd->prepare('UPDATE user set solde=solde-? where id_user=?');
    $req->execute(array($value->prix,$str_json->id_user));

    $req = $bdd->prepare('UPDATE perm set total_vente=total_vente+? where id=?');
    $req->execute(array($value->prix,$str_json->id_perm));
	}




}
$answer['ok']=true;
$answer=json_encode($answer);
echo $answer;
?>
