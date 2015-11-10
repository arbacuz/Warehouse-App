(function() {
	'use strict';
	
	angular
			.module('app')
			.config(config);

	config.$inject = ['$stateProvider', '$urlRouterProvider'];

	function config($stateProvider, $urlRouterProvider) {
		$urlRouterProvider.otherwise('/');

		$stateProvider
			/***************************
			***
			*** MAIN PAGE
			*** 
			****************************/
			.state('home', {
				url: '/',
				templateUrl: './views/styleguide.html'
			})

			/***************************
			***
			*** Member Page
			*** 
			****************************/
			.state('member', {
				url: '/member',
				templateUrl: './views/member.html',
				controller: 'MemberCtrl'
			})
			.state('profile', {
				url: '/me',
				templateUrl: './views/profile.html',
				controller: 'ProfileCtrl'
			})
			.state('staffAll', {
				url: '/report/staff/all',
				templateUrl: './views/member.html',
				controller: 'MemberCtrl'
			})
			.state('staffInfo', {
				url: '/report/staff/info',
				templateUrl: './views/member.html',
				controller: 'MemberCtrl'
			})

			/***************************
			***
			*** Item Page
			*** 
			****************************/
			.state('item', {
				url: '/report/item',
				templateUrl: './views/report/item.html'
				// controller: 'ItemRegistrationCtrl'
			})
			.state('itemRegistration', {
				url: '/form/item/registration',
				templateUrl: './views/form/itemregistration.html',
				controller: 'ItemRegistrationCtrl'
			})
			.state('stockAdjustment', {
				url: '/form/item/adjust',
				templateUrl: './views/form/stockadjustment.html',
				controller: 'stockAdjustmentCtrl'
			})
			.state('itemRegHistory', {
				url: '/report/item/regHistory',
				templateUrl: './views/report/itemRegistrationHistory.html',
				controller: 'itemRegHistoryCtrl'
			})
			.state('itemInfo', {
				url: '/report/item/info',
				templateUrl: './views/report/itemInfo.html',
				controller: 'itemInfoCtrl'
			})
			.state('itemRemain', {
				url: '/report/item/remain',
				templateUrl: './views/report/itemRemaining.html',
				controller: 'itemRemainCtrl'
			})
			.state('itemRefillAlert', {
				url: '/report/item/refillAlert',
				templateUrl: './views/report/itemRefillAlert.html',
				controller: 'itemRemainCtrl'
			})

			/**************************
			***
			*** Order Page
			*** 
			***************************/
			.state('order', {
				url: '/report/order',
				templateUrl: './views/report/order.html'
				// controller: 'ItemRegistrationCtrl'
			})
			.state('orderedItems', {
				url: '/form/order/ordering',
				templateUrl: './views/form/ordereditems.html',
				controller: 'OrderedItemsCtrl'
			})
			.state('requestedItems', {
				url: '/form/order/requesting',
				templateUrl: './views/form/requesteditems.html',
				controller: 'RequestedItemsCtrl'
			})
			.state('stockcard', {
				url: '/report/order/stockcard',
				templateUrl: './views/report/stockcard.html',
				controller: 'stockCardCtrl'
			})
			.state('invoice', {
				url: '/report/order/invoice',
				templateUrl: './views/report/invoice.html',
				controller: 'orderedHistoryCtrl'
			})

			.state('orderHistoryByTime', {
				url: '/report/order/history/time',
				templateUrl: './views/report/orderHistoryByTime.html',
				controller: 'orderedHistoryCtrl'
			})
			.state('orderHistoryByItem', {
				url: '/report/order/history/item',
				templateUrl: './views/report/orderHistoryByItem.html',
				controller: 'orderedHistoryCtrl'
			})
			.state('orderHistoryByStatus', {
				url: '/report/ordere/istory/status',
				templateUrl: './views/report/orderHistoryByStatus.html',
				controller: 'orderedHistoryCtrl'
			})
			.state('orderInfo', {
				url: '/report/order/info:id',
				templateUrl: './views/report/orderInfo.html',
				controller: 'orderedHistoryCtrl'
			})

			/***************************
			***
			*** Branch Page
			*** 
			****************************/
			.state('capacity', {
				url: '/report/branch/capacity',
				templateUrl: './views/report/capacity.html',
				controller: 'orderedHistoryCtrl'
			})


			/***************************
			***
			*** ADMIN Page
			*** 
			****************************/
			// add position
			// add branch
			.state('companyAdmin', {
				url: '/admin/company',
				templateUrl: './views/admin/company.html'
				// controller: 'orderedHistoryCtrl'
			}) // Crashes with select and views

			.state('itemAdmin', {
				url: '/admin/item',
				templateUrl: './views/admin/item.html'
				// controller: 'orderedHistoryCtrl'
			})	// no itemID

			.state('staffAdmin', {
				url: '/admin/staff',
				templateUrl: './views/admin/staff.html'
				// controller: 'orderedHistoryCtrl'
			})
		}

})();