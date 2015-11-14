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
							'SweetAlert',
							'MemberServices',
							'BranchServices'
							];

	function staffListCtrl(
							$state,
							$cookieStore,
							$scope,
							$stateParams,
							SweetAlert,
							MemberServices,
							BranchServices
							) {

		isLogin();
		$scope.staffs = [];
		$scope.positions = [];
		$scope.branches = [];
		$scope.addUser = addUser;
		$scope.updateUser = updateUser;
		
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
			SweetAlert.swal({
			    title: "Are you sure?",
			    text: "You will not be able to recover this user",
			    type: "warning",
			    showCancelButton: true,
			    confirmButtonColor: "#DD6B55",
			    confirmButtonText: "Yes, update it!",
			    closeOnConfirm: false,
			    cancelButtonText: "No, cancel please!",
				closeOnCancel: false
			  },
			  function(isConfirm){
			  	if (isConfirm) {
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
			      	SweetAlert.swal("Updated!", "User has been updated successfully", "success");
			    } else {
			    	$scope.loading = false;
			    	getUsersAll();
			      	SweetAlert.swal("Cancelled", "User does not update yet", "error");
			    }
			  }); 
		}

		
		
	}
})();