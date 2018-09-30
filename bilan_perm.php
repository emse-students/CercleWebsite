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
        <title>Cercle EMSE-Bilan des perms</title>
    </head>

    <body ng-app="bilan_perm_app"  ng-controller="mainController" id="message">
    	<?php
        $page=5;

    	include("php/header.php");
    	?>
    	<div class="page">

            <h1>Bilan des perms</h1>
            <div class="C_centre">
                <div class="formulaire" style="min-height: 25em; padding: 0;">
                    <div class="L_center" >
                        <div ng-if="message.statu=='ok'" style="color: green">{{message.texte}}</div>
                        <div ng-if="message.statu=='error'" style="color: red">{{message.texte}}</div>
                    </div>
                    <div ng-repeat="perm in list_perm">
                      <div class="accordion" ng-class="perm.class" ng-click="perm.activate(); perm.init();">
                          <div class="L_left" style="width: 80%;">
                            <div style="height: 20px; width: 20px;" ng-if="perm.screen">
                                <img style="width :100%;" src="images/accordion_activate.png">
                            </div>
                            <div style="height: 20px; width: 20px;" ng-if="!perm.screen">
                                <img style="width :100%;" src="images/accordion.png">
                            </div>
                            <div style="margin-left: 10px">{{perm.nom}} du {{perm.date}}</div>
                          </div>
                          <div class="L_right" style="width: 20%;">
                            <div style="margin-right: 10px">
                              {{prix(perm.total_vente)}}
                            </div>
                          </div>
                      </div>
                      <div ng-if="perm.screen" class="screen" style="padding:0;"> 
                        <div class="centreur">
                          <div class="C_centre">
                              <div class="L_tableau" >
                                  <div class="head_tableau " style="width: 50%;">Nombre</div>
                                  <div class="head_tableau ">Nom</div>
                                  <div class="head_tableau ">Contenant</div>
                                  <div class="head_tableau ">Total litre</div>
                                  <div class="head_tableau ">Total prix</div>
                              </div>
                              <div class="L_tableau" ng-class="color($index)"ng-repeat="data in perm.datas ">
                                  <div class="case_tableau" style="width: 50%;">{{data.nb}}</div>
                                  <div class="case_tableau">{{data.nom}}</div>
                                  <div class="case_tableau" ng-if="data.type=='C'"></div>
                                  <div class="case_tableau" ng-if="data.type!='C'">{{data.contenant}}</div>
                                  <div class="case_tableau" ng-if="data.type=='C'"></div>
                                  <div class="case_tableau" ng-if="data.type!='C'">{{data.total_litre}}L</div>
                                  <div class="case_tableau">{{prix(data.prix)}}</div>
                              </div>
                          </div>
                        </div>
            	       </div>
                </div>
            </div>
          </div>
        </div>
        <script src="js/angularjs.js" type="text/javascript"></script>

        <?php
        echo "<script src=\"js/bilan_perm_app.js?".time()."\" type=\"text/javascript\"></script>";
        ?>


    </body>
