(function() {
	'use strict';
	
	angular
			.module('app')
			.factory('BranchServices', BranchServices);

	BranchServices.$inject = ['$q', '$http', '$rootScope'];
	
	function BranchServices($q, $http, $rootScope) {
		var urlBase = "http://localhost:8888/warehouse-proj/Warehouse-App/public/";
		var branchServices = {
			addBranch: 		addBranch,
			updateBranch: 	updateBranch,
			deleteBranch: 	deleteBranch,
			getBranchAll: 	getBranchAll,
			getBranch: 		getBranch
		};
		return branchServices;

		function addBranch(branch) {
			var data = angular.toJson(branch);
			return $http.post(urlBase+'api/branch/addBranch.php',data);
		}

		function updateBranch(branch) {
			var data = angular.toJson(branch);
			return $http.post(urlBase+'api/branch/updateBranch.php',data);
		}

		function deleteBranch(branch) {
			var data = angular.toJson(branch);
			return $http.post(urlBase+'api/branch/deleteBranch.php',data);
		}

		function getBranchAll() {
			return $http.get(urlBase+'api/branch/getBranchAll.php');
		}

		function getBranch(branch) {
			var data = angular.toJson(branch);
			return $http.post(urlBase+'api/branch/getBranch.php',data);
		}

	}

})();