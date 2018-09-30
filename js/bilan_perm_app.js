var app = angular.module('bilan_perm_app', []);
app.controller('mainController', function($scope) {

  $scope.list_perm=[];
	bilan_perm_items=function(array){
    for (var i = 0; i < array.length; i++) {

      array[i].screen=false;
  		array[i].class="";
      array[i].initialized=false;

      array[i].init=function(){
        if (!this.initialized) {
          var a=this;
          var answer;
    			var xhttp = new XMLHttpRequest();
    		    xhttp.onreadystatechange = function() {
    		        if (this.readyState == 4 && this.status == 200) {

    		            answer = angular.fromJson(this.responseText);

    		            a.datas=answer;

    		            $scope.$apply();
    		        }
    		    };

    		    xhttp.open("GET", "php/get_bilan_perm.php?id="+this.id, true);
    		    xhttp.send();
            this.initialized=true;
        }
      };

  		array[i].activate=function(){
  			if (this.screen) {var activate=true;}
  			else {var activate=false;}

        for (var i = 0; i < $scope.list_perm.length; i++) {
          $scope.list_perm[i].screen=false;
      		$scope.list_perm[i].class="";
        }

  			$scope.message.statu="none";

  			if (!activate) {
  				this.screen=true;
  				this.class= "selected";
  			}
      }

      $scope.list_perm=array;
    }
	}

	$scope.message={};
	$scope.message.statu="none";
	$scope.message.texte="";


  list_perm=function(){
			var answer;
			var xhttp = new XMLHttpRequest();
		    xhttp.onreadystatechange = function() {
		        if (this.readyState == 4 && this.status == 200) {

		            answer = angular.fromJson(this.responseText);

		            bilan_perm_items(answer);
		            $scope.$apply();
		        }
		    };

		    xhttp.open("GET", "php/get_list_perms.php", true);
		    xhttp.send();
	};
  list_perm();

	$scope.color=function(index)
  {
		if (index%2==0) {
			return "color_1";
		}else{
			return "color_2";
		}
  }

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


});
