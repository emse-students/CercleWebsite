<?php
include_once ("./env/env.php");
include_once ("../env/env.php");

try
{
    $bdd = new PDO('mysql:host='.$_ENV["bdd"]["host"].';dbname='.$_ENV["bdd"]["bdd_name"].';charset=utf8', $_ENV['bdd']['login'], $_ENV['bdd']['pwd'], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    $bdd->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
}catch (Exception $e)
{
    die('Erreur : ' . $e->getMessage());
}
if ($_ENV['CAS']) {
    //identification via le CAS
    require_once("CAS.php");

// -------------------------------- CASIFICATION de l'application --------------------------------
    phpCAS::setDebug();
    phpCAS::client(CAS_VERSION_2_0, "cas.emse.fr", 443, "");
    phpCAS::setNoCasServerValidation();
    phpCAS::forceAuthentication();
// -------------------------------- CASIFICATION de l'application --------------------------------
} else {
    // Sinon on simule ce que renvoie le CAS
    $_SESSION["phpCAS"]=Array (
        "user" => "corentin.doue",
        "attributes" => Array (
            "uid" => "corentin.doue",
            "mail" => "corentin.doue@etu.emse.fr",
            "displayName" => "Corentin DOUE",
            "givenName" => "Corentin",
            "sn" => "DOUE"
        )
    );
}


// Connexion a la BDD via l'id du CAS
if (!isset($_SESSION["id_cercle"]))
{
	$req = $bdd -> prepare("SELECT id_user, droit_cercle FROM user WHERE login_user=?");
	$req -> execute(array($_SESSION['phpCAS']['user']));
	$donnees = $req -> fetch();
    
    
	if(isset($donnees["id_user"]) AND $donnees["droit_cercle"]!="aucun")
	{
		
		$rep = $bdd->prepare('UPDATE user SET prenom=:prenom, nom=:nom WHERE id_user=:ID');
        $rep->execute(array(
            'ID' => $donnees["id_user"],
            'prenom' => $_SESSION['phpCAS']['attributes']["givenName"],
            'nom' => $_SESSION['phpCAS']['attributes']["sn"]
            ));

		$_SESSION["id_cercle"]=$donnees["id_user"];
		$_SESSION["prenom"]=$_SESSION['phpCAS']['attributes']["givenName"];
		$_SESSION["nom"]=$_SESSION['phpCAS']['attributes']["sn"];
		$_SESSION["droit_cercle"]=$donnees["droit_cercle"];
		
		
	}else{
		header("location: index.php?erreur=Connexion_impossible" );
	}
}
?>