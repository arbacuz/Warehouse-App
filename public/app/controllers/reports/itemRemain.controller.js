(function() {
	'use strict';

	angular
			.module('app')
			.controller('itemRemainCtrl', itemRemainCtrl);

	itemRemainCtrl.$inject = [
							'$state',
							'$cookieStore',
							'$scope',
							'$stateParams',
							'ItemServices'
							];

	function itemRemainCtrl(
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
				getItemsByBranch($scope.user.relationships.branch);
			}
		}
		
		function getItemsByBranch(branch) {
			ItemServices.getItemsByBranch(branch)
				.success(function(data) {
					// console.log(data);
					$scope.items = data.data;
				}).error(function(error) {
					console.log(error);
				})
		}
	}
})();