(function() {
	'use strict';

	angular
			.module('app')
			.controller('stockAdjustmentCtrl', stockAdjustmentCtrl);

	stockAdjustmentCtrl.$inject = [
									'$state',
									'$cookieStore',
									'$scope',
									'$stateParams',
									'ItemServices'
									];

	function stockAdjustmentCtrl(
								$state,
								$cookieStore,
								$scope,
								$stateParams,
								ItemServices
								) {
		
		$scope.today = new Date().getTime();
		$scope.items = [];
		isLogin();

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
					console.log(data);
					// $scope.items = data.data;
				}).error(function(error) {
					console.log(error);
				})
		}
	}
})();