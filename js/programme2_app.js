var app = angular.module('programme2_app', []);
app.controller('mainController', function($scope) {

    var answer;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {

            answer = angular.fromJson(this.responseText);

            $scope.contenants = answer.contenants;
            
            $scope.$apply();
        }
    };
    xhttp.open("GET", "php/get_contenant.php", true);
    xhttp.send();
	
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
        xhttp.open("GET", "php/get_consommables.php", true);
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
                   maj();
                }
                
                
            }
        };
        xhttp.open("GET", "php/maj_consommable.php?old_id="+old_id+"&new_id="+new_id, true);
        xhttp.send();
    }

    $scope.autre=function (boisson) {
        var old_id=boisson.id;
        var old_nom=boisson.nom;
        var id_contenant=boisson.contenant.id;
        var answer;
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {

                answer = angular.fromJson(this.responseText);

                if (answer=="ok") {
                   maj();
                }
                
                
            }
        };
        xhttp.open("GET", "php/maj_autre2.php?old_id="+old_id+"&old_nom="+old_nom+"&id_contenant="+id_contenant, true);
        xhttp.send();
    }

    $scope.color=function($index){
        if($index%2==0){return "color_1";}else{return "color_2";}
    }

    maj();
    
    
});
