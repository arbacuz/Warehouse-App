(function() {
	'use strict';

	angular
			.module('app')
			.controller('branchListCtrl', branchListCtrl);

	branchListCtrl.$inject = [
							'$state',
							'$cookieStore',
							'$scope',
							'$stateParams',
							'BranchServices'
							];

	function branchListCtrl(
							$state,
							$cookieStore,
							$scope,
							$stateParams,
							BranchServices
							) {

		isLogin();
		$scope.branches = [];
		$scope.addBranch = addBranch;
		$scope.updateBranch = updateBranch;
		$scope.deleteBranch = deleteBranch;

		getBranchAll();

		function isLogin() {
			$scope.user = $cookieStore.get('user');
			if(!$scope.user) {
				$state.go('member');
			} else {
				// getItemsByBranch($scope.user.relationships.branch);
			}
		}
		
		function getBranchAll() {
			$scope.loading = true;
			BranchServices.getBranchAll()
				.success(function(data) {
					// console.log(data);
					$scope.branches = data.data;
					$scope.loading = false;;
				}).error(function(error) {
					console.log(error);
				});
		}

		function addBranch(branch) {
			$scope.loading = true;
			BranchServices.addBranch(branch)
				.success(function(data) {
					if(data.status == "success") {
						getBranchAll();
					}
					$scope.loading = false;
				}).error(function(error) {
					console.log(error);
					$scope.loading = false;
				})
			$scope.newBranch = ""
		}

		function updateBranch(branch) {
			$scope.loading = true;
			BranchServices.updateBranch(branch)
				.success(function(data) {
					console.log(data);
					if(data.status == "success") {
						branch.update = false;
					}
					$scope.loading = false;
				}).error(function(error) {
					console.log(error);
					$scope.loading = false;
				})
		}

		function deleteBranch(branch) {
			$scope.loading = true;
			BranchServices.deleteBranch(branch)
				.success(function(data) {
					console.log(data);
					getBranchAll();
					$scope.loading = false;
				}).error(function(error) {
					console.log(error);
					$scope.loading = false;
				})
		}
		
		
	}
})();