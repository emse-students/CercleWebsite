var module = angular.module('app.directives', []);

/**
 * Autocomplétion lors de la recherche d'une carte par son nom.
 */
module.directive('cardAutocomplete', ['$http', function($http) {
	return {
		restrict: 'A',
        link: function(scope, elem, attr) {
			
			function filterCard(array, term) {
				var matcher = new RegExp('(' + $.ui.autocomplete.escapeRegex(term) + ')', 'gi');
				return $.grep(array, function (item) {
					return matcher.test(item.name);
				});
			}
			
			function highlightCard(text, term) {
				var matcher = new RegExp('(' + $.ui.autocomplete.escapeRegex(term) + ')', 'gi');
            	return text.replace(matcher, '<strong>$1</strong>');
			}
			
        	// elem is a jquery lite object if jquery is not present, but with jquery and jquery ui, it will be a full jquery object.
            elem.autocomplete({
    			source: function(request, response) {
    			    // exemple appel rest pour filtrer les résultats
    				/*$http.get("/magicsupremacy/rest/api/1/card/search?name=" + this.term).success(function(data) {
    					response(data.cardslist);
    		        });*/
					
    				response(filterCard(scope.cardslist, this.term));
                },
                focus: function(event, ui) {
                    // on ne fait rien au survol de la souris sur les choix de la liste proposée
                	return false;
                },
                select: function(event, ui) {
                    // lors de la sélection d'un choix dans la liste, on affiche le libellé de la carte et on déclenche la recherche
                	scope.card = ui.item.label;
                    scope.$apply();
                    return false;
                },
                appendTo : attr.appendTo
                
            }).data("ui-autocomplete")._renderItem = function(ul, item) {
                // set du label pour récupération dans la méthode select
            	item.label = item.name;
                // nom de carte highlighted
				var cardNameHighlighted = highlightCard(item.name, this.term);
            	
                // construction de l'affichage d'une ligne
                var cardLine = $("<div>").html(cardNameHighlighted);
                // sortie pour jquery-ui
                return $("<li>").append("<a>" + $("<div>").append(cardLine).html() + "</a>").appendTo(ul);
            };
        }
    }
}]);