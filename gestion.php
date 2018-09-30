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
        <title>Cercle EMSE-Gestion</title>
    </head>

    <body ng-app="gestion_app"  ng-controller="mainController" id="message">
    	<?php
        $page=7;

    	include("php/header.php");
    	?>
    	<div class="page">

            <h1>Gestion</h1>
            <div class="C_centre">
                <div class="formulaire" style="min-height: 25em; padding: 0;">
                    <div class="L_center" >
                        <div ng-if="message.statu=='ok'" style="color: green">{{message.texte}}</div>
                        <div ng-if="message.statu=='error'" style="color: red">{{message.texte}}</div>
                    </div>

                    <div class="accordion" ng-class="boisson.class" ng-click="boisson.activate(); start_boissons()">
                        <div style="height: 20px; width: 20px;" ng-if="boisson.screen">
                            <img style="width :100%;" src="images/accordion_activate.png">
                        </div>
                        <div style="height: 20px; width: 20px;" ng-if="!boisson.screen">
                            <img style="width :100%;" src="images/accordion.png">
                        </div>
                        <div style="margin-left: 10px">Boissons</div>
                    </div>
                    <div ng-if="boisson.screen" class="screen">
                        <div class="L_left" style="margin-top: 0.8em">
                            <div class="item_formulaire" style="margin-left: 1em; ">Nom de la boisson :</div>
                            <div>
                                <input type="text" placeholder="Taper le nom d'une boisson" ng-model="boisson.search" style="font-size: 1em; width: 15em; margin-left: 0.5em;" autocomplete="off" ng-click="boisson.auto_c=true; message.statu='none';"/>

                                <div class="auto_c" ng-if="boisson.auto_c && boisson.search!=null" style="margin-left: 1em;">
                                    <div class="auto_c_value" ng-repeat="item in contenus_list | filter : boisson.search" ng-click="boisson.contenu=item; boisson.auto_c=false; boisson.search=null">{{item.nom}}</div>
                                </div>
                            </div>
                            <span ng-if="boisson.search!=null" style="width: 30px; margin: 0.5em" >
                                <img style="width: 100%;" src="images/false.png">
                            </span>
                            <span class="info" ng-if="boisson.search!=null" style="font-size: 0.8em; color: red margin: auto;">
                                Cette boisson n'existe pas ? voulez-vous la créer ?
                            </span>
                            <div class="bouton" ng-click="new_contenu(boisson.search); boisson.auto_c=false; boisson.search=null;" ng-if="boisson.search!=null" style="margin-left: 1em; font-size: 1em; padding: 1px;">Créer</div>

                        </div>
                        <div class="L_center" style="margin-top: 0.8em" ng-if="boisson.contenu!=null">
                            <div class="item_formulaire" style="font-size: 2em; " ng-if="!boisson.modif_nom">{{boisson.contenu.nom}}</div>
                            <div class="bouton" ng-click="boisson.modif_nom=true; message.statu='none';" ng-if="!boisson.modif_nom">Modifier</div>
                            <input type="texte" ng-model="boisson.contenu.nom" style="font-size: 2em; width: 15em;" ng-if="boisson.modif_nom">
                            <div class="bouton" ng-click="maj_contenu_nom(boisson.contenu); boisson.modif_nom=false;" ng-if="boisson.modif_nom">Enregistrer</div>

                        </div>
                        <div class="L_left" style="margin-top: 0.8em" ng-if="boisson.contenu!=null">
                            <div class="item_formulaire" style="margin-left: 1em;">Type :</div>
                            <div class="item_formulaire" ng-if="!boisson.modif_type">{{boisson.contenu.type}}</div>
                            <div class="bouton" ng-click="boisson.modif_type=true; message.statu='none';" ng-if="!boisson.modif_type">Modifier</div>
                            <select name="select_type"  ng-model="boisson.contenu.type" style="font-size: 1em; min-width: 15.3em; margin-left: 0.5em;" ng-if="boisson.modif_type">
                                <option value="Blonde">Blonde</option>
                                <option value="Blanche">Blanche</option>
                                <option value="Brune">Brune</option>
                                <option value="Ambrée">Ambrée</option>
                                <option value="Rouge">Rouge</option>
                                <option value="Aromatisée">Aromatisée</option>
                                <option value="Vin">Vin</option>
                                <option value="Autre">Autre</option>
                                <option value="Cidre">Cidre</option>
                            </select>
                            <div class="bouton" ng-click="maj_contenu_type(boisson.contenu); boisson.modif_type=false;" ng-if="boisson.modif_type">Enregistrer</div>
                        </div>
                        <div class="L_left" style="margin-top: 0.8em" ng-if="boisson.contenu!=null">
                            <div class="item_formulaire" style="margin-left: 1em;">Degré : </div>
                            <div class="item_formulaire" style="font-size: 2em; " ng-if="!boisson.modif_degre">{{boisson.contenu.degre}}</div>
                            <div class="bouton" ng-click="boisson.modif_degre=true; message.statu='none';" ng-if="!boisson.modif_degre">Modifier</div>
                            <input type="number" ng-model="boisson.contenu.degre" style="font-size: 2em; width: 10em;" ng-if="boisson.modif_degre">
                            <div class="bouton" ng-click="maj_contenu_degre(boisson.contenu); boisson.modif_degre=false;" ng-if="boisson.modif_degre">Enregistrer</div>
                        </div>
                        <div class="L_left" style="margin-top: 0.8em" ng-if="boisson.contenu!=null">
                            <div class="item_formulaire" style="margin-left: 1em;">Contenants :</div>
                        </div>
                        <div class="L_left" style="margin-top: 0.4em" ng-if="boisson.contenu!=null" ng-repeat="contenant in boisson.contenu.contenants">
                            <div class="item_formulaire" style="margin-left: 4em; font-size: 0.9em;">{{contenant.nom}}</div>
                            <div class="clickable" style="height: 20px; width: 20px;" ng-click="maj_contenu_contenant(boisson.contenu,-contenant.id);">
                                <img style="width :100%;" src="images/croix rouge.jpg">
                            </div>
                            <div class="item_formulaire" style="margin-left: 1em; font-size: 0.9em;" ng-if="contenant.consigne==0 && !contenant.modif_consigne">Pas de consigne</div>
                            <div class="item_formulaire" style="margin-left: 1em; font-size: 0.9em;" ng-if="contenant.consigne>0 && !contenant.modif_consigne">Consigne {{prix(contenant.consigne)}}</div>
                            <div class="bouton" ng-click="contenant.modif_consigne=true;" ng-if="!contenant.modif_consigne" style="margin-left: 1em; font-size: 0.9em; padding: 1px;">Modifier</div>
                             <input type="number" ng-model="contenant.consigne" style="font-size: 0.9em; width: 5em;" ng-if="contenant.modif_consigne">
                            <div class="bouton" ng-click="maj_contenu_contenant_consigne(boisson.contenu,contenant); contenant.modif_consigne=false;" ng-if="contenant.modif_consigne" style="margin-left: 1em; font-size: 0.9em; padding: 1px;">Enregistrer</div>
                        </div>
                        <div class="L_left" style="margin-top: 0.4em" ng-if="boisson.contenu!=null">
                            <div class="clickable" style="height: 20px; width: 20px; margin-left: 4em;" ng-click="boisson.add_contenant=true;" ng-if="!boisson.add_contenant">
                                <img style="width :100%;" src="images/plus item.png">
                            </div>
                            <div class="item_formulaire" style="margin-left: 4em; font-size: 0.9em;" ng-if="boisson.add_contenant">Nom du nouveau contenant :</div>
                            <div ng-if="boisson.add_contenant">
                                <input type="text" placeholder="Taper le nom du contenant" ng-model="boisson.search2" style="font-size: 0.9em; width: 15em; margin-left: 0.5em;" autocomplete="off" ng-click="boisson.auto_c2=true; message.statu='none';"/>

                                <div class="auto_c" ng-if="boisson.auto_c2 && boisson.search2!=null" style="margin-left: 0.9em;">
                                    <div class="auto_c_value" ng-repeat="item in contenants_list | filter : boisson.search2" ng-click="maj_contenu_contenant(boisson.contenu,item.id); boisson.auto_c2=false; boisson.search2=null; boisson.add_contenant=false;">{{item.nom}}</div>
                                </div>
                            </div>
                            <span ng-if="boisson.search2!=null" style="width: 30px; margin: 0.5em" >
                                <img style="width: 100%;" src="images/false.png">
                            </span>
                            <span class="info" ng-if="boisson.search2!=null" style="font-size: 0.8em; color: red margin: auto;">
                                Sélectionner un nom de contenant
                            </span>
                        </div>
                        <div class="L_left" style="margin-top: 0.8em" ng-if="boisson.contenu!=null">
                            <div class="item_formulaire" style="margin-left: 1em;">Description :</div>
                            <div class="item_formulaire" style="font-size: 1em; " ng-if="!boisson.modif_description">{{boisson.contenu.description}}</div>
                            <div class="bouton" ng-click="boisson.modif_description=true; message.statu='none';" ng-if="!boisson.modif_description">Modifier</div>
                            <input type="texte" ng-model="boisson.contenu.description" style="font-size: 1em; width: 15em;" ng-if="boisson.modif_description">
                            <div class="bouton" ng-click="maj_contenu_description(boisson.contenu); boisson.modif_description=false;" ng-if="boisson.modif_description">Enregistrer</div>
                        </div>

                    </div>

                    <div class="accordion" ng-class="contenant.class" ng-click="contenant.activate(); start_boissons();">
                        <div style="height: 20px; width: 20px;" ng-if="contenant.screen">
                            <img style="width :100%;" src="images/accordion_activate.png">
                        </div>
                        <div style="height: 20px; width: 20px;" ng-if="!contenant.screen">
                            <img style="width :100%;" src="images/accordion.png">
                        </div>
                        <div style="margin-left: 10px">Contenants</div>
                    </div>
                    <div ng-if="contenant.screen" class="screen">
                        <div class="L_left" ng-repeat="contenant in contenants_list" style="margin-top: 0.8em;">
                            <div class="item_formulaire" style="margin-left: 1em;">Nom :</div>
                            <div class="item_formulaire2" ng-if="!contenant.modif">{{contenant.nom}}</div>
                            <input type="text" ng-model="contenant.nom" ng-if="contenant.modif">
                            <div class="item_formulaire">Capacité :</div>
                            <div class="item_formulaire2"  ng-if="!contenant.modif">{{contenant.capacite}}L</div>
                            <input type="number" ng-model="contenant.capacite" ng-if="contenant.modif">
                            <div class="item_formulaire" >Type :</div>
                            <div class="item_formulaire2" ng-if="!contenant.modif">{{contenant_type(contenant.type)}}</div>
                            <select name="select_type"  ng-model="contenant.type" style="font-size: 1em; min-width: 15.3em; margin-left: 0.5em; margin-right: 0.5em;" ng-if="contenant.modif">
                              <option value="fut">Fût</option>
                              <option value="bouteille_unique">Bouteille vendue entière</option>
                              <option value="bouteille_partage">Bouteille servie en eco cup</option>
                              <option value="cubi">Cubi</option>
                              <option value="verre">Eco Cup</option>
                            </select>
                            <div class="bouton" ng-click="contenant.modif=true; message.statu='none';" ng-if="!contenant.modif">Modifier</div>
                            <div class="bouton" ng-click="valid_contenant(contenant); contenant.modif=false; " ng-if="contenant.modif">Enregistrer</div>
                        </div>
                        <div class="L_left" style="margin-top: 0.4em">
                            <div class="clickable" style="height: 40px; width: 40px; margin-left: 1em;" ng-click="contenant.add_contenant=true;" ng-if="!contenant.add_contenant">
                                <img style="width :100%;" src="images/plus item.png">
                            </div>
                            <div class="item_formulaire" style="margin-left: 1em;" ng-if="contenant.add_contenant">Nom :</div>

                            <input type="text" ng-model="new_contenant.nom" ng-if="contenant.add_contenant">
                            <div class="item_formulaire" ng-if="contenant.add_contenant">Capacité :</div>

                            <input type="number" ng-model="new_contenant.capacite" ng-if="contenant.add_contenant">
                            <div class="item_formulaire" ng-if="contenant.add_contenant">Type :</div>

                            <select name="select_type"  ng-model="new_contenant.type" style="font-size: 1em; min-width: 15.3em; margin-left: 0.5em; margin-right: 0.5em;" ng-if="contenant.add_contenant">
                              <option value="fut">Fût</option>
                              <option value="bouteille_unique">Bouteille vendue entière</option>
                              <option value="bouteille_partage">Bouteille servie en eco cup</option>
                              <option value="cubi">Cubi</option>
                              <option value="verre">Eco Cup</option>
                            </select>
                            <div class="bouton" ng-click="new_contenant(new_contenant); contenant.add_contenant=false; message.statu='none';" ng-if="contenant.add_contenant">Créer</div>

                        </div>
                    </div>

                    <div class="accordion" ng-class="compte.class" ng-click="compte.activate(); start_compte();">
                        <div style="height: 20px; width: 20px;" ng-if="compte.screen">
                            <img style="width :100%;" src="images/accordion_activate.png">
                        </div>
                        <div style="height: 20px; width: 20px;" ng-if="!compte.screen">
                            <img style="width :100%;" src="images/accordion.png">
                        </div>
                        <div style="margin-left: 10px">Comptes</div>
                    </div>
                    <div ng-if="compte.screen" class="screen" style="padding-left: 0; padding-right: 0;">
                        <div class="L_center">
                            <div class="item_formulaire">Recherche :</div>
                            <input type="texte" ng-model="compte.search">
                        </div>
                        <div class="L_space_a">
                            <div class="info" style="margin-top: 10px;">Soldes positifs : {{prix(solde_positif)}}</div>
                            <div class="info" style="margin-top: 10px;">Soldes négatifs {{prix(solde_negatif)}}</div>
                            <div class="info" style="margin-top: 10px;">Total: {{prix(solde_positif+solde_negatif)}}</div>
                        </div>
                        <div class="C_centre" style="margin-top: 10px;">
                            <div class="L_tableau" >
                                <div class="head_tableau clickable" ng-click="compte.order('prenom')">Prénom</div>
                                <div class="head_tableau clickable" ng-click="compte.order('nom')">Nom</div>
                                <div class="head_tableau clickable" ng-click="compte.order('promo')" style="width: 50%;">Promo</div>
                                <div class="head_tableau clickable" ng-click="compte.order('droit')" style="width: 50%;">Droit</div>
                                <div class="head_tableau clickable" style="width: 50%;" ng-click="compte.order('solde')">Solde</div>
                                <div class="head_tableau" ></div>
                                <div class="head_tableau" ></div>
                            </div>
                            <div class="L_tableau" ng-class="color($index)" ng-repeat="user in users_list | filter : compte.search | orderBy : compte.ordervalue">

                                <div class="case_tableau">{{user.prenom}}</div>
                                <div class="case_tableau">{{user.nom}}</div>
                                <div class="case_tableau" style="width: 50%;">{{user.promo}}</div>
                                <div class="case_tableau clickable" style="width: 50%;" ng-if="!user.modif_droit" ng-click="user.modif_droit=true;">{{user.droit}}</div>
                                <div class="case_tableau" style="width: 50%;" ng-if="user.modif_droit">
                                    <select name="select_type"  ng-model="user.droit" style="width: 100%;">
                                        <option value="user" ng-click="user.modif_droit=false; maj_user_droit(user);">User</option>
                                        <option value="cercleux" ng-click="user.modif_droit=false; maj_user_droit(user);">Cercleux</option>
                                        <option value="cercle" ng-click="user.modif_droit=false; maj_user_droit(user);">Cercle</option>
                                    </select>
                                    <div class="bouton" style="padding: 2px; font-size: 0.9em;" ng-click="user.modif_droit=false; maj_user_droit(user);">Ok</div>
                                </div>
                                <div class="case_tableau" style="width: 50%;">{{prix(user.solde)}}</div>
                                <div class="case_tableau clickable">
                                    <a href="compte.php?id={{user.id}}" style="color: black"><div class="bouton">Historique</div></a>
                                </div>
                                <div class="case_tableau clickable">
                                    <a href="stats.php?id={{user.id}}" style="color: black"><div class="bouton">Stats</div></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion" ng-class="perm_list.class" ng-click="perm_list.activate(); start_perm();">
                        <div style="height: 20px; width: 20px;" ng-if="perm_list.screen">
                            <img style="width :100%;" src="images/accordion_activate.png">
                        </div>
                        <div style="height: 20px; width: 20px;" ng-if="!perm_list.screen">
                            <img style="width :100%;" src="images/accordion.png">
                        </div>
                        <div style="margin-left: 10px">Perm</div>
                    </div>
                    <div ng-if="perm_list.screen" class="screen" style="padding-left: 0; padding-right: 0;">
                        <div class="L_center">
                            <div class="item_formulaire">Recherche :</div>
                            <input type="texte" ng-model="perm_list.search">
                        </div>

                        <div class="C_centre" style="margin-top: 10px;">
                            <div class="L_tableau" >
                                <div class="head_tableau clickable" ng-click="perm_list.order('nom')">Nom</div>
                                <div class="head_tableau" ></div>
                                <div class="head_tableau" ></div>
                                <div class="head_tableau" ></div>

                            </div>
                            <div class="L_tableau" ng-class="color($index)" ng-repeat="perm in nom_perm_list | filter : perm_list.search | orderBy : perm_list.ordervalue">

                                <div class="case_tableau" ng-if="!perm.new_nom">{{perm.nom}}</div>
                                <div class="case_tableau" ng-if="perm.new_nom">
                                    <div><input type="text" ng-model="perm.nom"></div>
                                </div>
                                <div class="case_tableau" ng-if="!perm.new_nom"><div><div class="bouton"  ng-click="perm.new_nom=true;">Changer le nom</div></div></div>
                                <div class="case_tableau"><div><div class="bouton"  ng-click="desactiv_perm(perm);">Désactiver la perm</div></div></div>
                                <div class="case_tableau" ng-if="perm.new_nom"><div><div class="bouton"  ng-click="maj_perm_nom(perm); perm.new_nom=false;">Valider</div></div></div>
                                <div class="case_tableau" ng-if="!perm.view"><div class="bouton"  ng-click="reset_perm_list(); perm.view=true;">Voir les membres</div></div>
                                <div class="case_tableau" ng-if="perm.view">
                                    <div class="C_tableau">
                                        <div class="" ng-repeat="membre in perm.membres" >
                                            <div style="font-size: 1em; padding: 11px; margin: 5px;">{{membre.prenom}} {{membre.nom}}</div>
                                        </div>
                                        <div class="clickable" style="height: 30px; width: 30px; margin-top: 5px;" ng-click="perm.new_membre=true;" ng-if="!perm.new_membre">
                                            <img style="width :100%;" src="images/plus item.png">
                                        </div>
                                        <div class="L_left" style="margin-top: 0.8em" ng-if="perm.new_membre">
                                            <div>
                                                <input type="text" placeholder="Taper le nom du nouveau membre" ng-model="perm.new_user.search" style="font-size: 1em; width: 15em; margin-left: 4em;" autocomplete="off" ng-click="perm.new_user.auto_c=true;"/>

                                                <div class="auto_c" ng-if="perm.new_user.auto_c && perm.new_user.search!=null" style="margin-left: 4em;">
                                                    <div class="auto_c_value" ng-repeat="item in users_list | filter : perm.new_user.search" ng-click="maj_membre_perm(perm,item.id);  perm.new_user.auto_c=false; perm.new_user.search=null; perm.new_membre=false;">{{item.prenom}} {{item.nom}}</div>
                                                </div>
                                            </div>
                                            <span ng-if="perm.new_user.search!=null" style="width: 30px; margin: 0.5em" >
                                                <img style="width: 100%;" src="images/false.png">
                                            </span>
                                            <span ng-if="perm.new_user.search==null" style="width: 30px; margin: 0.5em" >

                                            </span>

                                        </div>
                                    </div>
                                </div>


                                <div class="case_tableau" ng-if="!perm.view"></div>


                                <div class="case_tableau" ng-if="perm.view">
                                    <div class="C_tableau">
                                        <div class="" ng-repeat="membre in perm.membres">
                                            <div class="bouton"  ng-click="maj_membre_perm(perm,-membre.id)" style="font-size: 1em; margin: 5px;">Supprimer</div>
                                        </div>
                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>

                    <div class="accordion" ng-class="constante.class" ng-click="constante.activate(); start_constante();">
                        <div style="height: 20px; width: 20px;" ng-if="constante.screen">
                            <img style="width :100%;" src="images/accordion_activate.png">
                        </div>
                        <div style="height: 20px; width: 20px;" ng-if="!constante.screen">
                            <img style="width :100%;" src="images/accordion.png">
                        </div>
                        <div style="margin-left: 10px">Constantes</div>
                    </div>
                    <div ng-if="constante.screen" class="screen">
                        <div class="L_left" ng-repeat="constante in constantes_list" style="margin-top: 0.8em;">
                            <div class="item_formulaire" style="margin-left: 1em;">{{constante.nom}} :</div>
                            <div class="item_formulaire" ng-if="!constante.modif && ($index==0 || $index==1 || $index==5 || $index==6 || $index==7)">{{prix(constante.valeur)}}</div>
                            <div class="item_formulaire" ng-if="!constante.modif && ($index==2 || $index==4 || $index==8 )">{{constante.valeur}}</div>
                            <div class="item_formulaire" ng-if="($index==3 || $index==9)  && constante.valeur==1">Activé</div>
                            <div class="item_formulaire" ng-if="($index==3 || $index==9) && constante.valeur==0">Désactivé</div>
                            <input type="number" ng-model="constante.valeur" ng-if="constante.modif ">

                            <div class="bouton" ng-click="constante.modif=true; message.statu='none';" ng-if="!constante.modif && $index!=3 && $index!=9">Modifier</div>
                            <div class="bouton" ng-click="message.statu='none'; constante.valeur=(constante.valeur+1)%2; valid_constante(constante);" ng-if="$index==3 || $index==9">Modifier</div>
                            <div class="bouton" ng-click="valid_constante(constante); constante.modif=false; " ng-if="constante.modif">Enregistrer</div>
                        </div>
                    </div>

                    <div class="accordion" ng-class="perm.class" ng-click="perm.activate(); start_perm();">
                        <div style="height: 20px; width: 20px;" ng-if="perm.screen">
                            <img style="width :100%;" src="images/accordion_activate.png">
                        </div>
                        <div style="height: 20px; width: 20px;" ng-if="!perm.screen">
                            <img style="width :100%;" src="images/accordion.png">
                        </div>
                        <div style="margin-left: 10px">Nouvelle perm</div>
                    </div>
                    <div ng-if="perm.screen" class="screen">
                        <div class="L_left">
                            <div class="item_formulaire" style="margin-left: 1em; ">Nom de la nouvelle perm :</div>
                            <input type="text" placeholder="Taper le nom de la perm" ng-model="perm.name" style="font-size: 1em; min-width: 15em;" ng-click="message.statu='none';"/>
                            <span ng-if="control_name_perm(perm.name)=='wrong_input' && perm.name!=null" style="width: 30px; margin-left: 0.5em; margin-right: 0.5em" >
                                <img style="width: 100%;" src="images/false.png">
                            </span>
                            <span class="info" ng-if="control_name_perm(perm.name)=='wrong_input' && perm.name!=null" style="font-size: 0.8em; color: red margin: auto;">
                                Ce nom de perm existe déjà
                            </span>
                            <span ng-if="(!(control_name_perm(perm.name)=='wrong_input') && !(control_name_perm(perm.name)=='correct_input')) && perm.name!=null" style="width: 30px; margin-left: 0.5em; margin-right: 0.5em" >
                                <img style="width: 100%;" src="images/weird.png">
                            </span>
                            <span class="info" ng-if="(!(control_name_perm(perm.name)=='wrong_input') && !(control_name_perm(perm.name)=='correct_input')) && perm.name!=null" style="font-size: 0.8em; color: orange; ">
                                Un nom de perm ressemble : {{control_name_perm(perm.name)}}
                            </span>
                            <span ng-if="control_name_perm(perm.name)=='correct_input' && perm.name!=null" style="width: 30px; margin-left: 0.5em"><img style="width: 100%;" src="images/correct.png"></span>
                            <span ng-if="perm.name==null" style="width: 30px; margin-left: 0.5em"><div style="height: 34px;"></div></span>
                        </div>
                        <div class="L_left">
                            <div class="item_formulaire" style="margin-left: 1em; margin-top: 0.8em;">Membre de la perm :</div>
                        </div>
                        <div class="L_left" ng-repeat="user in perm.user_array">
                            <div class="item_formulaire" style="margin-left: 2em; ">{{user.prenom}} {{user.nom}}</div>
                        </div>
                        <div class="L_left" style="margin-top: 0.8em">
                            <div>
                                <input type="text" placeholder="Taper le nom d'un membre" ng-model="perm.new_user.search" style="font-size: 1em; width: 15em; margin-left: 4em;" autocomplete="off" ng-click="perm.new_user.auto_c=true;"/>

                                <div class="auto_c" ng-if="perm.new_user.auto_c && perm.new_user.search!=null" style="margin-left: 4em;">
                                    <div class="auto_c_value" ng-repeat="item in users_list | filter : perm.new_user.search" ng-click="perm.user_array.push(item); perm.new_user.auto_c=false; perm.new_user.search=null">{{item.prenom}} {{item.nom}}</div>
                                </div>
                            </div>
                            <span ng-if="perm.new_user.search!=null" style="width: 30px; margin: 0.5em" >
                                <img style="width: 100%;" src="images/false.png">
                            </span>
                            <span class="info" ng-if="perm.new_user.search!=null" style="font-size: 0.8em; color: red margin: auto;">
                                Sélectionner un nom de cercleux
                            </span>
                        </div>
                        <div class="L_center"  ng-if="perm.user_array.length>0 && control_name_perm(perm.name)!='wrong_input' && perm.name!=null" style="margin-top: 0.8em">
                            <div class="bouton" ng-click="valid_new_perm()">Créer la perm</div>
                        </div>
                    </div>

                    <div class="accordion" ng-class="new_user.class" ng-click="new_user.activate(); start_constante();">
                        <div style="height: 20px; width: 20px;" ng-if="new_user.screen">
                            <img style="width :100%;" src="images/accordion_activate.png">
                        </div>
                        <div style="height: 20px; width: 20px;" ng-if="!new_user.screen">
                            <img style="width :100%;" src="images/accordion.png">
                        </div>
                        <div style="margin-left: 10px">Nouveau compte</div>
                    </div>
                    <div ng-if="new_user.screen" class="screen" >
                        <div class="L_left">
                            <div class="item_formulaire" style="margin-left: 1em; ">Adresse mail EMSE :</div>
                            <input type="text" placeholder="prenom.nom@etu.emse.fr" ng-model="new_user.mail" style="font-size: 1em; min-width: 15em;" ng-click="message.statu='none';"/>
                            <span ng-if="!control_mail(new_user.mail) && new_user.mail!=null" style="width: 30px; margin-left: 0.5em; margin-right: 0.5em" >
                                <img style="width: 100%;" src="images/false.png">
                            </span>
                            <span class="info" ng-if="!control_mail(new_user.mail) && new_user.mail!=null" style="font-size: 0.8em; color: red margin: auto;">
                                Entrez une adresse mail EMSE valide
                            </span>
                            <span ng-if="control_mail(new_user.mail)" style="width: 30px; margin-left: 0.5em"><img style="width: 100%;" src="images/correct.png"></span>
                            <span ng-if="new_user.mail==null" style="width: 30px; margin-left: 0.5em"><div style="height: 34px;"></div></span>
                        </div>
                        <div class="L_left" style="margin-top: 0.8em">
                            <div class="item_formulaire" style="margin-left: 1em; ">Type :</div>
                            <select name="select_type"  ng-model="new_user.type" style="font-size: 1em; min-width: 15.3em; margin-left: 9.25em;">
                              <option value="ICM">ICM</option>
                              <option value="ISTP">ISTP</option>
                              <option value="autre">Autre</option>
                            </select>
                        </div>
                        <div class="L_left" style="margin-top: 0.8em">
                            <div class="item_formulaire" style="margin-left: 1em; ">Promo :</div>
                            <input type="number" ng-model="new_user.promo" style="font-size: 1em; min-width: 15em; margin-left: 8.1em;"/>
                        </div>
                        <div class="L_left" style="margin-top: 0.8em">
                            <div class="item_formulaire" style="margin-left: 1em; ">Premier versement :</div>
                            <!-- zone de saisie déclenchant l'autocomplétion -->

                            <input type="number" ng-model="new_user.montant" style="font-size: 1em; min-width: 15em; margin-left: 0.6em;"/>
                        </div>
                        <div class="L_left" style="margin-top: 0.8em">
                            <div class="item_formulaire" style="margin-left: 1em; ">Cotisation : {{prix(constantes_list[1].valeur)}}</div>
                        </div>
                        <div class="L_left" style="margin-top: 0.8em">
                            <div class="item_formulaire" style="margin-left: 1em; ">Solde : {{prix(new_user.montant-constantes_list[1].valeur)}}</div>
                        </div>
                        <div class="L_center" style="margin-top: 0.8em">
                            <div class="bouton" ng-if="control_mail(new_user.mail)" ng-click="valid_new_user()">Créer</div>
                            <div style="height: 3em;" ng-if="!control_mail(new_user.mail)" ></div>
                        </div>
                    </div>
                </div>
            </div>



    	</div>
        <script src="js/angularjs.js" type="text/javascript"></script>

        <?php
        echo "<script src=\"js/gestion_app.js?".time()."\" type=\"text/javascript\"></script>";
        ?>


    </body>
