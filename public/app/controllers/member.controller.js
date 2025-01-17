(function() {
	'use strict';

	angular
			.module('app')
			.controller('MemberCtrl', MemberCtrl);

	MemberCtrl.$inject = ['$state','$cookieStore','MemberServices','$rootScope'];

	function MemberCtrl($state,$cookieStore,MemberServices,$rootScope) {
		var vm = this;

		// Func Init
		vm.login = login;

		function login(user) {
			MemberServices.login(user)
				.success(function(data) {
					if(data.status == "success") {
						var user = data.data;
						$cookieStore.put('user',user);
						$rootScope.user = user;
						// location.reload();
						$state.go('home', {}, {reload: true});
					} else {
						console.log(data);
					}
				}).error(function(error) {
					console.log(error);
				})
		}

	}
})();