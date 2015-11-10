(function() {
	'use strict';

	angular
			.module('app')
			.controller('OrderedItemsCtrl', OrderedItemsCtrl);

	OrderedItemsCtrl.$inject = ['$state','$cookieStore','$scope','CompanyServices'];

	function OrderedItemsCtrl($state,$cookieStore,$scope,CompanyServices) {

		$scope.today = new Date().getTime();
		getCompanyByTypeID(1);
		isLogin();

		function isLogin() {
			$scope.user = $cookieStore.get('user');
			if(!$scope.user) {
				$state.go('member');
			}
		}		

		$scope.deleteItem = deleteItem;
		$scope.items = [
				{
					name: 'TV Sony 42" Model: 5G112TG',
					type: 'Product',
					cost: '18,500',
					quantity: '3,000'
				},
				{
					name: 'Microwave SR992',
					type: 'Product',
					cost: '2,700',
					quantity: '40,000'
				}
		];

		function deleteItem(idx) {
	    	$scope.items.splice(idx, 1);
	  	}

	  	function getCompanyByTypeID(type) {
			CompanyServices.getCompanyByTypeID(type)
				.success(function(data) {
					if(data.status == "success") {
						$scope.companies = data.data;
					} else {
						console.log(data.status + data.messages);
					}
				}).error(function(error) {
					console.log(error);
				});
		}
	}
})();