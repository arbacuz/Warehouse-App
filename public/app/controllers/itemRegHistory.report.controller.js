(function() {
	'use strict';

	angular
			.module('app')
			.controller('itemRegHistoryCtrl', itemRegHistoryCtrl);

	itemRegHistoryCtrl.$inject = [
								'$state',
								'$cookieStore',
								'$scope',
								'$stateParams'
								];

	function itemRegHistoryCtrl(
								$state,
								$cookieStore,
								$scope,
								$stateParams
								) {
		isLogin();

		function isLogin() {
			$scope.user = $cookieStore.get('user');
			if(!$scope.user) {
				$state.go('member');
			}
		}	
	}
})();