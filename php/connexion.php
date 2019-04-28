<?php
include_once ("./env/env.php");
include_once ("../env/env.php");

if ($_ENV['env_name'] == "dev") {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}


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
	$req = $bdd -> prepare("SELECT id_user, droit FROM user WHERE login=?");
	$req -> execute(array($_SESSION['phpCAS']['user']));
	$donnees = $req -> fetch();
    
    
	if(isset($donnees["id_user"]) AND $donnees["droit"]!="aucun")
	{
	    $_SESSION["id_cercle"]=$donnees["id_user"];
		$_SESSION["prenom"]=$_SESSION['phpCAS']['attributes']["givenName"];
		$_SESSION["nom"]=$_SESSION['phpCAS']['attributes']["sn"];
		$_SESSION["droit"]=$donnees["droit"];
		
		
	}else{
		header("location: index.php?erreur=Connexion_impossible" );
	}
}
?>