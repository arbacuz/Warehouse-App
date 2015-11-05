(function() {
	'use strict';

	angular
			.module('app')
			.controller('ProfileCtrl', ProfileCtrl);

	ProfileCtrl.$inject = ['$state','$scope','$cookieStore','MemberServices'];

	function ProfileCtrl($state,$scope,$cookieStore,MemberServices) {

		$scope.logout = logout;
		
		isLogin();

		function isLogin() {
			$scope.user = $cookieStore.get('user');
			if(!$scope.user) {
				$state.go('member');
			}
		}

		function logout() {
			$cookieStore.remove('user');
			$state.go('member');
		}



	}
})();