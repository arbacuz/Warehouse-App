(function() {
	'use strict';

	angular
			.module('app')
			.controller('itemInfoCtrl', itemInfoCtrl);

	itemInfoCtrl.$inject = [
							'$state',
							'$cookieStore',
							'$scope',
							'$stateParams'
							];

	function itemInfoCtrl (
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