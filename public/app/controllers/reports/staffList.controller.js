(function() {
	'use strict';

	angular
			.module('app')
			.controller('staffListCtrl', staffListCtrl);

	staffListCtrl.$inject = [
							'$state',
							'$cookieStore',
							'$scope',
							'$stateParams',
							'MemberServices',
							'BranchServices'
							];

	function staffListCtrl(
							$state,
							$cookieStore,
							$scope,
							$stateParams,
							MemberServices,
							BranchServices
							) {

		isLogin();
		$scope.staffs = [];
		$scope.positions = [];
		$scope.branches = [];
		$scope.addUser = addUser;
		$scope.updateUser = updateUser;
		$scope.deleteUser = deleteUser;
		
		getUsersAll();
		getPositionsAll();
		getBranchAll();

		function isLogin() {
			$scope.user = $cookieStore.get('user');
			if(!$scope.user) {
				$state.go('member');
			} else {
				// getItemsByBranch($scope.user.relationships.branch);
			}
		}
		
		function getUsersAll() {
			MemberServices.getUsersAll()
				.success(function(data) {
					// console.log(data);
					$scope.staffs = data.data;
				}).error(function(error) {
					console.log(error);
				});
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

		function getPositionsAll() {
			MemberServices.getPositionsAll()
				.success(function(data) {
					$scope.positions = data.data;
				}).error(function(error){
					console.log(error);
				})
		}

		function addUser(staff) {
			$scope.loading = true;
			MemberServices.addUser(staff)
				.success(function(data) {
					if(data.status == "success") {
						getUsersAll();
					}
					$scope.loading = false;
				}).error(function(error) {
					console.log(error);
					$scope.loading = false;
				})
			$scope.newStaff = ""
		}

		function updateUser(staff) {
			$scope.loading = true;
			MemberServices.updateUser(staff)
				.success(function(data) {
					console.log(data);
					if(data.status == "success") {
						staff.update = false;
					}
					$scope.loading = false;
				}).error(function(error) {
					console.log(error);
					$scope.loading = false;
				})
		}

		function deleteUser(user) {
			$scope.loading = true;
			MemberServices.deleteUser(user)
				.success(function(data) {
					console.log(data);
					getUsersAll();
					$scope.loading = false;
				}).error(function(error) {
					console.log(error);
					$scope.loading = false;
				})
		}
		
		
	}
})();