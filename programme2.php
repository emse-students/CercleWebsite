<?php
session_start();
include ("php/connexion.php");

?>

<!DOCTYPE html>

<html>

    <head>

        <meta charset="utf-8" />
        <?php 
        echo "<link rel='stylesheet' href='css/style.css?".time()."'/>";
        ?>
        <link rel="icon" type="image/png" href="images/touchicon.png" />
        <link rel="apple-touch-icon" href="images/appleicon.png" />
        <title>Cercle EMSE-Programme</title>
    </head>

    <body ng-app="programme2_app"  ng-controller="mainController">
    	<?php         
        $page=3;        
    	
    	include("php/header.php");
    	?>
    	<div class="page">
    		
            <h1>Programme2</h1>
               
            <div ng-repeat="boisson in boissons" style="margin: 10px;" ng-class="color($index)">
            	ID = {{boisson.id}} Nom = {{boisson.nom}} //-->  Associer à 
            	<select ng-model="boisson.boisson_asso" ng-options="item.nom for item in boissons" ><option value="">--sélectionner un consommable --</option></select> 
            	<span ng-click="associe(boisson)" class="bouton" style="padding: 0; font-size: 0.5em;"> Valider</span> // ou // <select ng-model="boisson.contenant" ng-options="item.nom for item in contenants" ><option value="1">Verre</option></select><span class="bouton" style="padding: 0; font-size: 0.5em;" ng-click="autre(boisson)">Ceci est une boisson</span>
            </div>
    		
    	</div>
        <script src="js/angularjs.js" type="text/javascript"></script>
        
        <?php 
        echo "<script src=\"js/programme2_app.js?".time()."\" type=\"text/javascript\"></script>";
        ?>
        
        
    </body>