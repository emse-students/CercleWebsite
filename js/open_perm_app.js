var app = angular.module('open_perm_app', ['ngFitText']);
app.controller('mainController', function($scope) {
  this.data       = {};
  this.data.dyn   = 'FitText';

    $scope.new_consommable={};
	$scope.new_boisson={};

    $scope.layer=false;
    $scope.layer2=false;
    $scope.layer3=false;


	if(droit_cercle=="cercle"){
		$scope.droit=true;
	}else{
		$scope.droit=false;
	}



    var answer;
	var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {

            answer = angular.fromJson(this.responseText);

            $scope.derniere_perm = answer.derniere_perm;
            $scope.permslist = answer.nom_perms;
            $scope.boissons= answer.boissons;
            $scope.contenus= answer.contenus;
            $scope.consommables= answer.consommables;
            $scope.new_perm={};
            $scope.new_perm.perm_name= null;

            $scope.isin_perm=function(perm_name){
				for (var i = $scope.permslist.length - 1; i >= 0; i--) {
					if($scope.permslist[i].nom==perm_name){
						return true;
					}
				}
				return false;
			};

			$scope.isin_boissons=function(boisson_name){
				for (var i = $scope.boissons.length - 1; i >= 0; i--) {
					if($scope.boissons[i].nom==boisson_name){
						$scope.new_boisson.boisson=$scope.boissons[i];
						return true;
					}
				}
				return false;
			};

			$scope.isin_consommables=function(consommable_name){
				for (var i = $scope.consommables.length - 1; i >= 0; i--) {
					if($scope.consommables[i].nom==consommable_name){
						$scope.new_consommable.consommable=$scope.consommables[i];
						return true;
					}
				}
				return false;
			};

			$scope.find_boissons=function(boisson_name){
				for (var i = $scope.boissons.length - 1; i >= 0; i--) {
					if($scope.boissons[i].id==boisson_name){
						return $scope.boissons[i];
					}
				}
				return false;
			};

			$scope.find_consommables=function(consommable_name){
				if ($scope.new_consommable.new) {
                    return $scope.new_consommable.new_consommable;
                }else {
                    for (var i = $scope.consommables.length - 1; i >= 0; i--) {
                        if($scope.consommables[i].nom===consommable_name){
                            return $scope.consommables[i];
                        }
                    }
                    return false;
                }
			};

			$scope.add_boisson=function(boisson){
				var page = document.getElementById('page');
				page.style.overflow="scroll";
				var newboisson={};
				newboisson.id=boisson.contenant.id_boisson;
				newboisson.nom=boisson.nom;
				newboisson.type=boisson.type;
				newboisson.degre=boisson.degre
				newboisson.fut_bouteille=boisson.contenant.type;
				newboisson.consigne=boisson.contenant.consigne;
				newboisson.prix_vente=boisson.contenant.prix_vente;
				newboisson.capacite=boisson.contenant.capacite;
				$scope.derniere_perm.boissons.push(newboisson);

				$scope.layer=false;
				$scope.layer2=false;
			};

      $scope.add_forum=function(boisson){
				var page = document.getElementById('page');
				page.style.overflow="scroll";
				var newboisson={};
				newboisson.id=boisson.contenant.id_boisson;
				newboisson.nom=boisson.nom;
				newboisson.type=boisson.type;
				newboisson.degre=boisson.degre
				newboisson.fut_bouteille=boisson.contenant.type;
				newboisson.consigne=boisson.contenant.consigne;
				newboisson.prix_vente=boisson.contenant.prix_vente;
				newboisson.capacite=boisson.contenant.capacite;
				$scope.derniere_perm.forums.push(newboisson);

				$scope.layer=false;
				$scope.layer2=false;
			};

			$scope.add_consommable=function(consommable){
				var page = document.getElementById('page');
				page.style.overflow="scroll";

				$scope.derniere_perm.consommables.push(consommable);

				$scope.layer=false;
				$scope.layer3=false;
			};


            $scope.$apply();
        }
    };
    xhttp.open("GET", "php/get_last_perm.php", true);
    xhttp.send();



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

    $scope.color_boisson=function(boisson)
    {

    	if (boisson.nom=="Soft")
    	{
    		return "soft";
    	}

    	if (boisson.nom=="Kro")
    	{
    		return "kro";
    	}else{
    		if (boisson.type=="Blonde") {
    			return "biere_blonde";
    		}
    		if (boisson.type=="Rouge") {
    			return "biere_rouge";
    		}
    		if (boisson.type=="Blanche") {
    			return "biere_blanche";
    		}
    		if (boisson.type=="Brune") {
    			return "biere_brune";
    		}
    		if (boisson.type=="Ambrée") {
    			return "biere_ambre";
    		}
    		if (boisson.type=="Aromatisée") {
    			return "biere_arome";
    		}
    		if (boisson.type=="Vin") {
    			return "vin";
    		}
        if (boisson.type=="Autre") {
    			return "autre";
    		}
        if (boisson.type=="Cidre") {
    			return "cidre";
    		}
    		return "";
    	}
    };
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

		boisson.prix_vente=Math.round10(boisson.prix_vente+0.1,-2);
	};

	$scope.moins=function(boisson){

		if (boisson.prix_vente>=0.1) {boisson.prix_vente=Math.round10(boisson.prix_vente-0.1,-2);}

	};


	$scope.delete_boisson=function(boisson){
		$scope.derniere_perm.boissons.splice($scope.derniere_perm.boissons.indexOf(boisson), 1);
	};

  $scope.delete_forum=function(boisson){
		$scope.derniere_perm.forums.splice($scope.derniere_perm.forums.indexOf(boisson), 1);
	};

	$scope.delete_consommable=function(consommable){
		$scope.derniere_perm.consommables.splice($scope.derniere_perm.consommables.indexOf(consommable), 1);
	};



	$scope.alert = function(str){
		alert(str);
	};

	$scope.select_boisson=function(){
		var page = document.getElementById('page');
		page.style.overflow="hidden";
		$scope.new_boisson={};
		$scope.new_boisson.contenu=null;
		$scope.layer=true;
		$scope.layer2=true;
    $scope.boisson_forum=false;
	};

  $scope.select_forum=function(){
		var page = document.getElementById('page');
		page.style.overflow="hidden";
		$scope.new_boisson={};
		$scope.new_boisson.contenu=null;
		$scope.layer=true;
		$scope.layer2=true;
    $scope.boisson_forum=true;
	};

    function new_conso (){
    	this.id=null;
        this.nom=null;
        this.prix=false;
        this.prix_vente=0;
        this.quantite=0;
    }

	$scope.select_consommable=function(){
		var page = document.getElementById('page');
		page.style.overflow="hidden";
		$scope.new_consommable={};
        $scope.new_consommable.new=false;
        $scope.new_consommable.new_consommable = new new_conso();
		$scope.layer=true;
		$scope.layer3=true;
	};

	$scope.esc=function(){
		var page = document.getElementById('page');
		page.style.overflow="scroll";


		$scope.layer=false;
		$scope.layer2=false;
		$scope.layer3=false;
	};

	$scope.open_new_perm=function()
	{
		var continu = true;

		if($scope.new_perm.perm_name==$scope.derniere_perm.nom)
		{
			continu=confirm("Le nom de la nouvelle perm est le même que celui de la prècédente, êtes-vous sûr de continuer ?");
		}

		if(!$scope.isin_perm($scope.new_perm.perm_name))
		{
			continu=false;
			alert("Veuillez entrer un nom de perm existant");
		}
		if (continu)
		{
			var answer;
			var xhttp = new XMLHttpRequest();
		    xhttp.onreadystatechange = function() {
		        if (this.readyState == 4 && this.status == 200) {

		            answer = angular.fromJson(this.responseText);
		            $scope.maj_perm();
            }
		    };
		    xhttp.open("GET", "php/new_perm.php?name="+$scope.new_perm.perm_name, true);
		    xhttp.send();
		}
	};

	$scope.maj_perm=function()
	{
      var text={};
    	text.data=$scope.derniere_perm.boissons;
    	text.type="boisson";
    	text=JSON.stringify(text);

      var answer2;
		  var xhttp2 = new XMLHttpRequest();
	    xhttp2.onreadystatechange = function() {
	        if (this.readyState == 4 && this.status == 200) {

	            answer2 = angular.fromJson(this.responseText);
	            if(answer2.ok)
	            {
	            	var text2={};
	            	text2.data=$scope.derniere_perm.consommables;
	            	text2.type="consommable";
	            	text2=JSON.stringify(text2);

		            var answer3;
      					var xhttp3 = new XMLHttpRequest();
      				    xhttp3.onreadystatechange = function() {
      				        if (this.readyState == 4 && this.status == 200) {

      				            answer3 = angular.fromJson(this.responseText);
      				            if(answer3.ok)
      				            {
      				            	if(forum)
                            {
                              var text3={};
              	            	text3.data=$scope.derniere_perm.forums;
              	            	text3.type="forum";
              	            	text3=JSON.stringify(text3);

              		            var answer4;
                    					var xhttp4 = new XMLHttpRequest();
                    				    xhttp4.onreadystatechange = function() {
                    				        if (this.readyState == 4 && this.status == 200) {

                    				            answer4 = angular.fromJson(this.responseText);
                    				            if(answer4.ok)
                    				            {
                                          window.location.replace("php/open_perm.php");
                    				            }
                    				        }
                    				    };
                    				    xhttp4.open("POST", "php/maj_perm.php", true);
                    				    xhttp4.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    				    xhttp4.send(text3);
                            }else{
                              window.location.replace("php/open_perm.php");
                            }
      				            }
      				        }
      				    };
      				    xhttp3.open("POST", "php/maj_perm.php", true);
      				    xhttp3.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      				    xhttp3.send(text2);
      				}
			    }
	    };
	    xhttp2.open("POST", "php/maj_perm.php", true);
	    xhttp2.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	    xhttp2.send(text);
	};


	var answer;
	var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {

            answer = angular.fromJson(this.responseText);


            var constantes_list = answer;

            $scope.decode=function(code){
				if (code===constantes_list[2].valeur)
				{
					$scope.droit=true;
				}
			}

        }
    };

    xhttp.open("GET", "php/get_constante.php", true);
    xhttp.send();


});
