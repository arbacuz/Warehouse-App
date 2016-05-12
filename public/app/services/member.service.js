(function() {
	'use strict';
	
	angular
			.module('app')
			.factory('MemberServices', MemberServices);

	MemberServices.$inject = ['$q', '$http', '$rootScope'];
	
	function MemberServices($q, $http, $rootScope) {
		var urlBase = "http://localhost:8888/warehouse-proj/Warehouse-App/public/";
		var memberServices = {
			addUser: 			addUser,
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

		function addUser(user) {
			var data = angular.toJson({'user':user});
			return $http.post(urlBase+'api/member/addUser.php',data);
		}

		function login(user) {
			var data = angular.toJson({'user':user});
			return $http.post(urlBase+'api/member/login.php',data);
		}

		function logout() {
			console.log("logout");
		}

		function getUser(user) {
			var data = angular.toJson({'user':user});
			return $http.post(urlBase+'api/member/getUser.php',data);
		}

		function getUsersAll() {
			return $http.get(urlBase+'api/member/getUsersAll.php');
		}

		function updateUser(user) {
			var data = angular.toJson({'user':user});
			return $http.post(urlBase+'api/member/updateUser.php',data);
		}

		function deleteUser(user) {
			var data = angular.toJson({'user':user});
			return $http.post(urlBase+'api/member/deleteUser.php',data);
		}

		function addPosition(position) {
			var data = angular.toJson({'position':position});
			return $http.post(urlBase+'api/member/addPosition.php',data);
		}

		function updatePosition(position) {
			var data = angular.toJson({'position':position});
			return $http.post(urlBase+'api/member/updatePosition.php',data);
		}

		function deletePosition(position) {
			var data = angular.toJson({'position':position});
			return $http.post(urlBase+'api/member/deletePosition.php',data);
		}

		function getPositionsAll() {
			return $http.get(urlBase+'api/member/getPositionsAll.php');
		}
	}

})();