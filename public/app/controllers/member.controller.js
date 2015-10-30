(function() {
	'use strict';

	angular
			.module('app')
			.controller('MemberCtrl', MemberCtrl);

	MemberCtrl.$inject = ['$state','$scope','MemberServices'];

	function MemberCtrl($state,$scope,MemberServices) {
		$scope.login = login;

		function login(user) {
			MemberServices.login(user)
				.success(function(data) {
					if(data.status == "success") {
						// $state.go('home');
						console.log(data);
						
					} else {
						console.log(data);
					}
				}).error(function(error) {
					console.log(error);
				})
		}
	}
})();