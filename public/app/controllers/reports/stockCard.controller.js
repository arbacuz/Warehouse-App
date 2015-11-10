(function() {
	'use strict';

	angular
			.module('app')
			.controller('stockCardCtrl', stockCardCtrl);

	stockCardCtrl.$inject = ['$state','$cookieStore','$scope','$stateParams'];

	function stockCardCtrl($state,$cookieStore,$scope,$stateParams) {
		
		isLogin();

		function isLogin() {
			$scope.user = $cookieStore.get('user');
			if(!$scope.user) {
				$state.go('member');
			}
		}	
	}
})();