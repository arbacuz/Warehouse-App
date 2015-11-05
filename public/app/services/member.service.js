(function() {
	'use strict';
	
	angular
			.module('app')
			.factory('MemberServices', MemberServices);

	MemberServices.$inject = ['$q', '$http', '$rootScope'];
	
	function MemberServices($q, $http, $rootScope) {
		var urlBase = "http://localhost:8888/warehouse-proj/Warehouse-App/public/";
		var memberServices = {
			registerUser: 		registerUser,
			login: 				login,
			logout: 			logout,
			getUser: 			getUser,
			getUsersAll: 		getUsersAll,
			updateUser: 		updateUser,
			deleteUser: 		deleteUser,
			addPosition: 		addPosition,
			updatePosition: 	updatePosition,
			deletePosition: 	deletePosition,
			getPositionsAll: 	getPositionsAll
		};
		return memberServices;

		function registerUser(user) {
			console.log("registerUser");
		}

		function login(user) {
			var data = angular.toJson(user);
			return $http.post(urlBase+'api/member/login.php',data);
		}

		function logout() {
			console.log("logout");
		}

		function getUser(user) {
			var data = angular.toJson(user);
			return $http.post(urlBase+'api/member/getUser.php',data);
		}

		function getUsersAll() {
			return $http.get(urlBase+'api/item/getUsersAll.php');
		}

		function updateUser(user) {
			var data = angular.toJson({'user':user});
			return $http.post(urlBase+'api/item/updateUser.php',data);
		}

		function deleteUser(user) {
			var data = angular.toJson({'user':user});
			return $http.post(urlBase+'api/item/deleteUser.php',data);
		}

		function addPosition(position) {
			var data = angular.toJson({'position':position});
			return $http.post(urlBase+'api/item/addPosition.php',data);
		}

		function updatePosition(position) {
			var data = angular.toJson({'position':position});
			return $http.post(urlBase+'api/item/updatePosition.php',data);
		}

		function deletePosition(position) {
			var data = angular.toJson({'position':position});
			return $http.post(urlBase+'api/item/deletePosition.php',data);
		}

		function getPositionsAll() {
			return $http.get(urlBase+'api/item/getPositionsAll.php');
		}
	}

})();