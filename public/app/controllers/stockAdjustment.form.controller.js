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
		isLogin();

		function isLogin() {
			$scope.user = $cookieStore.get('user');
			if(!$scope.user) {
				$state.go('member');
			}
		}		
	}
})();