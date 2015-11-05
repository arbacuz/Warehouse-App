(function() {
	'use strict';

	angular
			.module('app')
			.controller('mainCtrl', mainCtrl);

	mainCtrl.$inject = [
							'$state',
							'$cookieStore',
							'$scope',
							'$stateParams'
							];

	function mainCtrl (
							$state,
							$cookieStore,
							$scope,
							$stateParams
							) {

		$scope.user = $cookieStore.get('user');

	}
})();