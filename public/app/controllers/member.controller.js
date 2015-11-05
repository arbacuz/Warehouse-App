(function() {
	'use strict';

	angular
			.module('app')
			.controller('MemberCtrl', MemberCtrl);

	MemberCtrl.$inject = ['$state','$scope','$cookieStore','MemberServices'];

	function MemberCtrl($state,$scope,$cookieStore,MemberServices) {
		$scope.login = login;

		console.log($cookieStore.get('user'));

		function login(user) {
			MemberServices.login(user)
				.success(function(data) {
					if(data.status == "success") {
						var user = data.data;
						$cookieStore.put('user',user);
						console.log($cookieStore.get('user'));
						$state.go('home');
					} else {
						console.log(data);
					}
				}).error(function(error) {
					console.log(error);
				})
		}

	}
})();