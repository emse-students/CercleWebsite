<?php
session_start();
include ("php/connexion.php");
if (isset($_GET["modif"]) and $_GET["modif"]) {
    $modif=true;
}else{
    $modif=false;
}

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
        <title>Cercle EMSE-Ouvrir une perm</title>
    </head>

    <body ng-app="open_perm_app"  ng-controller="mainController">
    	<?php
        $page=3;

    	include("php/header.php");
    	?>
    	<div class="page">
            <?php
            if ($modif) {
                echo "<h1>Modifier la perm</h1>";
            }else{
                echo "<h1>Ouvrir une perm</h1>";
            } ?>
            <div class="C_centre">
                <?php
                if (!$modif) { ?>
                    <a href="php/open_perm.php">
                        <div class="bouton">
                            <div class="centreur">
                                <div style="padding-right: 5px;">Reouvrir la dernière perm : </div>
                                <div class="C_bouton" >
                                    <div >Perm : {{derniere_perm.nom}} </div>
                                    <div >Date : {{derniere_perm.date}} </div>
                                    <div >Total des ventes : {{derniere_perm.total_vente}} </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    <div class="info">ou</div>

                <?php } ?>
                <div class="formulaire" ng-if="!droit">
                    <div class="info">Accès réservé aux membres Cercle</div>
                    <div class="L_center">
                        <div><input type="number" ng-model="code_cercle"></div>
                    </div>
                    <div class="centreur"><div class="bouton" ng-click="decode(code_cercle);">Ok</div></div>
                </div>
                <div class="formulaire" ng-if="droit">
                    <?php
                    if (!$modif) { ?>
                        <div class="info">Ouvrir une nouvelle perm</div>
                        <div class="info"></div>
                        <div class="L_left">
                            <div class="item_formulaire" >Nom de la perm :</div>
                            <!-- zone de saisie déclenchant l'autocomplétion -->
                            <div>
                                <input type="text" placeholder="Tapez le nom de la perm" ng-model="new_perm.perm_name" style="font-size: 1.5em; width: 15em;" autocomplete="off" ng-click="new_perm.auto_c=true;"/>

                                <div class="auto_c" ng-if="new_perm.auto_c && new_perm.perm_name!=null">
                                    <div class="auto_c_value" ng-repeat="item in permslist | filter : new_perm.perm_name" ng-click="new_perm.perm_name=item.nom; new_perm.auto_c=false;">{{item.nom}}</div>
                                </div>
                            </div>

                            <span ng-if="!isin_perm(new_perm.perm_name) && new_perm.perm_name!=null" style="width: 30px; margin: 0.5em" >
                                    <img style="width: 100%;" src="images/false.png">
                                </span>
                            <span class="info" ng-if="!isin_perm(new_perm.perm_name) && new_perm.perm_name!=null" style="font-size: 0.8em; color: red margin: auto;">
                                Sélectionnez un nom valide
                            </span>
                            <span class="info" ng-if="isin_perm(new_perm.perm_name)" style="width: 30px; margin: 0.5em"><img style="width: 100%;" src="images/correct.png"></span>
                        </div>
                    <?php }
                    if ($forum)
                    { ?>
                      <div class="L_left">
                          <div class="item_formulaire">Boissons en bourse :</div>
                      </div>
                      <div class="inventaire">
                          <div class="item_inventaire " ng-repeat="boisson in derniere_perm.forums" ng-class="color_boisson(boisson)">
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
                                      <div class="clickable" style="height: 30px; width: 30px;" ng-click="delete_forum(boisson)">
                                          <img style="width :100%;" src="images/croix rouge.jpg">
                                      </div>
                                  </div>
                              </div>
                              <div class="L_center"><span class="nom_item" data-fittext  data-fittext-max="25">{{boisson.nom}}</span></div>
                              <div class="L_left">
                                  <div class="info_inventaire">Type : {{boisson.type}}</div>
                              </div>
                              <div class="L_left">
                                  <div class="info_inventaire">Prix :</div>
                              </div>
                              <div class="L_space_a">
                                  <div class="clickable" style="height: 30px; width: 30px;" ng-click="moins(boisson)">
                                      <img style="width :100%;" src="images/moins.png">
                                  </div>
                                  <div class="prix" ng-click="boisson.prix=true;" ng-if="!boisson.prix">{{prix(boisson.prix_vente)}}</div>
                                  <input type="number" ng-if="boisson.prix" ng-model="boisson.prix_vente" style="width: 60px;">
                                  <div class="clickable" style="height: 30px; width: 30px;" ng-click="plus(boisson)">
                                      <img style="width :100%;" src="images/plus.png">
                                  </div>
                              </div>
                              <div class="L_center" ng-if="boisson.prix" ng-click="boisson.prix=false;"><div class="bouton clickable">Ok</div></div>
                          </div>

                          <div class="clickable" style="height: 100px; width: 100px; margin: 70px 20px 70px 20px;" ng-click="select_forum()">
                              <img style="width :100%;" src="images/plus item.png">
                          </div>
                      </div>
                    <?php
                    }
                    ?>
                    <div class="L_left">
                        <div class="item_formulaire">Boissons :</div>
                    </div>
                    <div class="inventaire">
                        <div class="item_inventaire " ng-repeat="boisson in derniere_perm.boissons" ng-class="color_boisson(boisson)">
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
                                    <div class="clickable" style="height: 30px; width: 30px;" ng-click="delete_boisson(boisson)">
                                        <img style="width :100%;" src="images/croix rouge.jpg">
                                    </div>
                                </div>
                            </div>
                            <div class="L_center"><span class="nom_item" data-fittext  data-fittext-max="25">{{boisson.nom}}</span></div>
                            <div class="L_left">
                                <div class="info_inventaire">Type : {{boisson.type}}</div>
                            </div>
                            <div class="L_left">
                                <div class="info_inventaire">Prix :</div>
                            </div>
                            <div class="L_space_a">
                                <div class="clickable" style="height: 30px; width: 30px;" ng-click="moins(boisson)">
                                    <img style="width :100%;" src="images/moins.png">
                                </div>
                                <div class="prix" ng-click="boisson.prix=true;" ng-if="!boisson.prix">{{prix(boisson.prix_vente)}}</div>
                                <input type="number" ng-if="boisson.prix" ng-model="boisson.prix_vente" style="width: 60px;">
                                <div class="clickable" style="height: 30px; width: 30px;" ng-click="plus(boisson)">
                                    <img style="width :100%;" src="images/plus.png">
                                </div>
                            </div>
                            <div class="L_center" ng-if="boisson.prix" ng-click="boisson.prix=false;"><div class="bouton clickable">Ok</div></div>
                        </div>

                        <div class="clickable" style="height: 100px; width: 100px; margin: 70px 20px 70px 20px;" ng-click="select_boisson()">
                            <img style="width :100%;" src="images/plus item.png">
                        </div>
                    </div>

                    <div class="L_left">
                        <div class="item_formulaire">Autres :</div>
                    </div>
                    <div class="inventaire">
                        <div class="item_inventaire autre" ng-repeat="consommable in derniere_perm.consommables">
                            <div class="L_right">
                                <div class="clickable" style="height: 30px; width: 30px;" ng-click="delete_consommable(consommable)">
                                    <img style="width :100%;" src="images/croix rouge.jpg">
                                </div>
                            </div>

                            <div class="L_center"><span class="titre_item" data-fittext  data-fittext-max="25">{{consommable.nom}}</span></div>

                            <div class="L_left">
                                <div class="info_inventaire">Type : Autre</div>
                            </div>

                            <div class="L_left">
                                <div class="info_inventaire">Prix :</div>
                            </div>

                            <div class="L_space_a">
                                <div class="clickable" style="height: 30px; width: 30px;" ng-click="moins(consommable)">
                                    <img style="width :100%;" src="images/moins.png">
                                </div>
                                <div class="prix" ng-click="consommable.prix=true;" ng-if="!consommable.prix">{{prix(consommable.prix_vente)}}</div>
                                <input type="number" ng-if="consommable.prix" ng-model="consommable.prix_vente" style="width: 60px;">
                                <div class="clickable" style="height: 30px; width: 30px;" ng-click="plus(consommable)">
                                    <img style="width :100%;" src="images/plus.png">
                                </div>
                            </div>
                            <div class="L_center" ng-if="consommable.prix" ng-click="consommable.prix=false;"><div class="bouton clickable">Ok</div></div>
                        </div>

                        <div class="clickable" style="height: 100px; width: 100px; margin: 70px 20px 70px 20px;" ng-click="select_consommable()">
                            <img style="width :100%;" src="images/plus item.png">
                        </div>
                    </div>
                    <?php
                    if (!$modif) {
                        echo '<div class="centreur"><div class="bouton" ng-click="open_new_perm()">Valider</div></div>';
                    }else{
                        echo '<div class="centreur"><div class="bouton" ng-click="maj_perm()">Valider</div></div>';
                    }?>
                </div>
            </div>
    	</div>

        <div class="layer" ng-if="layer"></div>

        <div class="layer2" ng-if="layer2">
            <div class="layer_window">
                <div class="L_right"><div class="clickable" style="width: 30px;" ng-click="esc()"><img style="width :100%;" src="images/croix rouge.jpg"></div></div>
                <div class="C_centre">
                    <div class="info">Ajoutez une boisson</div>
                    <div class="info"></div>
                    <div class="L_left">
                        <div class="item_formulaire" >Nom :</div>
                        <!-- zone de saisie déclenchant l'autocomplétion -->

                        <div>
                            <input type="text" placeholder="Tapez le nom de la boisson" ng-model="new_boisson.boisson_name" style="font-size: 1em; width: 13em;" autocomplete="off" ng-click="new_boisson.auto_c=true;"/>

                            <div class="auto_c" ng-if="new_boisson.auto_c && new_boisson.boisson_name!=''">
                                <div class="auto_c_value" ng-repeat="item in contenus | filter : new_boisson.boisson_name" ng-click="new_boisson.contenu=item; new_boisson.boisson_name=item.nom; new_boisson.contenu.contenant=new_boisson.contenu.contenants[0]; new_boisson.auto_c=false;">{{item.nom}}</div>
                            </div>
                        </div>

                        <span ng-if="new_boisson.contenu==null && new_boisson.boisson_name!=null" style="width: 30px; margin: 0.5em" >
                                <img style="width: 100%;" src="images/false.png">
                            </span>
                        <span class="info" ng-if="new_boisson.contenu==null && new_boisson.boisson_name!=null" style="font-size: 0.8em; color: red margin: auto;">
                            Sélectionnez un nom valide
                        </span>
                        <span class="info" ng-if="new_boisson.contenu!=null" style="width: 30px; margin: 0.5em"><img style="width: 100%;" src="images/correct.png"></span>
                    </div>
                    <div class="L_left" ng-if="new_boisson.contenu!=null">
                        <div class="item_formulaire">Contenant : </div>
                        <select ng-options="contenant.nom for contenant in new_boisson.contenu.contenants" ng-model="new_boisson.contenu.contenant"></select>
                    </div>
                    <div class="item_inventaire" ng-if="new_boisson.contenu==null"> </div>
                    <div class="item_inventaire" ng-if="new_boisson.contenu!=null" ng-class="color_boisson(new_boisson.contenu)">
                        <div class="L">
                            <div class="L_left">
                                <div style="height: 30px; width: 30px; " ng-if="new_boisson.contenu!='inconnu'">
                                    <img style="height: 100%; width: auto;" src="images/{{new_boisson.contenu.contenant.type}}.png">
                                </div>
                                <div style="height: 30px; width: 30px; margin-left:10px;" ng-if="(new_boisson.contenu.contenant.type=='bouteille_unique' || new_boisson.contenu.contenant.type=='bouteille_partage') && new_boisson.contenu.contenant.consigne>0">
                                    <img style="width :100%;" src="images/consigne.png">
                                </div>
                            </div>
                        </div>
                        <div class="L_center"><span class="titre_item" data-fittext  data-fittext-max="25">{{new_boisson.contenu.nom}}</span></div>
                        <div class="L_left">
                            <div class="info_inventaire">Type : {{new_boisson.contenu.type}}</div>
                        </div>
                        <div class="L_left">
                            <div class="info_inventaire">Prix :</div>
                        </div>
                        <div class="L_center">

                            <div class="prix">{{prix(new_boisson.contenu.contenant.prix_vente)}}</div>

                        </div>
                    </div>
                    <div class="centreur">
                      <div class="bouton" ng-if="!boisson_forum" ng-click="add_boisson(new_boisson.contenu)">Valider</div>
                      <div class="bouton" ng-if="boisson_forum" ng-click="add_forum(new_boisson.contenu)">Valider</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="layer2" ng-if="layer3">
            <div class="layer_window">
                <div class="L_right"><div class="clickable" style="width: 30px;" ng-click="esc()"><img style="width :100%;" src="images/croix rouge.jpg"></div></div>
                <div class="C_centre">
                    <div class="info">Ajoutez un produit</div>
                    <div class="info"></div>
                    <div class="L_left">
                        <div class="item_formulaire" >Nom :</div>
                        <!-- zone de saisie déclenchant l'autocomplétion -->
                        <div>
                            <input type="text" placeholder="Tapez le nom du produit" ng-model="new_consommable.consommable_name" style="font-size: 1em; width: 13em;" autocomplete="off" ng-click="new_consommable.auto_c=true;"/>

                            <div class="auto_c" ng-if="new_consommable.auto_c && new_consommable.consommable_name!=''">
                                <div class="auto_c_value" ng-repeat="item in consommables | filter : new_consommable.consommable_name" ng-click="new_consommable.consommable_name=item.nom; new_consommable.auto_c=false;">{{item.nom}}</div>
                            </div>
                        </div>


                        <br>
                        <span ng-if="!isin_consommables(new_consommable.consommable_name) && new_consommable.consommable_name!=null" style="width: 30px; margin: 0.5em" >
                                <img style="width: 100%;" src="images/false.png">
                        </span>
                        <span class="info" ng-if="!isin_consommables(new_consommable.consommable_name) && new_consommable.consommable_name!=null" style="font-size: 0.8em; color: red margin: auto;">
                            Ce produit n'éxiste pas,<br> le créer ?
                        </span>
                        <span class="bouton" ng-if="!isin_consommables(new_consommable.consommable_name) && new_consommable.consommable_name!=null"
                              style="size: 0.6em; padding: 4px; margin-left: 10px;" ng-click="new_consommable.new_consommable.nom=new_consommable.consommable_name; new_consommable.new=true;">
                            Créer
                        </span>

                        <span class="info" ng-if="isin_consommables(new_consommable.consommable_name)" style="width: 30px; margin: 0.5em"><img style="width: 100%;" src="images/correct.png"></span>
                    </div>
                    <div class="item_inventaire" ng-if="!isin_consommables(new_consommable.consommable_name) && !new_consommable.new"> </div>
                    <div class="item_inventaire autre" ng-if="isin_consommables(new_consommable.consommable_name) || new_consommable.new">
                        <div class="L">
                            <div class="L_left">
                                <div style="height: 30px; width: 30px; " ng-if="find_consommables(new_consommable.consommable_name).fut_bouteille!='inconnu'">
                                    <img style="height: 100%; width: auto;" src="images/{{find_consommables(new_consommable.consommable_name).fut_bouteille}}.png">
                                </div>
                                <div style="height: 30px; width: 30px; margin-left:10px;" ng-if="find_consommables(new_consommable.consommable_name).fut_bouteille=='bouteille' && find_consommables(new_consommable.consommable_name).consigne>0">
                                    <img style="width :100%;" src="images/consigne.png">
                                </div>
                            </div>
                        </div>
                        <div class="L_center"><span class="titre_item">{{find_consommables(new_consommable.consommable_name).nom}}</span></div>
                        <div class="L_left">
                            <div class="info_inventaire">Type : Autres</div>
                        </div>
                        <div class="L_left">
                            <div class="info_inventaire">Prix :</div>
                        </div>
                        <div class="L_center">

                            <div class="prix">{{prix(find_consommables(new_consommable.consommable_name).prix_vente)}}</div>

                        </div>
                    </div>
                    <div class="centreur"><div class="bouton" ng-click="add_consommable(find_consommables(new_consommable.consommable_name))">Valider</div></div>
                </div>
            </div>
        </div>


        <script src="js/angularjs.js" type="text/javascript"></script>
        <script src="js/ng-FitText.js" type="text/javascript"></script>
        <?php

        echo "<script type=\"text/javascript\">var droit_cercle='".$_SESSION['droit_cercle']."'</script>";
        if ($forum) {
          echo "<script type=\"text/javascript\">var forum=true;</script>";
        }else{
          echo "<script type=\"text/javascript\">var forum=false;</script>";
        }

        echo "<script src=\"js/open_perm_app.js?".time()."\" type=\"text/javascript\"></script>";
        ?>


    </body>
