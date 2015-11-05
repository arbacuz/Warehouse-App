(function() {
	'use strict';

	angular
			.module('app')
			.controller('orderedHistoryCtrl', orderedHistoryCtrl);

	orderedHistoryCtrl.$inject = ['$state','$cookieStore','$scope','$stateParams'];

	function orderedHistoryCtrl($state,$cookieStore,$scope,$stateParams) {

		isLogin();

		function isLogin() {
			$scope.user = $cookieStore.get('user');
			if(!$scope.user) {
				$state.go('member');
			}
		}
		
	}
})();