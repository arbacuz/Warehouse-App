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
				controller: 'MemberCtrl',
				controllerAs: 'vm'
			})
			.state('profile', {
				url: '/me',
				templateUrl: './views/profile.html',
				controller: 'ProfileCtrl',
				controllerAs: 'vm'
			})
			.state('staffInfo', {
				url: '/report/staff/info',
				templateUrl: './views/report/staff.html',
				controller: 'staffListCtrl',
				controllerAs: 'vm'
			})

			/***************************
			***
			*** Item Page
			*** 
			****************************/
			.state('item', {
				url: '/report/item',
				templateUrl: './views/report/item.html'
			})
			.state('itemRegistration', {
				url: '/form/item/registration',
				templateUrl: './views/form/itemregistration.html',
				controller: 'ItemRegistrationCtrl',
				controllerAs: 'vm'
			})
			.state('stockAdjustment', {
				url: '/form/item/adjust',
				templateUrl: './views/form/stockadjustment.html',
				controller: 'stockAdjustmentCtrl',
				controllerAs: 'vm'
			})
			.state('itemRegHistory', {
				url: '/report/item/regHistory',
				templateUrl: './views/report/itemRegistrationHistory.html',
				controller: 'itemRegHistoryCtrl',
				controllerAs: 'vm'
			})
			.state('itemInfo', {
				url: '/report/item/info/:id',
				templateUrl: './views/report/itemInfo.html',
				controller: 'itemInfoCtrl',
				controllerAs: 'vm'
			})
			.state('itemRemain', {
				url: '/report/item/remain',
				templateUrl: './views/report/itemRemaining.html',
				controller: 'itemRemainCtrl',
				controllerAs: 'vm'
			})
			.state('itemRefillAlert', {
				url: '/report/item/refillAlert',
				templateUrl: './views/report/itemRefillAlert.html',
				controller: 'itemRefillCtrl',
				controllerAs: 'vm'
			})

			/**************************
			***
			*** Order Page
			*** 
			***************************/
			.state('orderedItems', {
				url: '/form/order/ordering',
				templateUrl: './views/form/ordereditems.html',
				controller: 'OrderedItemsCtrl',
				controllerAs: 'vm'
			})
			.state('requestedItems', {
				url: '/form/order/requesting',
				templateUrl: './views/form/requesteditems.html',
				controller: 'RequestedItemsCtrl',
				controllerAs: 'vm'
			})
			.state('stockcard', {
				url: '/report/order/stockcard',
				templateUrl: './views/report/stockcard.html',
				controller: 'stockCardCtrl',
				controllerAs: 'vm'
			})
			.state('invoice', {
				url: '/report/order/invoice',
				templateUrl: './views/report/invoice.html',
				controller: 'invoiceHistoryCtrl',
				controllerAs: 'vm'
			})
			.state('orderHistory', {
				url: '/report/order/history',
				templateUrl: './views/report/order.html'
			})
			.state('orderHistoryByTime', {
				url: '/report/order/history/time',
				templateUrl: './views/report/orderHistoryByTime.html',
				controller: 'orderedHistoryTimeCtrl',
				controllerAs: 'vm'
			})
			.state('orderHistoryByItem', {
				url: '/report/order/history/item',
				templateUrl: './views/report/orderHistoryByItem.html',
				controller: 'orderedHistoryItemCtrl',
				controllerAs: 'vm'
			})
			.state('orderHistoryByStatus', {
				url: '/report/ordere/istory/status',
				templateUrl: './views/report/orderHistoryByStatus.html',
				controller: 'orderedHistoryStatusCtrl',
				controllerAs: 'vm'
			})
			.state('orderInfo', {
				url: '/report/order/info/:id',
				templateUrl: './views/report/orderInfo.html',
				controller: 'orderedInfoCtrl',
				controllerAs: 'vm'
			})

			/***************************
			***
			*** Branch Page
			*** 
			****************************/
			.state('capacity', {
				url: '/report/branch/capacity',
				templateUrl: './views/report/capacity.html',
				controller: 'capacityCtrl',
				controllerAs: 'vm'
			})


			/***************************
			***
			*** ADMIN Page
			*** 
			****************************/
			// add position
			.state('admin', {
				url: '/admin',
				templateUrl: './views/admin/admin.html'
			})
			.state('companyAdmin', {
				url: '/admin/company',
				templateUrl: './views/admin/company.html',
				controller: 'companyListCtrl',
				controllerAs: 'vm'
			})
			.state('itemAdmin', {
				url: '/admin/item',
				templateUrl: './views/admin/item.html',
				controller: 'itemListCtrl',
				controllerAs: 'vm'
			})
			.state('staffAdmin', {
				url: '/admin/staff',
				templateUrl: './views/admin/staff.html',
				controller: 'staffListCtrl',
				controllerAs: 'vm'
			})
			.state('branchAdmin', {
				url: '/admin/branch',
				templateUrl: './views/admin/branch.html',
				controller: 'branchListCtrl',
				controllerAs: 'vm'
			})
			.state('positionAdmin', {
				url: '/admin/position',
				templateUrl: './views/admin/position.html',
				controller: 'positionListCtrl',
				controllerAs: 'vm'
			})
		}

})();