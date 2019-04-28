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

    <body ng-app="programme4_app"  ng-controller="mainController">
    	<?php         
        $page=3;        
    	
    	include("php/header.php");
    	?>
    	<div class="page">
    		
            <h1>Programme4</h1>
            <p style="color: red" ng-if="text">{{text}}</p>

            <div class="L_left">
                <div class="item_formulaire" style="margin-left: 1em; ">Compte ayant fait les transactions mais n'ayant pas le bon login :</div>
                <!-- zone de saisie déclenchant l'autocomplétion -->
                <div ng-if="users.user==null">
                    <input type="text" placeholder="Tapez le nom de l'utilisateur" ng-model="users.search" style="font-size: 1.5em; width: 13em;" autocomplete="off" ng-click="users.auto_c=true; users.user=null;"/>

                    <div class="users.auto_c" ng-if="users.auto_c && users.search!=null">
                        <div class="auto_c_value" ng-repeat="item in all_users | filter : users.search" ng-click="users.user=item; users.auto_c=false;">{{item.prenom}} {{item.nom}} {{item.type}} {{item.promo}}</div>
                    </div>
                </div>
                <div class="L_left" ng-if="users.user==null">
                    <span ng-if="users.search!=null" style="width: 30px; margin: 0.5em" >
                        <img style="width: 100%;" src="images/false.png">
                    </span>
                </div>

                <div class="div">
                    <div class="L_left" ng-if="users.user!=null">
                        <span  style="font-size: 1.8em;">{{users.user.prenom}} {{users.user.nom}} </span><span  style="font-size: 1.2em; margin-left: 10px;"> {{users.user.type}}  {{users.user.promo}}</span>
                    </div>
                    <div class="L_left" ng-if="users.user!=null">
                        <span  style="font-size: 1.8em;">Solde : {{users.user.solde}} Droits : {{users.user.droit}} </span>
                    </div>
                </div>
                <div class="bouton" ng-click="users.user = null; users.search = null" style="margin-left: 5%;" ng-if="users.user!=null">Annuler</div>
            </div>

            <div class="L_left">
                <div class="item_formulaire" style="margin-left: 1em; ">Compte ayant le bon login (il sera supprimé):</div>
                <!-- zone de saisie déclenchant l'autocomplétion -->
                <div ng-if="users.user2==null">
                    <input type="text" placeholder="Tapez le nom de l'utilisateur" ng-model="users.search2" style="font-size: 1.5em; width: 13em;" autocomplete="off" ng-click="users.auto_c2=true; users.user2=null;"/>

                    <div class="users.auto_c2" ng-if="users.auto_c2 && users.search2!=null">
                        <div class="auto_c_value" ng-repeat="item in all_users | filter : users.search2" ng-click="users.user2=item; users.auto_c2=false;">{{item.prenom}} {{item.nom}} {{item.type}} {{item.promo}}</div>
                    </div>
                </div>
                <div class="L_left" ng-if="users.user2==null">
                                <span ng-if="users.search2!=null" style="width: 30px; margin: 0.5em" >
                                    <img style="width: 100%;" src="images/false.png">
                                </span>
                </div>

                <div class="div">
                    <div class="L_left" ng-if="users.user2!=null">
                        <span  style="font-size: 1.8em;">{{users.user2.prenom}} {{users.user2.nom}} </span><span  style="font-size: 1.2em; margin-left: 10px;"> {{users.user2.type}}  {{users.user2.promo}}</span>
                    </div>
                    <div class="L_left" ng-if="users.user2!=null">
                        <span  style="font-size: 1.8em;">Solde : {{users.user2.solde}} Droits : {{users.user2.droit}} </span>
                    </div>
                </div>
                <div class="bouton" ng-click="users.user2 = null; users.search2 = null" style="margin-left: 5%;" ng-if="users.user2!=null">Annuler</div>
            </div>

            <div class="bouton" ng-click="fusion();" style="margin-left: 5%;" ng-if="users.user2!=null && users.user!=null">Fusionner</div>
    		
    	</div>
        <script src="js/angularjs.js" type="text/javascript"></script>
        
        <?php 
        echo "<script src=\"js/programme4_app.js?".time()."\" type=\"text/javascript\"></script>";
        ?>
        
        
    </body>