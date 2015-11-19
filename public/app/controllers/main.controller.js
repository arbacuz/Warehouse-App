(function() {
	'use strict';

	angular
			.module('app')
			.controller('mainCtrl', mainCtrl);

	mainCtrl.$inject = ['$cookieStore'];

	function mainCtrl ($cookieStore) {
		var vm = this;
		
		// Var Init
		vm.user = $cookieStore.get('user');
	}
})();