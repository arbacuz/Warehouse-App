(function() {
	'use strict';
	
	angular
			.module('app')
			.factory('MemberServices', MemberServices);

	MemberServices.$inject = ['$q', '$http', '$rootScope'];
	
	function MemberServices($q, $http, $rootScope) {
		var urlBase = "http://localhost:8888/warehouse-proj/Warehouse-App/public/";
		var memberServices = {
			login: login
		};
		return memberServices;

		function login(user) {
			var data = angular.toJson(user);
			// console.log(data);
			return $http.post(urlBase+'api/member/login.php',data);
		}
	}

})();