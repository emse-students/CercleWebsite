<?php
session_start();
include ("connexion.php");

include("validation_droits.php");

if ($_POST["montant"]>0) {
    $req = $bdd->prepare('INSERT INTO transaction VALUES (null,?,?,2,"A",0,?,1,?)');
    $req->execute(array($_POST["id_user"],
        $_SESSION["id_cercle"],
        time(),
        $_POST["montant"]
        ));
}else{
    $req = $bdd->prepare('INSERT INTO transaction VALUES (null,?,?,2,"C",1,?,1,?)');
    $req->execute(array($_POST["id_user"],
        $_SESSION["id_cercle"],
        time(),
        $_POST["montant"]
        ));
}


$req = $bdd->prepare('UPDATE user set solde=solde+? where id_user=?');
$req->execute(array($_POST["montant"],$_POST["id_user"]));


$answer['ok']=true;
$answer=json_encode($answer);
echo $answer;
?>
