(function() {
	'use strict';

	angular
			.module('app')
			.controller('itemRegHistoryCtrl', itemRegHistoryCtrl);

	itemRegHistoryCtrl.$inject = [
								'$state',
								'$cookieStore',
								'$scope',
								'$stateParams',
								'ItemServices'
								];

	function itemRegHistoryCtrl(
								$state,
								$cookieStore,
								$scope,
								$stateParams,
								ItemServices
								) {
		isLogin();
		$scope.items = [];
		$scope.getItemsByReg = getItemsByReg;
		function isLogin() {
			$scope.user = $cookieStore.get('user');
			if(!$scope.user) {
				$state.go('member');
			}
		}

		function getItemsByReg(registerCode,branch) {
			ItemServices.getItemsByReg(registerCode,branch)
				.success(function(data) {
					$scope.items = data.data;
					console.log(data);
					if(data.status=="success") {
						$scope.showTable = true;
					} else {
						$scope.showTable = false;
					}
				}).error(function(error) {
					console.log(error);
					$scope.showTable = false;
				})
		}
	}
})();