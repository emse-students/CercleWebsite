<?php

	include ("connexion.php");

	// Avant tout contenu HTML, on lance une session
	session_start(); // À faire dans toutes les pages pour rester connecter à son compte
	// On efface toutes les variables de la session

	$_SESSION = array();
	// Puis on détruit la session
	session_unset();        // On détruit les variables de session si vous les utilisez

    if ($_ENV['CAS']) {
        phpCAS::logout();        // On se déconnecte CAS
    }

	session_destroy();

	// On renvoie ensuite sur la page de connection
	header("location: ../index.php" ); 
?>