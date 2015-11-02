(function() {
	'use strict';
	
	angular
			.module('app')
			.factory('MemberServices', MemberServices);

	MemberServices.$inject = ['$q', '$http', '$rootScope'];
	
	function MemberServices($q, $http, $rootScope) {
		var urlBase = "http://localhost:8888/warehouse-proj/Warehouse-App/public/";
		var memberServices = {
			base64: 			base64,
			registerUser: 		registerUser,
			login: 				login,
			logout: 			logout,
			addCredetial: 		addCredetial,
			removeCredetial: 	removeCredetial,
			getUserByUsername: 	getUserByUsername,
			getUsersAll: 		getUsersAll,
			updateUser: 		updateUser,
			deleteUser: 		deleteUser,
			addPosition: 		addPosition,
			updatePosition: 	updatePosition,
			deletePosition: 	deletePosition,
			getPositionsAll: 	getPositionsAll
		};
		return memberServices;

		var base64 = {
			encode: encode,
			decode: decode
		};

		function encode(username,password) {
			console.log("base64.decode");
		}

		function decode(username,token) {
			console.log("base64.decode");
		}

		function registerUser(user) {
			console.log("registerUser");
		}

		function login(user) {
			var data = angular.toJson(user);
			// console.log(data);
			return $http.post(urlBase+'api/member/login.php',data);
		}

		function logout() {
			console.log("logout");
		}

		function addCredetial() {
			console.log("addCredetial");
		}

		function removeCredetial(username) {
			console.log("removeCredetial");
		}

		function getUserByUsername(username) {
			console.log("getUserByUserName");
		}

		function getUsersAll() {
			console.log("getUserAll");
		}

		function updateUser(username) {

		}
		function deleteUser(username) {
			console.log("deleteUserByUserName");
		}

		function addPosition(position) {

		}

		function updatePosition(position) {

		}

		function deletePosition(position) {

		}

		function getPositionsAll() {

		}
	}

})();