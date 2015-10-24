(function() {
	'use strict';
	
	angular
			.module('app')
			.config(config);

	config.$inject = ['$stateProvider', '$urlRouterProvider'];

	function config($stateProvider, $urlRouterProvider) {
		$urlRouterProvider.otherwise('/');

		$stateProvider
			.state('home', {
				url: '/',
				templateUrl: './views/styleguide.html'
			})
			.state('itemRegistration', {
				url: '/item/registration',
				templateUrl: './views/form/itemregistration.html',
				controller: 'ItemRegistrationCtrl'
			})
			.state('stockAdjustment', {
				url: '/item/adjust',
				templateUrl: './views/form/stockadjustment.html'
			})
			.state('orderedItems', {
				url: '/item/order',
				templateUrl: './views/form/ordereditems.html',
				controller: 'OrderedItemsCtrl'
			})
			.state('requestedItems', {
				url: '/item/request',
				templateUrl: './views/form/requesteditems.html',
				controller: 'RequestedItemsCtrl'
			})
			.state('stockcard', {
				url: '/report/stockcard',
				templateUrl: './views/report/stockcard.html'
			})
			.state('invoice', {
				url: '/report/invoice',
				templateUrl: './views/report/invoice.html'
			})
			.state('itemHistory', {
				url: '/report/itemReg',
				templateUrl: './views/report/itemRegistrationHistory.html'
			})
			.state('itemInfo', {
				url: '/report/itemInfo',
				templateUrl: './views/report/itemInfo.html'
			})
			.state('itemRemain', {
				url: '/report/itemRemain',
				templateUrl: './views/report/itemRemaining.html'
			})
	}

})();