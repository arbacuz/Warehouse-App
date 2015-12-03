(function() {
	'use strict';

	angular
			.module('app')
			.controller('mainCtrl', mainCtrl);

	mainCtrl.$inject = ['$cookieStore','$rootScope'];

	function mainCtrl ($cookieStore,$rootScope) {
		var vm = this;

		// Var Init
		
		$rootScope.$on('$stateChangeSuccess', function(event, to, toParams, from, fromParams) {
        	if(!$cookieStore.get('user')) {
				vm.user = $rootScope.user;
			} else {
				vm.user = $cookieStore.get('user');
			}
			console.log($cookieStore.get('user'));
        });

	}
})();