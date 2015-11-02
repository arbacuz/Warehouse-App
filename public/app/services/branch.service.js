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
			getBranch: 		getBranch,
			getCapacity: 	getCapacity
		};
		return branchServices;

		function addBranch(branch) {

		}

		function updateBranch(branch) {

		}

		function deleteBranch(branch) {

		}

		function getBranchAll() {

		}

		function getBranch(branch) {
			/* branchName */
		}

		function getCapacity(branch) {
			/* branchName */
		}

	}

})();