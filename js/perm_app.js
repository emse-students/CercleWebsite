var app = angular.module('perm_app', ['ngFitText']);
app.controller('mainController', function($scope) {
  this.data       = {};
  this.data.dyn   = 'FitText';

    //Fonctions arrondi
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


    // Layer noir/transparent
    $scope.layer=false;
    // Layer pour les achats spéciaux
    $scope.layer2=false;

    // Def d'un client
    function client(){
    	this.search=null;
    	this.auto_c=false;
    }

    // Initialisation du client
    $scope.client=new client();

    // Chargement de la perm
    var answer;
	var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {

            answer = angular.fromJson(this.responseText);

            $scope.perm = answer.perm;
            for(let i= 0; i < $scope.perm.boissons.length; i++) {
              if ($scope.perm.boissons[i].fut_bouteille==='fut') {
                $scope.perm.boissons[i].order=2;
              }
              if ($scope.perm.boissons[i].fut_bouteille==='bouteille_unique') {
                $scope.perm.boissons[i].order=3;
              }
              if ($scope.perm.boissons[i].fut_bouteille==='bouteille_partage') {
                $scope.perm.boissons[i].order=4;
              }
              if ($scope.perm.boissons[i].fut_bouteille==='cubi') {
                $scope.perm.boissons[i].order=5;
              }
              if ($scope.perm.boissons[i].fut_bouteille==='verre') {
                $scope.perm.boissons[i].order=6;
              }
              if ($scope.perm.boissons[i].nom==='Kro') {
                $scope.perm.boissons[i].order=1;
              }
              if ($scope.perm.boissons[i].nom==='Soft') {
                $scope.perm.boissons[i].order=7;
              }
            }
            $scope.perm.client=null;
            $scope.users = answer.users;
            $scope.limit=answer.limite;
            $scope.coef_forum=answer.coef_forum;
            $scope.limite_forum=answer.limite_forum;
            $scope.moy_forum=answer.moy_forum;
            $scope.forum=answer.forum;
            $scope.code = answer.code;
            $scope.droits= answer.droits;
            $scope.happyconstantes = answer.happy;


            $scope.$apply();
        }
    };
    xhttp.open("GET", "php/get_perm.php", true);
    xhttp.send();



    $scope.color_boisson=function(boisson){

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

	$scope.prix= function (float){
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

		boisson.quantite++;
	};

	$scope.moins=function(boisson){
		if (boisson.quantite==0) {boisson.quantite--;}
		if (boisson.quantite>0) {boisson.quantite-=2;}
	};

	$scope.plus_new_c=function(boisson){

		boisson.prix_vente=Math.round10(boisson.prix_vente+0.1,-2);
	};

	$scope.moins_new_c=function(boisson){

		if (boisson.prix_vente>=0.1) {boisson.prix_vente=Math.round10(boisson.prix_vente-0.1,-2);}

	};

	$scope.arrondi=function(float){
		return Math.round10(float,-2);
	};

	$scope.esc=function(){
		var page = document.getElementById('page');
		page.style.overflow="scroll";


		$scope.layer=false;
		$scope.layer2=false;
		$scope.layer3=false;
	};

	$scope.check=function(){
    	var check=0;
    	if (typeof $scope.perm !== 'undefined')
    	{
    		if ($scope.perm.client==null){
	    		check=2;
	    	}else{
	    		if ($scope.perm.client.new_solde<0){check=1;}
	    		if ($scope.perm.client.new_solde<$scope.limit){check=2;}
	    	}
    	}

    	return check;

    }

    $scope.info_check=function(){
    	var info_check="";
    	if (typeof $scope.perm !== 'undefined')
    	{
	    	if ($scope.perm.client==null){
	    		info_check="Sélectionnez un nom de client valide";
	    	}else{
	    		if ($scope.perm.client.new_solde<0){info_check="Attention, solde négatif";}
	    		if ($scope.perm.client.new_solde<$scope.limit){info_check="/ ! \\ Solde trop négatif, transaction impossible / ! \\";}
	    	}
	    }
    	return info_check;

    }

    $scope.color_check=function(){
    	var color_check="";
    	if (typeof $scope.perm !== 'undefined')
    	{
	    	if ($scope.perm.client==null){
	    		color_check="";
	    	}else{
	    		color_check="vert";
	    		if ($scope.perm.client.new_solde<0){color_check="orange";}
	    		if ($scope.perm.client.new_solde<$scope.limit){color_check="rouge";}
	    	}
	    }
    	return color_check;

    }

    $scope.maj=function(){
    	var total =0;
    	var total_litre=0;
    	for(var i= 0; i < $scope.perm.boissons.length; i++)
  		{
  		     total+=$scope.perm.boissons[i].prix_vente*$scope.perm.boissons[i].quantite;
  		     if ($scope.perm.boissons[i].fut_bouteille=="fut") {
  		     	total_litre+=0.25*$scope.perm.boissons[i].quantite;
  		     }else{
  		     	total_litre+=$scope.perm.boissons[i].capacite*$scope.perm.boissons[i].quantite;
  		     }

  		}
      for(var i= 0; i < $scope.perm.forums.length; i++)
  		{
  		     total+=$scope.perm.forums[i].prix_vente*$scope.perm.forums[i].quantite;
  		     if ($scope.perm.forums[i].fut_bouteille=="fut") {
  		     	total_litre+=0.25*$scope.perm.forums[i].quantite;
  		     }else{
  		     	total_litre+=$scope.perm.forums[i].capacite*$scope.perm.forums[i].quantite;
  		     }

  		}
  		for(var i= 0; i < $scope.perm.consommables.length; i++)
  		{
  		     total+=$scope.perm.consommables[i].prix_vente*$scope.perm.consommables[i].quantite;
  		}

    	if ($scope.perm.client!=null)
    	{
    		$scope.perm.client.new_solde=$scope.perm.client.solde-total;
    		$scope.perm.client.total=total;
    		$scope.perm.client.total_litre=total_litre;
    	}
    }

    function new_conso (){
    	this.nom=null;
    	this.prix=false;
    	this.prix_vente=0;
    	this.quantite=1;
    }

    $scope.select_new_conso=function(){
    	$scope.new_conso = new new_conso();
    	var page = document.getElementById('page');
		page.style.overflow="hidden";
		$scope.layer=true;
		$scope.layer2=true;
    }

    $scope.add_new_conso=function(){
		var page = document.getElementById('page');
		page.style.overflow="scroll";
		$scope.perm.consommables.push($scope.new_conso);
		$scope.maj();
		$scope.layer=false;
		$scope.layer2=false;
	};


	//Forum
    function maj_prix_forum(index){
    for(var i= 0; i < $scope.perm.forums.length; i++) {
      if (i==index) {
        $scope.perm.forums[i].prix_vente=Math.round10($scope.perm.forums[i].prix_vente+($scope.perm.forums.length)*$scope.coef_forum,-2);
      }else{
        if ($scope.perm.forums[i].prix_vente-$scope.coef_forum<$scope.limite_forum){
          $scope.perm.forums[i].prix_vente=$scope.limite_forum;
        }else{
          $scope.perm.forums[i].prix_vente=Math.round10($scope.perm.forums[i].prix_vente-$scope.coef_forum,-2);
        }
      }

      var answer;
      var xhttp = new XMLHttpRequest();
      xhttp.open("POST", "php/maj_prix.php", true);
      xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhttp.send("id="+$scope.perm.forums[i].id+"&prix="+$scope.perm.forums[i].prix_vente);
    }
    $scope.maj_forum();
  }

    $scope.data=[];

    $scope.maj_forum=function(){
    var answer;
    for (var i = 0; i < $scope.data.length; i++) {
      $scope.data[i].values=0;
    }
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            answer = angular.fromJson(this.responseText);
            boissons=answer.boissons;

            for (var biere in answer.data) {
              if (answer.data.hasOwnProperty(biere)) {
                //alert(biere);
                //alert(answer.data[biere]);
                var not_finded = true;
                for (var i = 0; i < $scope.data.length; i++) {
                  if($scope.data[i].key==biere)
                  {
                    for (var j= 0; j < answer.data[biere].length; j++)
                    {
                      $scope.data[i].values+=(answer.data[biere][j].y_prix-$scope.data[i].prix)*answer.data[biere][j].z_nb;
                    }
                    not_finded=false;
                  }
                }
                if (not_finded) {
                  $scope.data.push({ values: 0, key: biere, prix : 0 });
                  for (var j= 0; j < answer.data[biere].length; j++) {
                    $scope.data[$scope.data.length-1].values+=answer.data[biere][j].y_prix*answer.data[biere][j].z_nb;
                  }
                }
              }
            }
            $scope.total_forum=0
            for (var i = 0; i < $scope.data.length; i++) {
              $scope.total_forum+=$scope.data[i].values;
            }

            $scope.$apply();

        }
    };
    xhttp.open("GET", "php/get_forum_stats.php?time=0", true);
    xhttp.send();
  };

    $scope.maj_forum();

    $scope.crack_boursier=function(){
    for(var i= 0; i < $scope.perm.forums.length; i++)
    {

      for(var i= 0; i < $scope.perm.forums.length; i++)
      {

        $scope.perm.forums[i].prix_vente=Math.round10((Math.random()-0.5)*0.5+$scope.moy_forum,-2);


        var answer;
        var xhttp = new XMLHttpRequest();
        xhttp.open("POST", "php/maj_prix.php", true);
        xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhttp.send("id="+$scope.perm.forums[i].id+"&prix="+$scope.perm.forums[i].prix_vente);
      }


    }
  };

    // Encaisser
    $scope.validate=function(){
        if ($scope.perm.client.new_solde<$scope.limit){
            alert("Solde insuffisant, transaction impossible")
        }else{
            var texte={};
            texte.id_user=$scope.perm.client.id;
            for(var i= 0; i < $scope.users.length; i++)
            {
                if ($scope.users[i].id==$scope.perm.client.id) {
                    var index= i;
                    i+=$scope.users.length;
                }
            }

            texte.id_perm=$scope.perm.id;
            texte.data=[];
            var j=0;
            for(var i= 0; i < $scope.perm.boissons.length; i++)
            {
                if ($scope.perm.boissons[i].quantite>0) {
                    add_histo($scope.perm.boissons[i].quantite,$scope.perm.boissons[i].nom);
                    texte.data[j]={};
                    texte.data[j].type="B";
                    texte.data[j].id=$scope.perm.boissons[i].id;
                    texte.data[j].nb=$scope.perm.boissons[i].quantite;
                    texte.data[j].prix=$scope.perm.boissons[i].quantite*$scope.perm.boissons[i].prix_vente;
                    if ($scope.perm.boissons[i].fut_bouteille=="fut") {
                        texte.data[j].litre=0.25*$scope.perm.boissons[i].quantite;
                    }else{
                        texte.data[j].litre=$scope.perm.boissons[i].capacite*$scope.perm.boissons[i].quantite;
                    }
                    $scope.perm.boissons[i].quantite=0;
                    j++;


                }

            }
            for(var i= 0; i < $scope.perm.forums.length; i++)
            {

                texte.data[j]={};
                texte.data[j].type="F";
                texte.data[j].id=$scope.perm.forums[i].id;
                texte.data[j].nb=$scope.perm.forums[i].quantite;
                if ($scope.perm.forums[i].quantite>0) {
                    texte.data[j].prix=$scope.perm.forums[i].quantite*$scope.perm.forums[i].prix_vente;
                    add_histo($scope.perm.forums[i].quantite,$scope.perm.forums[i].nom);
                }else{
                    texte.data[j].prix=$scope.perm.forums[i].prix_vente;
                }
                if ($scope.perm.forums[i].fut_bouteille=="fut") {
                    texte.data[j].litre=0.25*$scope.perm.forums[i].quantite;
                }else{
                    texte.data[j].litre=$scope.perm.forums[i].capacite*$scope.perm.forums[i].quantite;
                }
                for (var k = 0; k < $scope.perm.forums[i].quantite; k++) {
                    maj_prix_forum(i);
                }
                $scope.perm.forums[i].quantite=0;
                j++;




            }
            for(var i= 0; i < $scope.perm.consommables.length; i++)
            {

                if ($scope.perm.consommables[i].quantite>0)
                {
                    add_histo($scope.perm.consommables[i].quantite,$scope.perm.consommables[i].nom);
                    if (typeof $scope.perm.consommables[i].id == 'undefined')
                    {
                        texte.data[j]={};
                        texte.data[j].type="add";
                        texte.data[j].id=$scope.perm.consommables[i].nom;
                        texte.data[j].nb=$scope.perm.consommables[i].quantite;

                        texte.data[j].prix=$scope.perm.consommables[i].quantite*$scope.perm.consommables[i].prix_vente;
                        $scope.perm.consommables[i].quantite=0;
                        j++;
                    }else{
                        texte.data[j]={};
                        texte.data[j].type="C";
                        texte.data[j].id=$scope.perm.consommables[i].id;
                        texte.data[j].nb=$scope.perm.consommables[i].quantite;
                        texte.data[j].prix=$scope.perm.consommables[i].quantite*$scope.perm.consommables[i].prix_vente;
                        $scope.perm.consommables[i].quantite=0;
                        j++;
                    }



                }
            }
            texte=JSON.stringify(texte);
            var answer;
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {

                    answer = angular.fromJson(this.responseText);
                    if(answer.ok)
                    {
                        $scope.perm.total_vente+=parseFloat($scope.perm.client.total);
                        $scope.perm.total_litre+=parseFloat($scope.perm.client.total_litre);
                        $scope.users[index].solde-=parseFloat($scope.perm.client.total);
                        $scope.perm.client=null;
                        $scope.client.search=null;
                        $scope.$apply();
                        window.location.replace("perm.php#perm");
                    }
                }
            };
            xhttp.open("POST", "php/debit.php", true);
            xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhttp.send(texte);
        }

    };


    //Historique des 10 dernière transactions
    $scope.histo=[];

    add_histo=function(nb,nom){
  	var transaction={};
  	transaction.nom=$scope.perm.client.nom;
  	transaction.prenom=$scope.perm.client.prenom;
  	transaction.nb=nb;
  	transaction.nom_article=nom;
  	$scope.histo.splice(0,0,transaction);
	  if($scope.histo.length>10){
		$scope.histo.splice(-1,1);
	  }
  }


    // Rapport cuite/prix
	function colorTypeChecker(color) {
		var errMsg = 'ERREUR: Il y a un problème avec les paramètres';
		var colorType = '';
		switch (color.substr(0, 1)) {
			case '#':
				if (color.length !== 7) {
					return errMsg;
					/*Attention, les couleurs hex+alpha ne sont pas supportées dans ce snippet,
					et la couleur hex entrée doit contenir les 6 caractères prévus */
				} else {
					colorType = 'hex';
				}
				break;

			case 'r':
				if (color.substr(0, 4).toLowerCase() === 'rgba') {
					colorType = 'rgba';
				} else if (color.substr(0, 3).toLowerCase() === 'rgb') {
					colorType = 'rgb';
				}
				break;

			default:
				console.log('Type de couleur inconnu');
				return errMsg;
				break;
		}
		return colorType;
	}

	function hexToRgb(hex) {
		var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
		return result ? {
			r: parseInt(result[1], 16),
			g: parseInt(result[2], 16),
			b: parseInt(result[3], 16)
		} : null;
	}

	function anythingToRgba(color, colorStringType) {
		switch (colorStringType) {
			case 'hex':
				var colorObj = hexToRgb(color);
				colorObj.a = parseFloat('1');
				break;

			case 'rgb':
				var rgbRaw = color.replace(/[^\d,]/g, '').split(',');
				var colorObj = new Object();
				colorObj.r = rgbRaw[0];
				colorObj.g = rgbRaw[1];
				colorObj.b = rgbRaw[2];
				colorObj.a = parseFloat('1');
				break;

			case 'rgba':
				var rgbaRaw = color.replace(/[^\d,.]/g, '').split(',');
				var colorObj = new Object();
				colorObj.r = rgbaRaw[0];
				colorObj.g = rgbaRaw[1];
				colorObj.b = rgbaRaw[2];
				colorObj.a = parseFloat(rgbaRaw[3]);
				break;
		}

		return colorObj;
	}

	function gradientCalculator(startColor, endColor, steps) {
		//On vérifie que les paramètres en entrée soient corrects, pour la forme
		var errMsg = 'ERREUR: Il y a un problème avec les paramètres';

		if (typeof (steps) !== 'number') { return errMsg; }
		if (typeof (startColor) !== 'string' || typeof (endColor) !== 'string') { return errMsg }

		//On détermine le type de la couleur en entrée
		var startColorType = colorTypeChecker(startColor);
		var endColorType = colorTypeChecker(endColor);

		//On convertit tout en RGBA
		startRgbaObj = anythingToRgba(startColor, startColorType);
		endRgbaObj = anythingToRgba(endColor, endColorType);


		//On calcule la différence entre les éléments
		var deltaRgbaObj = new Object();
		deltaRgbaObj.r = endRgbaObj.r - startRgbaObj.r;
		deltaRgbaObj.g = endRgbaObj.g - startRgbaObj.g;
		deltaRgbaObj.b = endRgbaObj.b - startRgbaObj.b;
		deltaRgbaObj.a = endRgbaObj.a - startRgbaObj.a;

		//On calcule le différentiel en fonction du nombre d'étapes
		var deltaStep = new Object();
		deltaStep.r = Math.round(deltaRgbaObj.r / steps);
		deltaStep.g = Math.round(deltaRgbaObj.g / steps);
		deltaStep.b = Math.round(deltaRgbaObj.b / steps);
		deltaStep.a = Number(deltaRgbaObj.a / steps).toFixed(3);

		//On retourne l'objet contenant les delta relatifs à chaque élément.
		return deltaStep;
	}

	$scope.colorLader=function (i) {
		i=Math.round(i);
		var style = {};
		//var deltaStep=gradientCalculator("#D10000","#00CC18",100);
		style.backgroundColor="hsl("+i+",100%,50%)"
			//"rgb("+(209+deltaStep.r*i)+","+(deltaStep.g*i)+","+(deltaStep.b*i)+")";
		return style;
	};

	$scope.cuitePrix=function (boisson) {
		if (boisson.fut_bouteille=='bouteille_unique'){
			return Math.round(50*(boisson.degre*boisson.capacite)/(boisson.prix_vente));
		}else{
			return Math.round(50*(boisson.degre*0.25)/(boisson.prix_vente));
		}
	};

	//Happy-hour
    $scope.boissonHorsHappyHour = [];
    $scope.isHappyHour = false;
    $scope.checkCode = false;
    $scope.happy={};
    $scope.happy.happyHourCode = null;
    $scope.happyHourCodeFalse = false;

    function cloneObj(obj) {
        if (null == obj || "object" !== typeof obj) return obj;
        var copy = obj.constructor();
        for (var attr in obj) {
            if (obj.hasOwnProperty(attr)) copy[attr] = obj[attr];
        }
        return copy;
    }

    function copyArray(array){
        newArray=[];
        for(let i=0; i<array.length; i++){
            newArray[i]=cloneObj(array[i]);
        }
        return newArray;
    }

    $scope.checkHappyHourRights = function () {
        if ($scope.droits === "cercle" || Number($scope.happy.happyHourCode) === $scope.code){
            $scope.checkCode = false;
            $scope.happyHourCodeFalse = false;
            return true;
        } else if($scope.checkCode === true) {
            $scope.happyHourCodeFalse = true;
        } else {
            $scope.checkCode = true;
        }
    };

    $scope.happyHour = function () {
        if ($scope.isHappyHour) {
            $scope.perm.boissons = copyArray($scope.boissonHorsHappyHour);
            $scope.isHappyHour = false;
            $scope.happy.happyHourCode = null;
        } else {
            if ($scope.checkHappyHourRights()){
                $scope.boissonHorsHappyHour = copyArray($scope.perm.boissons);

                for(let i= 0; i < $scope.perm.boissons.length; i++)
                {
                    if ($scope.happyconstantes.bypercent===1){
                        if ($scope.perm.boissons[i].prix_vente > 1) {
                            $scope.perm.boissons[i].prix_vente = $scope.perm.boissons[i].prix_vente*(1-$scope.happyconstantes.coef);
                        }
                    } else {
                        if ($scope.perm.boissons[i].prix_vente > $scope.happyconstantes.prix) {
                            $scope.perm.boissons[i].prix_vente = $scope.happyconstantes.prix;
                        }
                    }
                }
                $scope.isHappyHour = true;
            }
        }
        //$scope.maj();
    }

    checkHappyHourRights = function () {
        if (happyHourCode === $scope.code){
            $scope.happyHourCode
        }
    }
});
