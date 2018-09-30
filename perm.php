<?php
session_start();
include ("php/connexion.php");

$req = $bdd -> query("SELECT id, nom, valeur FROM constante where id=4 ");

$i=0;
while ($donnees = $req->fetch())
{
	if($donnees['valeur']==1){
    $forum=true;
  }else{
    $forum=false;
  }
}
?>

<!DOCTYPE html>

<html id="page">

    <head>

        <meta charset="utf-8" />
        <?php
        echo "<link rel='stylesheet' href='css/style.css?".time()."'/>";
        ?>
        <link rel="icon" type="image/png" href="images/touchicon.png" />
        <link rel="apple-touch-icon" href="images/appleicon.png" />
        <title>Cercle EMSE - Perm</title>
    </head>

    <body ng-app="perm_app"  ng-controller="mainController">
    	<?php
        $page=4;

    	include("php/header.php");
    	?>
    	<div class="page">
            <h1>{{perm.nom}}</h1>
            <div class="L_space_b">
                <div class="info" style="display: flex; margin-top: 10px; align-items: center;">Total des ventes : {{arrondi(perm.total_litre)}}L soit {{prix(perm.total_vente)}}</div>
                <div class="bouton" ng-click="crack_boursier()" ng-if="forum==1">
                    Krach Boursier
                </div>
                <a href="open_perm.php?modif=true">
                    <div class="bouton L" style="padding: 2px; margin: 10px;">
                        <div class="clickable" style="height: 35px; width: 35px;">
                            <img style="width :100%;" src="images/logo-config.png">
                        </div>
                        Modifier la perm
                    </div>
                </a>
            </div>
            <div class="C_centre">
                <div class="formulaire" ng-if="forum==1">
                    <div class="inventaire">
                        <div style="margin-left:10px; margin-bottom:10px; border:solid 2px rgb(200,51,30); padding:3px; border-radius:5px; color: rgb(200,51,30);" ng-repeat="boisson in data">
                            <div class="C_center">
                                    <div>
                                        {{boisson.key}} <br /> Prix normal : <span ng-click="boisson.change_prix=true;" ng-if="!boisson.change_prix">{{prix(boisson.prix)}}</span>
                                    </div>
                                    <div class="L_center" ng-if="boisson.change_prix">
                                        <div>
                                            <input type="number"   ng-model="boisson.prix" style="width: 50px;">
                                        </div>
                                        <div style="margin-left:5px;">
                                            <span ng-if="boisson.change_prix" ng-click="boisson.change_prix=false; maj_forum();" class="bouton clickable" style="font-size:0.8em; padding:3px;">Ok</span>
                                        </div>

                                    </div>
                                    <div>
                                        <span style="margin-left:5px;">  Bilan : {{prix(boisson.values)}}</span>
                                    </div>
                                </div>
                        </div>
                        <div class="L_center">
                            Total des écarts avec les prix réels : {{prix(total_forum)}}
                        </div>
                    </div>
                </div>

                <div class="formulaire" id="perm">
                    <h2 ng-if="histo.length>0">Historique des 10 dernières opérations :</h2>
                    <div class="inventaire" ng-if="histo.length>0">
                        <div style="margin-left:10px; margin-bottom:10px; border:solid 2px rgb(200,51,30); padding:3px; border-radius:5px; color: rgb(200,51,30);" ng-repeat="transaction in histo">
                            <div class="C_center">
                                <div>
                                    {{transaction.prenom}} {{transaction.nom}}
                                </div>
                                <div>
                                    {{transaction.nb}}
                                </div>
                                <div>
                                    {{transaction.nom_article}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="L_left">
                        <div class="item_formulaire" style="margin-left: 10%; ">Client :</div>
                        <!-- zone de saisie déclenchant l'autocomplétion -->
                        <div ng-if="perm.client==null">
                            <input type="text" placeholder="Tapez le nom du client" ng-model="client.search" style="font-size: 1.5em; width: 13em;" autocomplete="off" ng-click="client.auto_c=true; perm.client=null;"/>

                            <div class="auto_c" ng-if="client.auto_c && client.search!=null">
                                <div class="auto_c_value" ng-repeat="item in users | filter : client.search" ng-click="perm.client=item; client.auto_c=false; maj();">{{item.prenom}} {{item.nom}}</div>
                            </div>
                        </div>
                        <div class="L_left" ng-if="perm.client==null">
                            <span ng-if="check()==2 && client.search!=null" style="width: 30px; margin: 0.5em" >
                                <img style="width: 100%;" src="images/false.png">
                            </span>
                            <span class="info" ng-if="check()!=0 && client.search!=null" style="font-size: 0.8em; color: red margin: auto;">
                                {{info_check()}}
                            </span>
                            <span ng-if="check()==0 && client.search!=null" style="width: 30px; margin: 0.5em" >
                                <img style="width: 100%;" src="images/correct.png">
                            </span>
                        </div>

                        <div class="info_user" ng-class="color_check()">
                            <div class="L" ng-if="perm.client!=null">
                                <div class="L_left">
                                    <span  class="no_text_wrap" ng-if="check()!=0 && client.search!=null" style="font-size: 0.9em;">
                                        {{info_check()}}
                                    </span>
                                </div>
                                <div class="L_right">
                                    <span class="clickable" ng-if="perm.client!=null" style="width: 30px; margin: 0.5em" ng-click="perm.client=null; client.search=null;">
                                        <img style="width: 100%;" src="images/croix rouge.jpg">
                                    </span>

                                </div>
                            </div>

                            <div class="L_left" ng-if="perm.client!=null">
                                <span  style="font-size: 1.8em;">{{perm.client.prenom}} {{perm.client.nom}} </span><span  style="font-size: 1.2em; margin-left: 10px;"> promo {{perm.client.promo}}</span>
                            </div>
                            <div class="L_space_a" ng-if="perm.client!=null">
                                <span>Solde : {{prix(perm.client.solde)}}</span> <span>Total achat : {{prix(perm.client.total)}} ({{arrondi(perm.client.total_litre)}}L)</span>
                            </div>
                            <div class="L_left" ng-if="perm.client!=null">
                                <span>Solde après transaction : </span><span style="font-size: 1.8em; margin-left: 10px;"> {{prix(perm.client.new_solde)}}</span>
                            </div>
                        </div>
                        <div class="bouton" ng-click="validate()" style="margin-left: 5%;" ng-if="perm.client!=null && perm.total!=0">Encaisser</div>
                    </div>

                    <div class="inventaire">
<!--                        boissons forum-->
                        <div class="item_inventaire clickable noselect" ng-if="forum==1" ng-repeat="boisson in perm.forums" ng-class="color_boisson(boisson)" ng-click="plus(boisson); maj();">

                            <div class="L">
                                <div class="L_left">
                                    <div style="height: 30px; width: 30px; " ng-if="boisson.fut_bouteille!='inconnu'">
                                        <img style="height: 100%; width: auto;" src="images/{{boisson.fut_bouteille}}.png">
                                    </div>
                                    <div style="height: 30px; width: 30px; margin-left:10px;" ng-if="(boisson.fut_bouteille=='bouteille_unique' || boisson.fut_bouteille=='bouteille_partage')  && boisson.consigne>0">
                                        <img style="width :100%;" src="images/consigne.png">
                                    </div>
                                </div>
                                <div class="L_center" ng-if="boisson.fut_bouteille=='bouteille_unique'">{{boisson.capacite}}L</div>
                                <div class="L_center" ng-if="boisson.fut_bouteille!='bouteille_unique'">0.25L</div>
                                <div class="L_right">
                                  <div style="height: 30px; width: 30px; margin-right:10px;">
                                      <img style="width :100%;" src="images/forum.png">
                                  </div>
                                  <div class="clickable" style="height: 30px; width: 30px;" ng-click="moins(boisson)">
                                      <img style="width :100%;" src="images/moins.png">
                                  </div>
                                </div>
                            </div>
                            <div class="L_center"><span class="nom_item" data-fittext data-fittext-max="25">{{boisson.nom}}</span></div>
                            <div class="L_left">
                                <div class="info_inventaire">Type : {{boisson.type}}</div>
                            </div>
                            <div class="L_left">
                                <div class="info_inventaire">Prix : <span style="font-size: 1.5em;">{{prix(boisson.prix_vente)}}</span></div>
                            </div>
                            <div class="L_space_a" style="align-items: center">
                                <div class="info_inventaire" >Quantité :</div>
                                <div class="prix">{{boisson.quantite}}</div>
                            </div>
                            <div class="L_space_a" style="align-items: center">
                                <div class="info_inventaire" style="font-size: 0.9em;">Degré : {{boisson.degre}}°</div>
                                <div class="info_inventaire" ><span style="font-size: 0.8em;">Cuite/Prix : </span><span ng-style="colorLader(cuitePrix(boisson))" style="border-radius: 0.5em; padding: 3px;">{{arrondi(cuitePrix(boisson))}}</span></div>
                            </div>
                        </div>
<!--                        boissons-->
                        <div class="item_inventaire clickable noselect" ng-repeat="boisson in perm.boissons | orderBy : 'order'" ng-class="color_boisson(boisson)" ng-click="plus(boisson); maj();">

                            <div class="L">
                                <div class="L_left">
                                    <div style="height: 30px; width: 30px; " ng-if="boisson.fut_bouteille!='inconnu'">
                                        <img style="height: 100%; width: auto;" src="images/{{boisson.fut_bouteille}}.png">
                                    </div>
                                    <div style="height: 30px; width: 30px; margin-left:10px;" ng-if="(boisson.fut_bouteille=='bouteille_unique' || boisson.fut_bouteille=='bouteille_partage')  && boisson.consigne>0">
                                        <img style="width :100%;" src="images/consigne.png">
                                    </div>
                                </div>
                                <div class="L_center" ng-if="boisson.fut_bouteille=='bouteille_unique'">{{boisson.capacite}}L</div>
                                <div class="L_center" ng-if="boisson.fut_bouteille!='bouteille_unique'">0.25L</div>
                                <div class="L_right">
                                    <div class="clickable" style="height: 30px; width: 30px;" ng-click="moins(boisson)">
                                        <img style="width :100%;" src="images/moins.png">
                                    </div>
                                </div>
                            </div>
                            <div class="L_center"><span class="nom_item" data-fittext data-fittext-max="25">{{boisson.nom}}</span></div>
                            <div class="L_left">
                                <div class="info_inventaire">Type : {{boisson.type}}</div>
                            </div>
                            <div class="L_left">
                                <div class="info_inventaire">Prix : <span style="font-size: 1.5em;">{{prix(boisson.prix_vente)}}</span></div>
                            </div>
                            <div class="L_space_a" style="align-items: center">
                                <div class="info_inventaire" >Quantité :</div>
                                <div class="prix">{{boisson.quantite}}</div>
                            </div>
                            <div class="L_space_a" style="align-items: center">
                                <div class="info_inventaire" style="font-size: 0.9em;">Degré : {{boisson.degre}}°</div>
                                <div class="info_inventaire" ><span style="font-size: 0.8em;">Cuite/Prix : </span><span ng-style="colorLader(cuitePrix(boisson))" style="border-radius: 0.5em; padding: 3px;">{{arrondi(cuitePrix(boisson))}}</span></div>
                            </div>
                        </div>
<!--                        consommables (non boissons)-->
                        <div class="item_inventaire autre clickable noselect" ng-repeat="consommable in perm.consommables" ng-click="plus(consommable); maj();">

                            <div class="L_right">
                                <div class="clickable" style="height: 30px; width: 30px;" ng-click="moins(consommable)">
                                    <img style="width :100%;" src="images/moins.png">
                                </div>
                            </div>

                            <div class="L_center"><span class="titre_item" data-fittext  data-fittext-max="25">{{consommable.nom}}</span></div>
                            <div class="L_left">
                                <div class="info_inventaire">Type : Autre</div>
                            </div>
                            <div class="L_left">
                                <div class="info_inventaire">Prix : <span style="font-size: 1.5em;">{{prix(consommable.prix_vente)}}</span></div>
                            </div>
                            <div class="L_left">
                                <div class="info_inventaire">Quantité :</div>
                            </div>
                            <div class="L_space_a">

                                <div class="prix" >{{consommable.quantite}}</div>

                            </div>

                        </div>

                        <div class="clickable" style="height: 100px; width: 100px; margin: 70px 20px 70px 20px;" ng-click="select_new_conso()">
                            <img style="width :100%;" src="images/plus item.png">
                        </div>

                    </div>

                    <div class="centreur">
                        <div class="bouton" ng-click="validate()" ng-if="perm.client!=null && perm.total!=0">Encaisser</div>
                    </div>
                </div>
            </div>
            <div class="C_centre" style="margin-top: 20px;">
                <input type="text" placeholder="Tapez le code Cercle" ng-model="happy.happyHourCode" style="font-size: 1.5em; width: 13em;" autocomplete="off"  ng-if="checkCode"/>
                <div ng-if="happyHourCodeFalse">Le code est érroné</div>
                <div class="bouton" ng-click="happyHour()" style="margin-left: 5%;" ng-if="!isHappyHour">Démarrer l'HappyHour</div>
                <div class="bouton" ng-click="happyHour()" style="margin-left: 5%;" ng-if="isHappyHour">Arrêter l'HappyHour</div>
            </div>
    	</div>

        <div class="layer" ng-if="layer"></div>
        <div class="layer2" ng-if="layer2">
            <div class="layer_window">
                <div class="L_right"><div class="clickable" style="width: 30px;" ng-click="esc()"><img style="width :100%;" src="images/croix rouge.jpg"></div></div>
                <div class="C_centre">
                    <div class="info">Achat spécial </div>
                    <div class="info"></div>
                    <div class="L_left">
                        <div class="item_formulaire" >Nom :</div>
                        <!-- zone de saisie déclenchant l'autocomplétion -->

                        <input type="text" placeholder="Tapez le nom du produit" ng-model="new_conso.nom" style="font-size: 1em; width: 13em;" />
                    </div>
                    <div class="L_left">
                        <div class="info_inventaire">Prix :</div>
                    </div>
                    <div class="L_space_a">
                        <div class="clickable" style="height: 30px; width: 30px;" ng-click="moins_new_c(new_conso)">
                            <img style="width :100%;" src="images/moins.png">
                        </div>
                        <div class="prix" ng-click="new_conso.prix=true;" ng-if="!new_conso.prix">{{prix(new_conso.prix_vente)}}</div>
                        <input type="number" ng-if="new_conso.prix" ng-model="new_conso.prix_vente" style="width: 60px;">
                        <div class="clickable" style="height: 30px; width: 30px;" ng-click="plus_new_c(new_conso)">
                            <img style="width :100%;" src="images/plus.png">
                        </div>
                    </div>
                    <div class="L_center" ng-if="new_conso.prix" ng-click="new_conso.prix=false;"><div class="bouton clickable">Ok</div></div>
                    <div class="L_center"><div class="bouton clickable" ng-if="new_conso.prix_vente>0 && new_conso.nom!=null && !new_conso.prix" ng-click="add_new_conso()">Valider</div></div>
                </div>
            </div>
        </div>

        <script src="js/angularjs.js" type="text/javascript"></script>

        <script src="js/ng-FitText.js" type="text/javascript"></script>
        <?php
        if ($forum) {
          echo "<script type=\"text/javascript\">var forum=true;</script>";
        }else{
          echo "<script type=\"text/javascript\">var forum=false;</script>";
        }

        echo "<script src=\"js/perm_app.js?".time()."\" type=\"text/javascript\"></script>";
        ?>


    </body>
