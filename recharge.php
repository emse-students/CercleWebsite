<?php
session_start();
include ("php/connexion.php");

include("php/validation_droits.php");

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
        <title>Cercle EMSE-Rechargement</title>
    </head>

    <body ng-app="recharge_app"  ng-controller="mainController">
    	<?php
        $page=6;

    	include("php/header.php");
    	?>
    	<div class="page">

            <h1>Rechargement de compte</h1>
            <div class="C_centre">
                <div class="formulaire" style="min-height: 25em;">
                    <div class="L_centre" style="height: 2em;"><div ng-if="client.recharge_ok" style="color: green">Rechargement effectué</div></div>
                    <div class="L_left">
                        <div class="item_formulaire" style="margin-left: 20%; ">Client :</div>
                        <!-- zone de saisie déclenchant l'autocomplétion -->
                        <div ng-if="client.client==null">
                            <input type="text" placeholder="Tapez le nom du client" ng-model="search" style="font-size: 1.5em; width: 15em;" autocomplete="off" ng-click="client.auto_c=true; client.recharge_ok=false;"/>

                            <div class="auto_c" ng-if="client.auto_c && search!=null">
                                <div class="auto_c_value" ng-repeat="item in users | filter : search" ng-click="client.client=item; client.auto_c=false; client.client.montant=0; maj();">{{item.prenom}} {{item.nom}}</div>
                            </div>
                        </div>
                        <div class="L_left" ng-if="client.client==null">
                            <span ng-if="client.client==null && search!=null" style="width: 30px; margin: 0.5em" >
                                <img style="width: 100%;" src="images/false.png">
                            </span>
                            <span class="info" ng-if="client.client==null && search!=null" style="font-size: 0.8em; color: red margin: auto;">
                                Selectionnez un nom valide
                            </span>
                            <span ng-if="client.client!=null && search!=null" style="width: 30px; margin: 0.5em" >
                                <img style="width: 100%;" src="images/correct.png">
                            </span>
                        </div>

                        <div class="info_user">
                            <div class="L" ng-if="client.client!=null">

                                <div class="L_right">
                                    <span class="clickable" ng-if="client.client!=null" style="width: 30px; margin: 0.5em" ng-click="client.client=null; search=null;">
                                        <img style="width: 100%;" src="images/croix rouge.jpg">
                                    </span>

                                </div>
                            </div>

                            <div class="L_left" ng-if="client.client!=null">
                                <span  style="font-size: 1.8em;">{{client.client.prenom}} {{client.client.nom}} </span><span  style="font-size: 1.2em; margin-left: 10px;"> promo {{client.client.promo}}</span>
                            </div>
                            <div class="L_space_a" ng-if="client.client!=null">
                                <span>Solde : {{prix(client.client.solde)}}</span>
                            </div>
                            <div class="L_left" ng-if="client.client!=null">
                                <span>Solde après transaction : </span><span style="font-size: 1.8em; margin-left: 10px;"> {{prix(client.client.new_solde)}}</span>
                            </div>
                        </div>

                    </div>
                    <div class="L_left" ng-if="client.client!=null">
                        <div class="item_formulaire" style="margin-left: 20%; ">Montant :</div>
                        <div class="L_space_a" style="width: 50%;">
                            <div class="clickable" style="height: 30px; width: 30px;" ng-click="moins()">
                                <img style="width :100%;" src="images/moins.png">
                            </div>
                            <div class="prix clickable" ng-click="client.prix=true;" ng-if="!client.prix" style="width: 120px; text-align: center;">{{prix(client.client.montant)}}</div>
                            <div class="L_left" style="width: 120px;" ng-if="client.prix">
                                <input type="number"  ng-model="client.client.montant" style=" font-size: 1.5em; width: 80px;">
                                <div class="bouton clickable"  ng-click="client.prix=false; maj();" style="margin-left: 10px; ">Ok</div>
                            </div>
                            <div class="clickable" style="height: 30px; width: 30px;" ng-click="plus()">
                                <img style="width :100%;" src="images/plus.png">
                            </div>
                        </div>

                    </div>

                     <div class="L_left" ng-if="client.client!=null && client.client.montant!=0 && !client.prix" ><div class="bouton" ng-click="validate()" style="margin-left: 20%;" >Recharger</div></div>
                    <h2>Historiques des rechargements :</h2>
                    <div class="L_tableau" >
                        <div class="head_tableau clickable" ng-click="compte.order('date')">Date</div>
                        <div class="head_tableau clickable" ng-click="compte.order('perm.nom')">Perm</div>
                        <div class="head_tableau clickable" ng-click="compte.order('user.easy_search')" style="width: 125%;">Client</div>
                        <div class="head_tableau clickable" ng-click="compte.order('debiteur.easy_search')" style="width: 125%;">Rechargé par</div>
                        <div class="head_tableau clickable" ng-click="compte.order('prix')" style="width: 50%;">Montant</div>
                        <?php
                        if ($_SESSION["droit"]=="cercle") {
                            echo "<div class=\"head_tableau\" style=\"width: 75%;\"></div>";
                        }
                        ?>
                    </div>
                    <div class="L_tableau" ng-class="color($index,operation)" ng-if="operation.nb>0" ng-repeat="operation in operations | filter : x | orderBy : compte.ordervalue">
                        <div class="case_tableau">{{datestr(operation.date)}}</div>
                        <div class="case_tableau">{{operation.perm.nom}}</div>
                        <a href='compte.php?id={{operation.user.id}}' style="width: 125%;"><div class="case_tableau" >{{operation.user.prenom}} {{operation.user.nom}}</div></a>
                        <a href='compte.php?id={{operation.debiteur.id}}' style="width: 125%;"><div class="case_tableau" >{{operation.debiteur.prenom}} {{operation.debiteur.nom}}</div></a>
                        <div class="case_tableau" style="width: 50%;">{{prix(operation.prix)}}</div>
                        <?php
                        if ($_SESSION["droit"]=="cercle") {
                            echo "<div class=\"case_tableau\" style=\"width: 75%;\"><div class='bouton' style='padding: 5px;' ng-click='annule(operation)'>Annuler</div></div>";
                        }
                        ?>
                    </div>
                </div>

            </div>



    	</div>
        <script src="js/angularjs.js" type="text/javascript"></script>

        <?php
        echo "<script src=\"js/recharge_app.js?".time()."\" type=\"text/javascript\"></script>";
        ?>


    </body>
