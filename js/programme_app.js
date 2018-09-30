var app = angular.module('programme_app', []);
app.controller('mainController', function($scope) {

	
	maj=function(){

    	var answer;
    	var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {

                answer = angular.fromJson(this.responseText);

                $scope.boissons = answer;
                
                $scope.$apply();
            }
        };
        xhttp.open("GET", "php/get_boisson.php", true);
        xhttp.send();
    }

    $scope.associe=function (boisson) {
        var old_id=boisson.id;
        var new_id=boisson.boisson_asso.id;
        var answer;
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {

                answer = angular.fromJson(this.responseText);

                if (answer=="ok") {
                   $scope.boissons.splice($scope.boissons.indexOf(boisson), 1);
                   $scope.$apply();
                }
                
                
            }
        };
        xhttp.open("GET", "php/maj_boisson.php?old_id="+old_id+"&new_id="+new_id, true);
        xhttp.send();
    }

    $scope.autre=function (boisson) {
        var old_id=boisson.id;
        var old_nom=boisson.nom
        var answer;
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {

                answer = angular.fromJson(this.responseText);

                if (answer=="ok") {
                   $scope.boissons.splice($scope.boissons.indexOf(boisson), 1);
                   $scope.$apply();
                }
                
                
            }
        };
        xhttp.open("GET", "php/maj_autre.php?old_id="+old_id+"&old_nom="+old_nom, true);
        xhttp.send();
    }

    $scope.color=function($index){
        if($index%2==0){return "color_1";}else{return "color_2";}
    }
    maj();
    
    
});
