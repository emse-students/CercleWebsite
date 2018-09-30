var module = angular.module('app.controllers', ['ui.bootstrap']);

/**
 * Controlleur des cartes.
 */
module.controller('CardController', ['$scope',  function($scope) {
    
    // mock des cartes dans lesquelles rechercher
	$scope.cardslist = [
		{ 'name' : 'Skylasher' },
		{ 'name' : 'Thrashing Mossdog' },
		{ 'name' : 'Zhur-Taa Druid' },
		{ 'name' : 'Feral Animist' },
		{ 'name' : 'Rubblebelt Maaka' },
		{ 'name' : 'Mending Touch' },
		{ 'name' : 'Weapon Surge' },
		{ 'name' : 'Woodlot Crawler' },
		{ 'name' : 'Phytoburst' },
		{ 'name' : 'Smelt-Ward Gatekeepers' },
		{ 'name' : 'Debt to the Deathless' },
		{ 'name' : 'Woodlot Crawler' },
		{ 'name' : 'Blaze Commando' },
		{ 'name' : 'Uncovered Clues' }
	];
	
	// saisie du nom de la carte
	$scope.card = null;
    
}]);