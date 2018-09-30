var app = angular.module('stats_app', ['nvd3']);
app.controller('mainController', function($scope) {

    var stats_item = function (bool = false) {
        this.screen = bool;
        if (bool) {
            this.class = "selected";
        }else{
            this.class = "";
        }

        this.started = false;

        this.activate = function () {
            var activate;
            if (this.screen) {
                activate = true;
            }
            else {
                activate = false;
            }
            $scope.forum.screen = false;
            $scope.forum.class = "";

            $scope.stats_globales.screen = false;
            $scope.stats_globales.class = "";

            $scope.stats_perso.screen = false;
            $scope.stats_perso.class = "";

            if (!activate) {
                this.screen = true;
                this.class = "selected";
            }
        };

        this.order = function (str) {
            if (this.ordervalue === str) {
                this.ordervalue = "-" + str;
            } else {
                this.ordervalue = str;
            }
        }
    };

    $scope.forum= new stats_item();



    $scope.start_forum=function () {
        if (!$scope.forum.started){
            $scope.options = {
                chart: {
                    type: 'lineChart',
                    height: 450,

                    margin : {
                        top: 20,
                        right: 20,
                        bottom: 40,
                        left: 55
                    },
                    x: function(d){ return d.x; },
                    y: function(d){ return d.y; },
                    useInteractiveGuideline: true,
                    dispatch: {
                        stateChange: function(e){ console.log("stateChange"); },
                        changeState: function(e){ console.log("changeState"); },
                        tooltipShow: function(e){ console.log("tooltipShow"); },
                        tooltipHide: function(e){ console.log("tooltipHide"); }
                    },
                    xAxis: {
                        axisLabel: 'Heure',
                        tickFormat: function(d){
                            return d3.time.format('%H:%M')(new Date(d));
                        }
                    },
                    yAxis: {
                        axisLabel: 'Prix (€)',
                        tickFormat: function(d){
                            return d3.format('.02f')(d);
                        },
                        axisLabelDistance: -10
                    },
                    callback: function(chart){
                        console.log("!!! lineChart callback !!!");
                    }

                }
            };

            var last_x = 0;

            $scope.data = [];

            setInterval(function(){
                var answer;
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState === 4 && this.status === 200) {
                        answer = angular.fromJson(this.responseText);
                        last_x=answer.last_x;
                        $scope.boissons=answer.boissons;
                        for (var biere in answer.data) {
                            if (answer.data.hasOwnProperty(biere)) {
                                var not_finded = true;
                                for (var i = 0; i < $scope.data.length; i++) {
                                    if($scope.data[i].key===biere)
                                    {
                                        for (var j= 0; j < answer.data[biere].length; j++)
                                        {
                                            $scope.data[i].values.push( {x : answer.data[biere][j].x_time, y : answer.data[biere][j].y_prix});
                                        }
                                        not_finded=false;
                                    }
                                }
                                if (not_finded) {
                                    $scope.data.push({ values: [], key: biere });
                                    for (var j= 0; j < answer.data[biere].length; j++) {
                                        $scope.data[$scope.data.length-1].values.push({ x : answer.data[biere][j].x_time, y : answer.data[biere][j].y_prix});
                                    }
                                }
                            }
                        }
                        $scope.$apply();
                    }
                };
                xhttp.open("GET", "php/get_forum_stats.php?time="+last_x, true);
                xhttp.send();
            }, 2000);
            $scope.forum.started=true;
        }
    };

    $scope.start_stats_globales=function (){
        if (!$scope.stats_globales.started) {
            $scope.stats_globales.globale = {};
            $scope.stats_globales.annee = {};
            $scope.stats_globales.promo = {};
            $scope.stats_globales.globale.limit = 5;
            $scope.stats_globales.globale.classby = "depense";
            $scope.stats_globales.annee.limit = 5;
            $scope.stats_globales.annee.classby = "depense";
            $scope.stats_globales.promo.limit = 5;
            $scope.stats_globales.promo.classby = "depense";

            var answer;
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState === 4 && this.status === 200) {
                    answer = angular.fromJson(this.responseText);
                    $scope.stats_globales.globale.data = answer.globale.data;
                    $scope.stats_globales.globale.data_diagramme_biere = answer.globale.diagramme_biere;
                    $scope.stats_globales.annee.data = answer.annee.data;
                    $scope.stats_globales.annee.list = answer.annee.list;
                    $scope.stats_globales.annee.annee = answer.annee.list[answer.annee.list.length-1].id;
                    $scope.stats_globales.promo.data = answer.promo.data;
                    $scope.stats_globales.promo.list = answer.promo.list;
                    $scope.stats_globales.promo.promo = answer.promo.list[answer.promo.list.length-1];
                    $scope.stats_globales.started=true;
                    $scope.$apply();
                }
            };
            xhttp.open("GET", "php/get_stats_globales.php");
            xhttp.send();

        }
    };



    $scope.start_stats_perso=function (){
        if (!$scope.stats_perso.started) {
            $scope.stats_perso.globale = {};
            $scope.stats_perso.annee = {};
            var answer;
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState === 4 && this.status === 200) {
                    answer = angular.fromJson(this.responseText);
                    $scope.stats_perso.globale = answer.globale;
                    $scope.stats_perso.annee = answer.annee;
                    $scope.stats_perso.annee.annee = answer.annee.list[answer.annee.list.length - 1].id;
                    $scope.stats_perso.started = true;
                    $scope.$apply();
                }
            };
            xhttp.open("GET", "php/get_stats_perso.php?id="+id_search);
            xhttp.send();
        }
    };

    $scope.color=function(index)
    {
        if (index%2===0) {
                return "color_1";
        }else{
            return "color_2";
        }
    };

    function decimalAdjust(type, value, exp) {
        // Si la valeur de exp n'est pas définie ou vaut zéro...
        if (typeof exp === 'undefined' || +exp === 0) {
            return Math[type](value);
        }
        value = +value;
        exp = +exp;
        // Si la valeur n'est pas un nombre
        // ou si exp n'est pas un entier...
        if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0)) {
            return NaN;
        }
        // Si la valeur est négative
        if (value < 0) {
            return -decimalAdjust(type, -value, exp);
        }
        // Décalage
        value = value.toString().split('e');
        value = Math[type](+(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp)));
        // Décalage inversé
        value = value.toString().split('e');
        return +(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp));
    }

    // Arrondi décimal
    if (!Math.round10) {
        Math.round10 = function(value, exp) {
            return decimalAdjust('round', value, exp);
        };
    }
    // Arrondi décimal inférieur
    if (!Math.floor10) {
        Math.floor10 = function(value, exp) {
            return decimalAdjust('floor', value, exp);
        };
    }
    // Arrondi décimal supérieur
    if (!Math.ceil10) {
        Math.ceil10 = function(value, exp) {
            return decimalAdjust('ceil', value, exp);
        };
    }


    $scope.prix= function (float)
    {
        float=Math.round10(float,-2);
        if (float<0)
        {
            float=-float;
            var cent=Math.round((float*100)%100);
            var euro=Math.floor(float);
            if (cent==0)
            {
                return "- "+euro+"€";
            }else{
                if (cent<10) {
                    cent="0"+cent;
                }
                return "- "+euro+"€"+cent;
            }

        }else{
            var cent=Math.round((float*100)%100);
            var euro=Math.floor(float);
            if (cent==0)
            {
                return euro+"€";
            }else{
                if (cent<10) {
                    cent="0"+cent;
                }
                return euro+"€"+cent;
            }
        }
    };

    $scope.volume=function (float) {
        float=Math.round10(float,-1);
        var dec=Math.round((float*10)%10);
        var ent=Math.floor(float);
        return ent+","+dec+"L";
    };

    $scope.classement=function (int) {
        if (int===1){
            return "1er";
        }else{
            return int+"ème";
        }
    };

    $scope.options_diagramme_biere = {
        chart: {
            type: 'pieChart',
            height: 500,
            width: 1000,
            x: function(d){return d.key;},
            y: function(d){return d.y;},
            showLabels: true,
            duration: 500,
            labelThreshold: 0.01,
            labelSunbeamLayout: true,
            legend: {
                margin: {
                    top: 5,
                    right: 50,
                    bottom: 5,
                    left: 0
                }
            }
        }
    };

    if(perso){
        $scope.stats_globales= new stats_item();
        $scope.stats_perso= new stats_item(true);
        $scope.start_stats_perso();
    }else{
        $scope.stats_globales= new stats_item(true);
        $scope.stats_perso= new stats_item();
        $scope.start_stats_globales();
    }
});
