(function() {
	'use strict';

	angular
			.module('app')
			.controller('orderedHistoryCtrl', orderedHistoryCtrl);

	orderedHistoryCtrl.$inject = ['$state','$cookieStore','$scope','$stateParams','OrderServices'];

	function orderedHistoryCtrl($state,$cookieStore,$scope,$stateParams,OrderServices) {

		isLogin();
		console.log($stateParams.id);

		function isLogin() {
			$scope.user = $cookieStore.get('user');
			if(!$scope.user) {
				$state.go('member');
			}
		}

		function getOrder(order) {
			OrderServices.getOrder(order)
				.success(function (data) {
					console.log(data);
				}).error(function (error) {
					console.log(error);
				})
		}
		
	}
})();