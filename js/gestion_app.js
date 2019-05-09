var app = angular.module('gestion_app', []);
app.controller('mainController', function($scope) {


	gestion_item=function(){
		this.screen=false;
		this.class="";

		this.activate=function(){
			if (this.screen) {var activate=true;}
			else {var activate=false;}
			$scope.boisson.screen=false;
			$scope.boisson.class="";

			$scope.contenant.screen=false;
			$scope.contenant.class="";

			$scope.compte.screen=false;
			$scope.compte.class="";

			$scope.constante.screen=false;
			$scope.constante.class="";

			$scope.perm.screen=false;
			$scope.perm.class="";

			$scope.new_user.screen=false;
			$scope.new_user.class="";

			$scope.perm_list.screen=false;
			$scope.perm_list.class="";

			$scope.message.statu="none";

			if (!activate) {
				this.screen=true;
				this.class= "selected";
			}
		}

		this.order=function(str){
	        if (this.ordervalue==str) {
	            this.ordervalue="-"+str;
	        }else{
	           this.ordervalue=str;
	        }
    	}
	}


	$scope.boisson= new gestion_item();
	$scope.contenant= new gestion_item();
	$scope.compte= new gestion_item();
	$scope.constante= new gestion_item();
	$scope.perm= new gestion_item();
	$scope.new_user= new gestion_item();
	$scope.perm_list= new gestion_item();


	$scope.message={};
	$scope.message.statu="none";
	$scope.message.texte="";

	$scope.constantes_list=null;
	$scope.boissons_list=null;

	$scope.start_boissons=function(){
		if ($scope.contenus_list==null) {
			var answer;
			var xhttp = new XMLHttpRequest();
		    xhttp.onreadystatechange = function() {
		        if (this.readyState == 4 && this.status == 200) {

		            answer = angular.fromJson(this.responseText);


		            $scope.contenus_list = answer.contenus;
		            $scope.contenants_list = answer.contenants;

		            $scope.$apply();
		        }
		    };

		    xhttp.open("GET", "php/get_contenu.php", true);
		    xhttp.send();
		}

	};

	$scope.get_all_users=function(){
		if ($scope.contenus_list==null) {
			var answer;
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {

					answer = angular.fromJson(this.responseText);


					$scope.all_users = answer.users;
					$scope.new_user.user=null;
					$scope.new_user.search=null;
					$scope.new_user.auto_c=false;
					$scope.new_user.comfirme_hard_user=false;
					$scope.new_user.hard_user= null;
					$scope.new_user.montant= 0;

					$scope.$apply();
				}
			};

			xhttp.open("GET", "php/get_all_users.php", true);
			xhttp.send();
		}

	};

	$scope.start_constante=function(){
		if ($scope.constantes_list==null) {
			var answer;
			var xhttp = new XMLHttpRequest();
		    xhttp.onreadystatechange = function() {
		        if (this.readyState == 4 && this.status == 200) {

		            answer = angular.fromJson(this.responseText);


		            $scope.constantes_list = answer;
		            $scope.$apply();
		        }
		    };

		    xhttp.open("GET", "php/get_constante.php", true);
		    xhttp.send();
		}

	};

	$scope.start_compte=function(){
		if ($scope.users_list==null) {
			var answer2;
			var xhttp2 = new XMLHttpRequest();
		    xhttp2.onreadystatechange = function() {
		        if (this.readyState == 4 && this.status == 200) {

		            answer2 = angular.fromJson(this.responseText);


		            $scope.users_list = answer2.users;
		            $scope.solde_positif = answer2.solde_positif;
		            $scope.solde_negatif = answer2.solde_negatif;
		            $scope.perm.name=null;
		            $scope.perm.user_array=[];
		            $scope.perm.new_name=true;
		            $scope.perm.new_user={};
		            $scope.$apply();

		        }
		    };

		    xhttp2.open("GET", "php/get_users.php", true);
		    xhttp2.send();
		}

	};

	$scope.start_perm=function(){
		if ($scope.nom_perm_list==null) {
			var answer;
			var xhttp = new XMLHttpRequest();
		    xhttp.onreadystatechange = function() {
		        if (this.readyState == 4 && this.status == 200) {

		            answer = angular.fromJson(this.responseText);


		            $scope.nom_perm_list = answer;

		            $scope.control_name_perm=function(perm_name)
					{
						var classe = "correct_input";
						var i = 0;
						var run = true;
						while ( i < $scope.nom_perm_list.length && run)
						{
							var myRegex2 = new RegExp("^"+perm_name+"$", "i");
							if (myRegex2.test($scope.nom_perm_list[i].nom))
							{
								classe = "wrong_input";
								run = false;
							}else{
								var myRegex = new RegExp(perm_name, "i");
								if ( myRegex.test($scope.nom_perm_list[i].nom))
								{
									if (classe!='wrong_input')
									{
										classe = $scope.nom_perm_list[i].nom;
									}
								}
							}
							i++;
						}

						return classe;
					};

		            $scope.start_compte();

		            $scope.reset_perm_list=function(){
		            	for (var i = $scope.nom_perm_list.length - 1; i >= 0; i--) {
		            		$scope.nom_perm_list[i].view=false;
		            	}
		            };
		        }
		    };

		    xhttp.open("GET", "php/get_nom_perm.php", true);
		    xhttp.send();
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

	$scope.plus=function(boisson){

		$scope.client.client.montant++;
		$scope.maj();
	};

	$scope.moins=function(boisson){

		if ($scope.client.client.montant>=1) {$scope.client.client.montant--;}
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

	$scope.control_mail=function(str)
	{
		return (/\S+@etu\.emse\.fr$/.test(str))
	};



	$scope.valid_new_user=function()
	{
		var montant = $scope.new_user.montant?$scope.new_user.montant : 0;
		var cotis= $scope.constantes_list[1].valeur;
		var solde = $scope.new_user.montant - $scope.constantes_list[1].valeur;
		var continu=true;
		if (solde < 0)
		{
			continu=confirm("Le solde sera négatif, voulez vous continuer ?")
		}
		if (continu)
		{
			var login = RegExp.$1
			var answer;
			var xhttp = new XMLHttpRequest();
		    xhttp.onreadystatechange = function() {
		        if (this.readyState == 4 && this.status == 200) {

		            answer = angular.fromJson(this.responseText);
		            if(answer.statu=="ok")
		            {
		            	$scope.message.statu="ok";
						$scope.message.texte="Compte créé avec succès";
						$scope.new_user.montant=0;
		            	$scope.new_user.mail=null;
						$scope.new_user.user=null;
		            	$scope.$apply();
		            	window.location.replace("gestion.php#message");

		            }else{
		            	if (answer.statu=="exist")
		            	{
		            		$scope.message.statu="error";
							$scope.message.texte="Ce compte existe déjà";
			            	$scope.$apply();
			            	window.location.replace("gestion.php#message");
		            	}
		            }
		        }
		    };
		    xhttp.open("POST", "php/new_user.php", true);
		    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			if ($scope.new_user.user) {
				xhttp.send("id_user="+$scope.new_user.user.id+"&montant="+montant+"&cotis="+cotis);
			} else {
				xhttp.send("nom="+$scope.new_user.hard_user.nom+"&prenom="+$scope.new_user.hard_user.prenom+"&montant="+montant+"&cotis="+cotis+"&promo="+$scope.new_user.hard_user.promo);
			}

		}
	};

	$scope.create_hard_user=function()
	{
		$scope.new_user.hard_user = {};
		$scope.new_user.hard_user.nom = '';
		$scope.new_user.hard_user.prenom = '';
		$scope.new_user.hard_user.promo = 0;
	};

	$scope.valid_new_perm=function(){
		var texte={};
		texte.perm_name=$scope.perm.name;
		texte.data=[];
		for(var i= 0; i < $scope.perm.user_array.length; i++)
		{
			texte.data.push($scope.perm.user_array[i].id);
		}
		texte=JSON.stringify(texte);
		var answer;
		var xhttp = new XMLHttpRequest();
	    xhttp.onreadystatechange = function() {
	        if (this.readyState == 4 && this.status == 200) {

	            answer = angular.fromJson(this.responseText);
	            if(answer.ok)
	            {
	            	$scope.message.statu="ok";
					$scope.message.texte="Perm créée avec succès";
	            	$scope.perm.user_array=[];
	            	$scope.perm.name=null;

	            	$scope.$apply();
	            	window.location.replace("gestion.php#message");
	            }
	        }
	    };
	    xhttp.open("POST", "php/new_perm_name.php", true);
	    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	    xhttp.send(texte);
	};

	$scope.valid_constante=function(constante){
		var answer;
		var xhttp = new XMLHttpRequest();
	    xhttp.onreadystatechange = function() {
	        if (this.readyState == 4 && this.status == 200) {

	            answer = angular.fromJson(this.responseText);
	            if(answer=="ok")
	            {
	            	$scope.message.statu="ok";
					$scope.message.texte="La valeur de la constante a été mise à jour";

	            	$scope.$apply();
	            }

	        }
	    };
	    xhttp.open("POST", "php/maj_constante.php", true);
	    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	    xhttp.send("id="+constante.id+"&valeur="+constante.valeur);
	};

	$scope.valid_contenant=function(contenu){
		if (typeof contenu.capacite === 'undefined') {
	     	alert("La capacite doit être entrée avec une virgule, pas un point.")
	    }else{
			var answer;
			var xhttp = new XMLHttpRequest();

		    xhttp.onreadystatechange = function() {
		        if (this.readyState == 4 && this.status == 200) {

		            answer = angular.fromJson(this.responseText);
		            if(answer=="ok")
		            {
		            	$scope.message.statu="ok";
						$scope.message.texte="Le contenant a été mis à jour";

		            	$scope.$apply();
		            }

		        }
		    };
		    xhttp.open("POST", "php/maj_contenant.php", true);
		    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		    xhttp.send("id="+contenu.id+"&nom="+contenu.nom+"&capacite="+contenu.capacite+"&type="+contenu.type);
		}
	};

	$scope.maj_contenu_nom=function(contenu){
		var answer;
		var xhttp = new XMLHttpRequest();
	    xhttp.onreadystatechange = function() {
	        if (this.readyState == 4 && this.status == 200) {

	            answer = angular.fromJson(this.responseText);
	            if(answer=="ok")
	            {
	            	$scope.message.statu="ok";
					$scope.message.texte="Le nom de la boisson a été mise à jour";

	            	$scope.$apply();
	            }

	        }
	    };
	    xhttp.open("POST", "php/maj_contenu.php", true);
	    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	    xhttp.send("id="+contenu.id+"&nom="+contenu.nom);
	};

	$scope.maj_perm_nom=function(perm){
		var answer;
		var xhttp = new XMLHttpRequest();
	    xhttp.onreadystatechange = function() {
	        if (this.readyState == 4 && this.status == 200) {

	            answer = angular.fromJson(this.responseText);
	            if(answer=="ok")
	            {
	            	$scope.message.statu="ok";
					$scope.message.texte="Le nom de la perm a été mise à jour";

	            	$scope.$apply();
	            }

	        }
	    };
	    xhttp.open("POST", "php/maj_nom_perm.php", true);
	    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	    xhttp.send("id="+perm.id+"&nom="+perm.nom);
	};

	$scope.maj_contenu_type=function(contenu){
		var answer;
		var xhttp = new XMLHttpRequest();
	    xhttp.onreadystatechange = function() {
	        if (this.readyState == 4 && this.status == 200) {

	            answer = angular.fromJson(this.responseText);
	            if(answer=="ok")
	            {
	            	$scope.message.statu="ok";
					$scope.message.texte="Le type de la boisson a été mise à jour";

	            	$scope.$apply();
	            }

	        }
	    };
	    xhttp.open("POST", "php/maj_contenu.php", true);
	    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	    xhttp.send("id="+contenu.id+"&type="+contenu.type);
	};

	$scope.maj_contenu_degre=function(contenu){
		var answer;
		var xhttp = new XMLHttpRequest();
	    xhttp.onreadystatechange = function() {
	        if (this.readyState == 4 && this.status == 200) {

	            answer = angular.fromJson(this.responseText);
	            if(answer=="ok")
	            {
	            	$scope.message.statu="ok";
					$scope.message.texte="Le degré de la boisson a été mise à jour";

	            	$scope.$apply();
	            }

	        }
	    };
	    xhttp.open("POST", "php/maj_contenu.php", true);
	    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	    xhttp.send("id="+contenu.id+"&degre="+contenu.degre);
	};

	$scope.maj_contenu_description=function(contenu){
		var answer;
		var xhttp = new XMLHttpRequest();
	    xhttp.onreadystatechange = function() {
	        if (this.readyState == 4 && this.status == 200) {

	            answer = angular.fromJson(this.responseText);
	            if(answer=="ok")
	            {
	            	$scope.message.statu="ok";
					$scope.message.texte="Le degré de la boisson a été mis à jour";

	            	$scope.$apply();
	            }

	        }
	    };
	    xhttp.open("POST", "php/maj_contenu.php", true);
	    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	    xhttp.send("id="+contenu.id+"&description="+contenu.description);
	};



	$scope.maj_contenu_contenant=function(contenu,id_contenant){
		var answer;
		var xhttp = new XMLHttpRequest();
	    xhttp.onreadystatechange = function() {
	        if (this.readyState == 4 && this.status == 200) {

	            answer = angular.fromJson(this.responseText);
	            if(answer=="ok")
	            {

	            	if (id_contenant>0) {var add=true;}else{var add=false; id_contenant=-id_contenant; }

	            	for(var i= 0; i < $scope.contenus_list.length; i++)
					{
						if ($scope.contenus_list[i]==contenu) {
							var index_contenu= i;
							i+=$scope.contenus_list.length;
						}
					}

					for(var i= 0; i < $scope.contenants_list.length; i++)
					{
						if ($scope.contenants_list[i].id==id_contenant) {
							var index_contenant= i;
							i+=$scope.contenants_list.length;
						}
					}

					var exist_in_contenu=false;
					for(var i= 0; i < contenu.contenants.length; i++)
					{
						if (contenu.contenants[i].id==id_contenant) {
							var index_contenant2= i;
							i+=contenu.contenants.length;
							exist_in_contenu=true;
						}
					}


	            	if (add && !exist_in_contenu) {
	            		$scope.contenants_list[index_contenant].consigne=0;
	            		$scope.contenus_list[index_contenu].contenants.push($scope.contenants_list[index_contenant]);
	            	}

	            	if (!add && exist_in_contenu)
	            	{
	            		$scope.contenus_list[index_contenu].contenants.splice(index_contenant2,1);
	            	}


	            	$scope.$apply();
	            }

	        }
	    };
	    xhttp.open("POST", "php/maj_contenu.php", true);
	    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	    xhttp.send("id="+contenu.id+"&contenant="+id_contenant);
	};

	$scope.desactiv_perm=function(perm){
		if (confirm("Voulez vous vraiment désactiver cette perm ? \n Ceci est irréversible")){
            var answer;
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {

                    answer = angular.fromJson(this.responseText);
                    if(answer=="ok")
                    {
                        for(var i= 0; i < $scope.nom_perm_list.length; i++)
                        {
                            if ($scope.nom_perm_list[i]==perm) {
                                $scope.nom_perm_list.splice(i,1);
                            }
                        }

                        $scope.$apply();
                    }
                }
            };
            xhttp.open("POST", "php/desactiv_perm.php", true);
            xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhttp.send("id="+perm.id);
		}
	};

	$scope.maj_membre_perm=function(perm,id_membre){
		var answer;
		var xhttp = new XMLHttpRequest();
	    xhttp.onreadystatechange = function() {
	        if (this.readyState == 4 && this.status == 200) {

	            answer = angular.fromJson(this.responseText);
	            if(answer=="ok")
	            {

	            	if (id_membre>0) {var add=true;}else{var add=false; id_membre=-id_membre; }

	            	for(var i= 0; i < $scope.nom_perm_list.length; i++)
					{
						if ($scope.nom_perm_list[i]==perm) {
							var index_perm= i;
							i+=$scope.nom_perm_list.length;
						}
					}

					for(var i= 0; i < $scope.users_list.length; i++)
					{
						if ($scope.users_list[i].id==id_membre) {
							var index_membre= i;
							i+=$scope.users_list.length;
						}
					}

					var exist_in_perm=false;
					for(var i= 0; i < perm.membres.length; i++)
					{
						if (perm.membres[i].id==id_membre) {
							var index_membre2= i;
							i+=perm.membres.length;
							exist_in_perm=true;
						}
					}


	            	if (add && !exist_in_perm) {

	            		$scope.nom_perm_list[index_perm].membres.push($scope.users_list[index_membre]);
	            	}

	            	if (!add && exist_in_perm)
	            	{
	            		$scope.nom_perm_list[index_perm].membres.splice(index_membre2,1);
	            	}


	            	$scope.$apply();
	            }

	        }
	    };
	    xhttp.open("POST", "php/maj_nom_perm.php", true);
	    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	    xhttp.send("id="+perm.id+"&id_membre="+id_membre);
	};

	$scope.maj_contenu_contenant_consigne=function(contenu,contenant){
		var answer;
		var xhttp = new XMLHttpRequest();
	    xhttp.onreadystatechange = function() {
	        if (this.readyState == 4 && this.status == 200) {

	            answer = angular.fromJson(this.responseText);
	            if(answer=="ok")
	            {
	            	$scope.message.statu="ok";
					$scope.message.texte="La consigne a été mise à jour";

	            	$scope.$apply();
	            }

	        }
	    };
	    xhttp.open("POST", "php/maj_contenu.php", true);
	    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	    xhttp.send("id="+contenu.id+"&id_contenant="+contenant.id+"&consigne="+contenant.consigne);
	};


	$scope.maj_user_droit=function(user){
		var answer;
		var xhttp = new XMLHttpRequest();
	    xhttp.onreadystatechange = function() {
	        if (this.readyState == 4 && this.status == 200) {

	            answer = angular.fromJson(this.responseText);
	            if(answer=="ok")
	            {
	            	$scope.message.statu="ok";
					$scope.message.texte="Droit mis à jour";

	            	$scope.$apply();
	            }

	        }
	    };
	    xhttp.open("POST", "php/maj_user_droit.php", true);
	    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	    xhttp.send("id="+user.id+"&droit="+user.droit);
	};



	$scope.new_contenu=function(nom){
		var answer;
		var xhttp = new XMLHttpRequest();
	    xhttp.onreadystatechange = function() {
	        if (this.readyState == 4 && this.status == 200) {

	            answer = angular.fromJson(this.responseText);
	            if(answer.ok)
	            {
	            	$scope.boisson.contenu=answer.contenu;
	            	$scope.contenus_list.push(answer.contenu);
	            	$scope.$apply();
	            }else{
	            	$scope.message.statu="error";
					$scope.message.texte="Cette boisson existait déjà";
					$scope.$apply();
	            }

	        }
	    };
	    xhttp.open("POST", "php/new_contenu.php", true);
	    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	    xhttp.send("nom="+nom);
	};

	$scope.new_contenant=function(contenant){
		if (typeof contenant.capacite === 'undefined') {
	     	alert("La capacite doit être entrée avec une virgule, pas un point.")
	    }else{
	    	var answer;
			var xhttp = new XMLHttpRequest();
		    xhttp.onreadystatechange = function() {
		        if (this.readyState == 4 && this.status == 200) {

		            answer = angular.fromJson(this.responseText);
		            if(answer.ok)
		            {

		            	$scope.contenants_list.push(answer.contenant);
		            	$scope.$apply();
		            }else{
		            	$scope.message.statu="error";
						$scope.message.texte="Ce contenant existait déjà";
						$scope.$apply();
		            }

		        }
		    };
		    xhttp.open("POST", "php/new_contenant.php", true);
		    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		    xhttp.send("nom="+contenant.nom+"&capacite="+contenant.capacite+"&type="+contenant.type);
		}
	};

	$scope.suppr_vers_contenant=function(old_id,new_id,contenu){
		if (old_id==new_id) {
	     	alert("Attention le nouveau contenant ne doit pas être l'ancien");
	    }else{
	    	var answer;
			var xhttp = new XMLHttpRequest();
		    xhttp.onreadystatechange = function() {
		        if (this.readyState == 4 && this.status == 200) {

		            answer = angular.fromJson(this.responseText);
		            if(answer=='ok')
		            {



		            	for(var i= 0; i < $scope.contenus_list.length; i++)
						{
							if ($scope.contenus_list[i]==contenu) {
								var index_contenu= i;
								i+=$scope.contenus_list.length;
							}
						}

						for(var i= 0; i < $scope.contenants_list.length; i++)
						{
							if ($scope.contenants_list[i].id==new_id) {
								var index_new_id= i;
								i+=$scope.contenants_list.length;
							}
						}


						for(var i= 0; i < contenu.contenants.length; i++)
						{
							if (contenu.contenants[i].id==old_id) {
								var index_old_id= i;
								i+=contenu.contenants.length;
							}
						}

						var exist_in_contenu=false;
						for(var i= 0; i < contenu.contenants.length; i++)
						{
							if (contenu.contenants[i].id==new_id) {
								i+=contenu.contenants.length;
								exist_in_contenu=true;
							}
						}


		            	if (!exist_in_contenu) {
		            		$scope.contenants_list[index_new_id].consigne=0;
		            		$scope.contenus_list[index_contenu].contenants.push($scope.contenants_list[index_new_id]);
		            	}

		            	$scope.contenus_list[index_contenu].contenants.splice(index_old_id,1);


		            	$scope.$apply();
		            }

		        }
		    };
		    xhttp.open("POST", "php/suppr_vers_contenant.php", true);
		    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		    xhttp.send("old_id="+old_id+"&new_id="+new_id+"&id_contenu="+contenu.id);
		}
	};


	$scope.contenant_type=function(type){
		if (type=="fut") { return "Fût";}
		if (type=="bouteille_unique") { return "Bouteille vendue entière";}
		if (type=="bouteille_partage") { return "Bouteille servie en eco cup";}
		if (type=="cubi") { return "Cubi";}
		if (type=="verre") { return "Eco Cup";}
	}

	$scope.color=function(index)
    {

		if (index%2==0) {
			return "color_1";
		}else{
			return "color_2";
		}

    }


});
