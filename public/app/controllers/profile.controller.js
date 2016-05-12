(function() {
	'use strict';

	angular
			.module('app')
			.controller('ProfileCtrl', ProfileCtrl);

	ProfileCtrl.$inject = ['$state','$cookieStore','MemberServices'];

	function ProfileCtrl($state,$cookieStore,MemberServices) {
		var vm = this;

		// Func Init
		vm.logout = logout;
		
		// Run
		isLogin();

		function isLogin() {
			vm.user = $cookieStore.get('user');
			if(!vm.user) {
				$state.go('member');
			}
		}

		function logout() {
			$cookieStore.remove('user');
			$state.go('member');
		}



	}
})();