(function() {
	'use strict';

	angular
			.module('app')
			.controller('itemRemainCtrl', itemRemainCtrl);

	itemRemainCtrl.$inject = [
							'$state',
							'$cookieStore',
							'$scope',
							'$stateParams'
							];

	function itemRemainCtrl(
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