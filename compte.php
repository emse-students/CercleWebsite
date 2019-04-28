<?php
session_start();
include ("php/connexion.php");

if (!isset($_GET["id"])) {
    $_GET["id"]=$_SESSION["id_cercle"];
}
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
        <?php
        if ($_GET['id']==0){
            echo "<title>Cercle EMSE-Historique</title>";
        }else{
            echo "<title>Cercle EMSE-Compte</title>";
        }
        ?>
    </head>

    <body ng-app="compte_app"  ng-controller="mainController">
    	<?php
        if ($_GET['id']==0){
            $page=5;
        }else{
            $page=1;
        }

    	include("php/header.php");
    	?>
    	<div class="page">

            <?php
                if ($_GET['id']==0){
                ?>


                    <div class="L_center" style="margin-bottom: 1em;">
                      <a href="bilan_perm.php">
                        <div class="bouton">
                          Bilan des perms
                        </div>
                      </a>
                    </div>
                    <h1>Historique</h1>
                    <div class="L_center" style="margin-top: 1em;">
                        <div class="info" style="font-size: 1em;">Du</div>
                        <select name="select_type"  ng-model="date.debut.jour" style="font-size: 1em; margin-left: 0.5em;">
                            <option ng-repeat="i in getNumber(31) track by $index" value="{{$index+1}}">{{$index+1}}</option>
                        </select>
                        <div class="info" style="font-size: 1em; margin-left: 0.5em;">/</div>
                        <select name="select_type"  ng-model="date.debut.mois" style="font-size: 1em; margin-left: 0.5em;">
                            <option ng-repeat="i in getNumber(12) track by $index" value="{{$index+1}}">{{$index+1}}</option>
                        </select>
                        <div class="info" style="font-size: 1em; margin-left: 0.5em;">/</div>
                        <select name="select_type"  ng-model="date.debut.annee" style="font-size: 1em; margin-left: 0.5em;">
                            <option ng-repeat="i in getNumber(30) track by $index" value="{{$index+2000}}">{{$index+2000}}</option>
                        </select>

                        <div class="info" style="font-size: 1em; margin-left: 1em;">au</div>
                        <select name="select_type"  ng-model="date.fin.jour" style="font-size: 1em; margin-left: 0.5em;">
                            <option ng-repeat="i in getNumber(31) track by $index" value="{{$index+1}}">{{$index+1}}</option>
                        </select>
                        <div class="info" style="font-size: 1em; margin-left: 0.5em;">/</div>
                        <select name="select_type"  ng-model="date.fin.mois" style="font-size: 1em; margin-left: 0.5em;">
                            <option ng-repeat="i in getNumber(12) track by $index" value="{{$index+1}}">{{$index+1}}</option>
                        </select>
                        <div class="info" style="font-size: 1em; margin-left: 0.5em;">/</div>
                        <select name="select_type"  ng-model="date.fin.annee" style="font-size: 1em; margin-left: 0.5em;">
                            <option ng-repeat="i in getNumber(30) track by $index" value="{{$index+2000}}">{{$index+2000}}</option>
                        </select>
                    </div>
                    <div class="L_center" style="margin-top: 1em;">
                        <div class="info" style="font-size: 1em;">Nombre d'opérations affichées :</div>
                        <select name="select_type"  ng-model="nb_operation_affichee" style="font-size: 1em; margin-left: 1em;">
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="500">500</option>
                            <option value="1000">1000</option>
                        </select>

                    </div>
                    <div class="L_center" style="margin-top: 1em;">
                        <div class="bouton" ng-click="actualise(date,nb_operation_affichee);">Actualiser</div>
                    </div>
                <?php
                }else{
                    //echo $_GET["id"];
                ?>
                    <h1>Historique du compte de {{user.prenom}} {{user.nom}}</h1>
                    <div class="info">Solde : {{user.solde}}</div>
                    <div class="L_center" style="margin-top: 1em;">
                        <div class="info" style="font-size: 1em;">Du</div>
                        <select name="select_type"  ng-model="date.debut.jour" style="font-size: 1em; margin-left: 0.5em;">
                            <option ng-repeat="i in getNumber(31) track by $index" value="{{$index+1}}">{{$index+1}}</option>
                        </select>
                        <div class="info" style="font-size: 1em; margin-left: 0.5em;">/</div>
                        <select name="select_type"  ng-model="date.debut.mois" style="font-size: 1em; margin-left: 0.5em;">
                            <option ng-repeat="i in getNumber(12) track by $index" value="{{$index+1}}">{{$index+1}}</option>
                        </select>
                        <div class="info" style="font-size: 1em; margin-left: 0.5em;">/</div>
                        <select name="select_type"  ng-model="date.debut.annee" style="font-size: 1em; margin-left: 0.5em;">
                            <option ng-repeat="i in getNumber(30) track by $index" value="{{$index+2000}}">{{$index+2000}}</option>
                        </select>

                        <div class="info" style="font-size: 1em; margin-left: 1em;">au</div>
                        <select name="select_type"  ng-model="date.fin.jour" style="font-size: 1em; margin-left: 0.5em;">
                            <option ng-repeat="i in getNumber(31) track by $index" value="{{$index+1}}">{{$index+1}}</option>
                        </select>
                        <div class="info" style="font-size: 1em; margin-left: 0.5em;">/</div>
                        <select name="select_type"  ng-model="date.fin.mois" style="font-size: 1em; margin-left: 0.5em;">
                            <option ng-repeat="i in getNumber(12) track by $index" value="{{$index+1}}">{{$index+1}}</option>
                        </select>
                        <div class="info" style="font-size: 1em; margin-left: 0.5em;">/</div>
                        <select name="select_type"  ng-model="date.fin.annee" style="font-size: 1em; margin-left: 0.5em;">
                            <option ng-repeat="i in getNumber(30) track by $index" value="{{$index+2000}}">{{$index+2000}}</option>
                        </select>
                    </div>
                    <div class="info">Dépenses : {{prix(depense(date))}}</div>
                    <div class="info">Nombre d'opérations : {{nb_operation(date)}}</div>
                <?php
                }
                ?>


    		<div class="centreur">
                <div class="C_centre">
                    <div class="L_tableau" >
                        <div class="head_tableau clickable" ng-click="compte.order('date')">Date</div>
                        <div class="head_tableau clickable" ng-click="compte.order('perm.nom')">Perm</div>
                        <?php
                            if ($_GET["id"]==0) {
                                echo "<div class=\"head_tableau clickable\" ng-click=\"compte.order('user.easy_search')\" style=\"width: 125%;\">Client</div>";
                                echo "<div class=\"head_tableau clickable\" ng-click=\"compte.order('debiteur.easy_search')\" style=\"width: 125%;\">Débiteur</div>";
                            }
                        ?>

                        <div class="head_tableau clickable" ng-click="compte.order('nb')" style="width: 50%;">Quantité</div>
                        <div class="head_tableau clickable" ng-click="compte.order('achat.nom')">Produit</div>
                        <div class="head_tableau clickable" ng-click="compte.order('prix')" style="width: 50%;">Prix</div>
                         <?php
                            if ($_GET["id"]==0 and $_SESSION["droit"]=="cercle") {
                                echo "<div class=\"head_tableau\" style=\"width: 75%;\"></div>";
                            }
                        ?>
                    </div>
                    <div class="L_tableau" ng-class="color($index,operation)" ng-if="operation.nb>0" ng-repeat="operation in operations | filter : x | orderBy : compte.ordervalue">

                        <div class="case_tableau">{{datestr(operation.date)}}</div>
                        <div class="case_tableau">{{operation.perm.nom}}</div>
                        <?php
                            if ($_GET["id"]==0) {
                                echo "<a href='compte.php?id={{operation.user.id}}' style=\"width: 125%;\"><div class=\"case_tableau\" >{{operation.user.prenom}} {{operation.user.nom}}</div></a>";
                                echo "<a href='compte.php?id={{operation.debiteur.id}}' style=\"width: 125%;\"><div class=\"case_tableau\" >{{operation.debiteur.prenom}} {{operation.debiteur.nom}}</div></a>";
                            }
                        ?>
                        <div class="case_tableau" style="width: 50%;">{{operation.nb}}</div>
                        <div class="case_tableau">{{operation.achat.nom}}</div>
                        <div class="case_tableau" style="width: 50%;">{{prix(operation.prix)}}</div>
                        <?php
                            if ($_GET["id"]==0 and $_SESSION["droit"]=="cercle") {
                                echo "<div class=\"case_tableau\" style=\"width: 75%;\"><div class='bouton' style='padding: 5px;' ng-click='annule(operation)'>Annuler</div></div>";
                            }
                        ?>
                    </div>
                </div>
            </div>

    	</div>

        <script src="js/angularjs.js" type="text/javascript"></script>
        <?php
        echo"<script type=\"text/javascript\">var id_search=".$_GET["id"]."</script>";
        echo "<script src=\"js/compte_app.js?".time()."\" type=\"text/javascript\"></script>";
        ?>

    </body>
