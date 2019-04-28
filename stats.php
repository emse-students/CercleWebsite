<?php
session_start();
include ("php/connexion.php");

if (!isset($_GET["id"])) {
    $_GET["id"]=$_SESSION["id_cercle"];
    $perso=false;
}else{
    $perso=true;
}
?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8" />
        <?php
        echo "<link rel='stylesheet' href='css/style.css?".time()."'/>";
        ?>
        <link rel='stylesheet' href='css/nv.d3.css'>
        <link rel="icon" type="image/png" href="images/touchicon.png" />
        <link rel="apple-touch-icon" href="images/appleicon.png" />
        <title>Cercle EMSE-Stats</title>
    </head>

    <body ng-app="stats_app"  ng-controller="mainController">
    	<?php
        $page=2;

    	include("php/header.php");
    	?>
    	<div class="page">

            <h1>Stats</h1>

            <div class="formulaire" style="min-height: 25em; padding: 0;">
                <div class="accordion" ng-class="stats_globales.class" ng-click="stats_globales.activate(); start_stats_globales()">
                    <div style="height: 20px; width: 20px;" ng-if="stats_globales.screen">
                        <img style="width :100%;" src="images/accordion_activate.png">
                    </div>
                    <div style="height: 20px; width: 20px;" ng-if="!stats_globales.screen">
                        <img style="width :100%;" src="images/accordion.png">
                    </div>
                    <div style="margin-left: 10px">Stats globales</div>
                </div>
                <div ng-if="stats_globales.screen && loading" class="inventaire" >
                    <div class="classement">
                        <h1>Chargement en cours ...</h1>
                        <h2>Calcul des classements</h2>
                        <h2>C'est un peu long c'est normal</h2>

                    </div>
                </div>
                <div ng-if="stats_globales.screen && !loading" class="inventaire" >
                    <div class="classement">
                        <div class="selector">
                            <h1>Classement général</h1>
                            <div class="L_center">
                                <div class="item_formulaire">Recherche :</div>
                                <input type="texte" ng-model="stats_globales.globale.search">
                            </div>
                            <div class="L_center">
                                <div style="margin-right: 5px">Classé par :</div>
                                <input type="radio" name="globale_classby" value="-depense" ng-model="stats_globales.globale.classby" style="margin-bottom: auto"><div style="margin-right: 5px">Dépenses</div>
                                <input type="radio" name="globale_classby" value="-volume" ng-model="stats_globales.globale.classby" style="margin-bottom: auto"><div style="margin-right: 5px">Volume</div>
                                <input type="radio" name="globale_classby" value="-alcool" ng-model="stats_globales.globale.classby" style="margin-bottom: auto"><div style="margin-right: 5px">Volume d'alcool</div>
                                <input type="radio" name="globale_classby" value="-perm" ng-model="stats_globales.globale.classby" style="margin-bottom: auto"><div>Nb de perm</div>
                            </div>
                        </div>
                        <div class="centreur">
                            <div class="C_centre">
                                <div class="L_tableau">
                                    <div class="head_tableau_stats" style="width: 50%;"></div>
                                    <div class="head_tableau_stats" style="width: 120%;">Prénom</div>
                                    <div class="head_tableau_stats" style="width: 120%;">Nom</div>
                                    <div class="head_tableau_stats">Promo</div>
                                    <div class="head_tableau_stats">Dépenses</div>
                                    <div class="head_tableau_stats">Volume</div>
                                    <div class="head_tableau_stats">Volume d'alcool</div>
                                    <div class="head_tableau_stats">Nb de Perm</div>
                                </div>
                                <div class="L_tableau" ng-class="color($index)" ng-if="stats_globales.globale.data" ng-repeat="user in stats_globales.globale.data | orderBy : stats_globales.globale.classby | filter : stats_globales.globale.search |  limitTo : stats_globales.globale.limit">
                                    <div class="case_tableau" style="width: 50%;">{{classement(user.rank, stats_globales.globale.classby)}}</div>
                                    <div class="case_tableau" style="width: 120%;">{{user.prenom}}</div>
                                    <div class="case_tableau" style="width: 120%;">{{user.nom}}</div>
                                    <div class="case_tableau">{{user.promo}}</div>
                                    <div class="case_tableau">{{prix(user.depense)}}</div>
                                    <div class="case_tableau">{{volume(user.volume)}}</div>
                                    <div class="case_tableau">{{volume(user.alcool)}}</div>
                                    <div class="case_tableau">{{user.perm}}</div>
                                </div>
                                <div class="bouton" ng-if="stats_globales.globale.limit==10" ng-click="stats_globales.globale.limit=50" style="margin-top: 10px;">Top 50</div>
                                <div class="bouton" ng-if="stats_globales.globale.limit==50" ng-click="stats_globales.globale.limit=10" style="margin-top: 10px;">Top 10</div>
                                <h2 style="margin-top: 10px;" ng-if="stats_globales.globale.data_diagramme_biere">Répartition du nombre de boissons totales par type de boisson :</h2>
                                <nvd3 options="options_diagramme_biere" data="stats_globales.globale.data_diagramme_biere" ng-if="stats_globales.globale.data_diagramme_biere"></nvd3>
                            </div>
                        </div>
                    </div>
                    <div style="width: 100%; text-align: center; margin: 20px;">
                        Remarques: <br>
                        - Seuls les achats de boissons sont pris en compte (pas les planchettes ect.)<br>
                        - Le volume d'alcool pur est obtenu par la somme des (volume de la boisson) X (% d'alcool)
                    </div>
                    <div class="classement">
                        <div class="selector">
                            <h1>Par année</h1>
                            <div class="L_center">
                                <div class="item_formulaire">Recherche :</div>
                                <input type="texte" ng-model="stats_globales.annee.search">
                            </div>
                            <div class="L_center">
                                <div style="margin-right: 5px">Année :
                                    <select ng-model="stats_globales.annee.annee" ng-options="annee.id*1 as annee.name for annee in stats_globales.annee.list"></select>
                                </div>
                                <div style="margin-right: 5px">Classé par :</div>
                                <input type="radio" name="annee_classby" value="-depense" ng-model="stats_globales.C.classby" style="margin-bottom: auto"><div style="margin-right: 5px">Dépenses</div>
                                <input type="radio" name="annee_classby" value="-volume" ng-model="stats_globales.annee.classby" style="margin-bottom: auto"><div style="margin-right: 5px">Volume</div>
                                <input type="radio" name="annee_classby" value="-alcool" ng-model="stats_globales.annee.classby" style="margin-bottom: auto"><div style="margin-right: 5px">Volume d'alcool</div>
                                <input type="radio" name="annee_classby" value="-perm" ng-model="stats_globales.annee.classby" style="margin-bottom: auto"><div>Nb de perm</div>
                            </div>
                        </div>
                        <div class="centreur">
                            <div class="C_centre">
                                <div class="L_tableau">
                                    <div class="head_tableau_stats" style="width: 50%;"></div>
                                    <div class="head_tableau_stats" style="width: 120%;">Prénom</div>
                                    <div class="head_tableau_stats" style="width: 120%;">Nom</div>
                                    <div class="head_tableau_stats">Promo</div>
                                    <div class="head_tableau_stats">Dépenses</div>
                                    <div class="head_tableau_stats">Volume</div>
                                    <div class="head_tableau_stats">Volume d'alcool</div>
                                    <div class="head_tableau_stats">Nb de Perm</div>
                                </div>
                                <div class="L_tableau" ng-class="color($index)" ng-if="stats_globales.globale.data" ng-repeat="user in stats_globales.annee.data[stats_globales.annee.annee] | orderBy : stats_globales.annee.classby | filter : stats_globales.annee.search |  limitTo : stats_globales.annee.limit">
                                    <div class="case_tableau" style="width: 50%;">{{classement(user.rank, stats_globales.annee.classby)}}</div>
                                    <div class="case_tableau" style="width: 120%;">{{user.prenom}}</div>
                                    <div class="case_tableau" style="width: 120%;">{{user.nom}}</div>
                                    <div class="case_tableau">{{user.promo}}</div>
                                    <div class="case_tableau">{{prix(user.depense)}}</div>
                                    <div class="case_tableau">{{volume(user.volume)}}</div>
                                    <div class="case_tableau">{{volume(user.alcool)}}</div>
                                    <div class="case_tableau">{{user.perm}}</div>
                                </div>
                                <div class="bouton" ng-if="stats_globales.annee.limit==10" ng-click="stats_globales.annee.limit=50" style="margin-top: 10px;">Top 50</div>
                                <div class="bouton" ng-if="stats_globales.annee.limit==50" ng-click="stats_globales.annee.limit=10" style="margin-top: 10px;">Top 10</div>
                                <h2 style="margin-top: 10px;" ng-if="stats_globales.annee.data_diagramme_biere">Répartition du nombre de boissons sur l'année {{stats_globales.annee.annee}}/{{stats_globales.annee.annee+1}} par type de boisson :</h2>
                                <nvd3 options="options_diagramme_biere" data="stats_globales.annee.data_diagramme_biere[stats_globales.annee.annee]"></nvd3>
                            </div>
                        </div>
                    </div>
                    <div class="classement">
                        <div class="selector">
                            <h1>Par promo</h1>
                            <div class="L_center">
                                <div class="item_formulaire">Recherche :</div>
                                <input type="texte" ng-model="stats_globales.promo.search">
                            </div>
                            <div class="L_center">
                                <div style="margin-right: 5px">Promo :
                                    <select ng-model="stats_globales.promo.promo" ng-options="promo*1 for promo in stats_globales.promo.list"></select>
                                </div>
                                <div style="margin-right: 5px">Classé par :</div>
                                <input type="radio" name="promo_classby" value="-depense" ng-model="stats_globales.promo.classby" style="margin-bottom: auto"><div style="margin-right: 5px">Dépenses</div>
                                <input type="radio" name="promo_classby" value="-volume" ng-model="stats_globales.promo.classby" style="margin-bottom: auto"><div style="margin-right: 5px">Volume</div>
                                <input type="radio" name="promo_classby" value="-alcool" ng-model="stats_globales.promo.classby" style="margin-bottom: auto"><div style="margin-right: 5px">Volume d'alcool</div>
                                <input type="radio" name="promo_classby" value="-perm" ng-model="stats_globales.promo.classby" style="margin-bottom: auto"><div>Nb de perm</div>
                            </div>
                        </div>
                        <div class="centreur">
                            <div class="C_centre">
                                <div class="L_tableau">
                                    <div class="head_tableau_stats" style="width: 50%;"></div>
                                    <div class="head_tableau_stats" style="width: 120%;">Prénom</div>
                                    <div class="head_tableau_stats" style="width: 120%;">Nom</div>
                                    <div class="head_tableau_stats">Promo</div>
                                    <div class="head_tableau_stats">Dépenses</div>
                                    <div class="head_tableau_stats">Volume</div>
                                    <div class="head_tableau_stats">Volume d'alcool</div>
                                    <div class="head_tableau_stats">Nb de Perm</div>
                                </div>
                                <div class="L_tableau" ng-class="color($index)" ng-if="stats_globales.globale.data" ng-repeat="user in stats_globales.promo.data[stats_globales.promo.promo] | orderBy : stats_globales.promo.classby | filter : stats_globales.promo.search |  limitTo : stats_globales.promo.limit">
                                    <div class="case_tableau" style="width: 50%;">{{classement(user.rank, stats_globales.promo.classby)}}</div>
                                    <div class="case_tableau" style="width: 120%;">{{user.prenom}}</div>
                                    <div class="case_tableau" style="width: 120%;">{{user.nom}}</div>
                                    <div class="case_tableau">{{user.promo}}</div>
                                    <div class="case_tableau">{{prix(user.depense)}}</div>
                                    <div class="case_tableau">{{volume(user.volume)}}</div>
                                    <div class="case_tableau">{{volume(user.alcool)}}</div>
                                    <div class="case_tableau">{{user.perm}}</div>
                                </div>
                                <div class="bouton" ng-if="stats_globales.promo.limit==10" ng-click="stats_globales.promo.limit=50" style="margin-top: 10px;">Top 50</div>
                                <div class="bouton" ng-if="stats_globales.promo.limit==50" ng-click="stats_globales.promo.limit=10" style="margin-top: 10px;">Top 10</div>
                                <h2 style="margin-top: 10px;" ng-if="stats_globales.promo.data_diagramme_biere">Répartition du nombre de boissons pour la promo {{stats_globales.promo.promo}} par type de boisson :</h2>
                                <nvd3 options="options_diagramme_biere" data="stats_globales.promo.data_diagramme_biere[stats_globales.promo.promo]"></nvd3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion" ng-class="stats_perso.class" ng-click="stats_perso.activate(); start_stats();">
                    <div style="height: 20px; width: 20px;" ng-if="stats_perso.screen">
                        <img style="width :100%;" src="images/accordion_activate.png">
                    </div>
                    <div style="height: 20px; width: 20px;" ng-if="!stats_perso.screen">
                        <img style="width :100%;" src="images/accordion.png">
                    </div>
                    <div style="margin-left: 10px">Stats perso</div>
                </div>
                <div ng-if="stats_perso.screen && loading" class="inventaire" >
                    <div class="classement">
                        <h1>Chargement en cours ...</h1>
                        <h2>Calcul des classements</h2>
                        <h2>C'est un peu long c'est normal</h2>

                    </div>
                </div>
                <div ng-if="stats_perso.screen && !loading" class="inventaire">
                    <div class="classement">
                        <div class="selector">
                            <h1>Consomation totale de</h1>
                            <h1>{{stats_perso.globale.data.prenom}} {{stats_perso.globale.data.nom}}</h1>
                        </div>
                        <div class="centreur">
                            <div class="C_centre">
                                <div class="L_tableau">
                                    <div class="head_tableau_stats" style="width: 200%"></div>
                                    <div class="head_tableau_stats">Dépenses</div>
                                    <div class="head_tableau_stats">Volume</div>
                                    <div class="head_tableau_stats">Volume d'alcool</div>
                                    <div class="head_tableau_stats">Nb de Perm</div>
                                </div>
                                <div class="L_tableau" ng-class="color(1)">
                                    <div class="case_tableau" style="width: 200%">Consommation</div>
                                    <div class="case_tableau">{{prix(stats_perso.globale.data.depense)}}</div>
                                    <div class="case_tableau">{{volume(stats_perso.globale.data.volume)}}</div>
                                    <div class="case_tableau">{{volume(stats_perso.globale.data.alcool)}}</div>
                                    <div class="case_tableau">{{stats_perso.globale.data.perm}}</div>
                                </div>
                                <div class="L_tableau" ng-class="color(2)">
                                    <div class="case_tableau" style="width: 200%">Classements généraux</div>
                                    <div class="case_tableau">{{classement(stats_perso.globale.rank.depense)}}</div>
                                    <div class="case_tableau">{{classement(stats_perso.globale.rank.volume)}}</div>
                                    <div class="case_tableau">{{classement(stats_perso.globale.rank.alcool)}}</div>
                                    <div class="case_tableau">{{classement(stats_perso.globale.rank.perm)}}</div>
                                </div>
                                <div class="L_tableau" ng-class="color(1)">
                                    <div class="case_tableau" style="width: 200%">Classements par rapport à la promo {{stats_perso.promo.promo}}</div>
                                    <div class="case_tableau">{{classement(stats_perso.promo.rank.depense)}}</div>
                                    <div class="case_tableau">{{classement(stats_perso.promo.rank.volume)}}</div>
                                    <div class="case_tableau">{{classement(stats_perso.promo.rank.alcool)}}</div>
                                    <div class="case_tableau">{{classement(stats_perso.promo.rank.perm)}}</div>
                                </div>
                                <h2 style="margin-top: 10px;" ng-if="stats_perso.globale_biere">Nombre total de boissons consomées par {{stats_perso.globale.data.prenom}} {{stats_perso.globale.data.nom}} : {{total_boisson(stats_perso.globale_biere)}}</h2>
                                <h2 style="margin-top: 10px;" ng-if="stats_perso.globale_biere">réparties par types :</h2>
                                <nvd3 options="options_diagramme_biere" data="stats_perso.globale_biere" ng-if="stats_perso.globale_biere"></nvd3>
                            </div>
                        </div>
                    </div>
                    <div class="classement">
                        <div class="selector">
                            <h1>Consomation sur l'année {{stats_perso.annee.annee}}/{{stats_perso.annee.annee+1}}</h1>
                            <div class="L_center">
                                <div style="margin-right: 5px">Année :
                                    <select ng-model="stats_perso.annee.annee" ng-options="annee.id*1 as annee.name for annee in stats_perso.annee.list"></select>
                                </div>
                            </div>
                        </div>
                        <div class="centreur">
                            <div class="C_centre">
                                <div class="L_tableau">
                                    <div class="head_tableau_stats" style="width: 150%"></div>
                                    <div class="head_tableau_stats">Dépenses</div>
                                    <div class="head_tableau_stats">Volume</div>
                                    <div class="head_tableau_stats">Volume d'alcool</div>
                                    <div class="head_tableau_stats">Nb de Perm</div>
                                </div>
                                <div class="L_tableau" ng-class="color(1)">
                                    <div class="case_tableau" style="width: 150%">Consommation</div>
                                    <div class="case_tableau">{{prix(stats_perso.annee.data[stats_perso.annee.annee].depense)}}</div>
                                    <div class="case_tableau">{{volume(stats_perso.annee.data[stats_perso.annee.annee].volume)}}</div>
                                    <div class="case_tableau">{{volume(stats_perso.annee.data[stats_perso.annee.annee].alcool)}}</div>
                                    <div class="case_tableau">{{stats_perso.annee.data[stats_perso.annee.annee].perm}}</div>
                                </div>
                                <div class="L_tableau" ng-class="color(2)">
                                    <div class="case_tableau" style="width: 150%">Classements</div>
                                    <div class="case_tableau">{{classement(stats_perso.annee.rank[stats_perso.annee.annee].depense)}}</div>
                                    <div class="case_tableau">{{classement(stats_perso.annee.rank[stats_perso.annee.annee].volume)}}</div>
                                    <div class="case_tableau">{{classement(stats_perso.annee.rank[stats_perso.annee.annee].alcool)}}</div>
                                    <div class="case_tableau">{{classement(stats_perso.annee.rank[stats_perso.annee.annee].perm)}}</div>
                                </div>
                                <h2 style="margin-top: 10px;" ng-if="stats_perso.annee.biere && stats_perso.annee.biere[stats_perso.annee.annee]">Nombre de boissons consommées par {{stats_perso.globale.data.prenom}} {{stats_perso.globale.data.nom}} sur l'année {{stats_perso.annee.annee}}/{{stats_perso.annee.annee+1}} : {{total_boisson(stats_perso.annee.biere[stats_perso.annee.annee])}}</h2>
                                <h2 style="margin-top: 10px;" ng-if="stats_perso.annee.biere && stats_perso.annee.biere[stats_perso.annee.annee]">réparties par types :</h2>
                                <nvd3 options="options_diagramme_biere" data="stats_perso.annee.biere[stats_perso.annee.annee]" ng-if="stats_perso.annee.biere && stats_perso.annee.biere[stats_perso.annee.annee]"></nvd3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion" ng-class="forum.class" ng-click="forum.activate(); start_forum()">
                    <div style="height: 20px; width: 20px;" ng-if="forum.screen">
                        <img style="width :100%;" src="images/accordion_activate.png">
                    </div>
                    <div style="height: 20px; width: 20px;" ng-if="!forum.screen">
                        <img style="width :100%;" src="images/accordion.png">
                    </div>
                    <div style="margin-left: 10px">Cours de la bière de la dernière perm Forum</div>
                </div>
                <div ng-if="forum.screen" class="screen">
                    <div class="inventaire">
                        <div class="bouton" style="margin-left:10px;" ng-repeat="boisson in boissons">
                            <div>
                                {{boisson.nom}} : {{boisson.prix}}€
                            </div>
                        </div>
                    </div>
                    <nvd3 options="options" data="data"></nvd3>
                </div>
            </div>
    	</div>
        <script src="js/angularjs.js" type="text/javascript"></script>
        <script src="js/d3.js" type="text/javascript"></script>
        <script src="js/nv.d3.js" type="text/javascript"></script>
        <script src="js/angular-nvd3.js" type="text/javascript"></script>
        <?php
        if ($perso) {
            echo "<script type=\"text/javascript\">var perso=true;</script>";
        }else{
            echo "<script type=\"text/javascript\">var perso=false;</script>";
        }
        echo"<script type=\"text/javascript\">var id_search=".$_GET["id"]."</script>";
        echo "<script src=\"js/stats_app.js?".time()."\" type=\"text/javascript\"></script>";
        ?>


    </body>
