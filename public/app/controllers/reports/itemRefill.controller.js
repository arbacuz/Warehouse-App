(function() {
	'use strict';

	angular
			.module('app')
			.controller('itemRefillCtrl', itemRefillCtrl);

	itemRefillCtrl.$inject = [
							'$state',
							'$cookieStore',
							'$scope',
							'$stateParams',
							'ItemServices'
							];

	function itemRefillCtrl(
							$state,
							$cookieStore,
							$scope,
							$stateParams,
							ItemServices
							) {

		isLogin();
		$scope.items = [];

		function isLogin() {
			$scope.user = $cookieStore.get('user');
			if(!$scope.user) {
				$state.go('member');
			} else {
				getItemsByQuantity($scope.user.relationships.branch,50);
			}
		}

		function getItemsByQuantity(branch,quantity) {
			ItemServices.getItemsByQuantity(branch,quantity)
				.success(function(data) {
					console.log(data);
					if(data.status == "success") {
						$scope.items = data.data;
					}
				}).error(function(error) {
					console.log(error);
				})
		}
		
	}
})();