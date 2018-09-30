var app = angular.module('recharge_app', []);
app.controller('mainController', function($scope) {


    var answer;
	var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {

            answer = angular.fromJson(this.responseText);


            $scope.client={};
            $scope.client.client=null;
            $scope.users = answer.users;
            $scope.search=null;
            $scope.client.recharge_ok=false;

            $scope.$apply();
        }
    };

    xhttp.open("GET", "php/get_users.php", true);
    xhttp.send();

    var answer2;
    var xhttp2 = new XMLHttpRequest();
    xhttp2.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {

            answer2 = angular.fromJson(this.responseText);

            $scope.operations = answer2.operations;
            $scope.$apply();
        }
    };
    xhttp2.open("GET", "php/get_list_recharge.php", true);
    xhttp2.send();


    $scope.color=function(index,operation)
    {
        if (operation.type=="A")
        {
            if (index%2==0) {
                return "color_3";
            }else{
                return "color_4";
            }
        }else{
            if (index%2==0) {
                return "color_1";
            }else{
                return "color_2";
            }
        }
    }

    $scope.annule=function(operation)
    {
        var continu=confirm("Voulez-vous vraiment annuler cette opértion ?");
        if (continu)
        {
            var answer;
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {

                    answer = angular.fromJson(this.responseText);
                    if(answer=='ok')
                    {

                        for(var i= 0; i < $scope.operations.length; i++)
                        {
                            if ($scope.operations[i].id==operation.id) {
                                var index= i;
                                i+=$scope.operations.length;
                            }
                        }
                        $scope.operations.splice(index,1);
                        $scope.$apply();

                    }
                }
            };
            xhttp.open("POST", "php/annule_operation.php", true);
            xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhttp.send("id="+operation.id);
        }
    }

    $scope.alert=function(str)
    {
        var answer;
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {

                answer = angular.fromJson(this.responseText);
                if(answer=='ok')
                {

                    alert(str);


                }
            }
        };
        xhttp.open("POST", "php/annule_operation.php", true);
        xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhttp.send("id="+str);

    }

    $scope.typeof = function(obj) {
        return typeof obj ;

    }

    $scope.getNumber = function(num) {
        return new Array(num);
    }

    var date = new Date();
    $scope.date={};
    $scope.date.debut={};
    $scope.date.debut.jour=1+"";
    $scope.date.debut.mois=1+"";
    $scope.date.debut.annee=2000+"";
    $scope.date.fin={};
    $scope.date.fin.jour=date.getDate()+1+"";
    $scope.date.fin.mois=date.getMonth()+1+"";
    $scope.date.fin.annee=date.getFullYear()+"";

    $scope.datestr=function(timestamp){
        // Create a new JavaScript Date object based on the timestamp
        // multiplied by 1000 so that the argument is in milliseconds, not seconds.
        var date = new Date(timestamp*1000);
        // Hours part from the timestamp
        var annee = date.getFullYear();
        // Minutes part from the timestamp
        var mois = "0" + (date.getMonth()+1);
        // Seconds part from the timestamp
        var jour = "0" + date.getDate();
        // Hours part from the timestamp
        var hours = "0"+date.getHours();
        // Minutes part from the timestamp
        var minutes = "0" + date.getMinutes();


        // Will display time in 10:30:23 format
        var formattedTime = jour.substr(-2)+"/"+mois.substr(-2)+"/"+annee+" "+hours.substr(-2) + ':' + minutes.substr(-2);

        return formattedTime;
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

	$scope.plus=function(boisson){

		$scope.client.client.montant++;
		$scope.maj();
	};

	$scope.moins=function(boisson){

		$scope.client.client.montant--;
		$scope.maj();


	};

	$scope.maj=function(){

		$scope.client.client.new_solde=$scope.client.client.solde+$scope.client.client.montant;


	};

	$scope.validate=function(){

		for(var i= 0; i < $scope.users.length; i++)
		{
			if ($scope.users[i].id==$scope.client.client.id) {
				var index= i;
				i+=$scope.users.length;
			}
		}

		var answer;
		var xhttp = new XMLHttpRequest();
	    xhttp.onreadystatechange = function() {
	        if (this.readyState == 4 && this.status == 200) {

	            answer = angular.fromJson(this.responseText);
	            if(answer.ok)
	            {
	            	$scope.users[index].solde+=$scope.client.client.montant;
	            	$scope.client.client=null;
	            	$scope.search=null;
	            	$scope.client.recharge_ok=true;
	            	$scope.$apply();

	            }
	        }
	    };
	    xhttp.open("POST", "php/recharge.php", true);
	    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	    xhttp.send("id_user="+$scope.client.client.id+"&montant="+$scope.client.client.montant);
	}

});
