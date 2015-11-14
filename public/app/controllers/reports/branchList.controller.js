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
							'SweetAlert',
							'BranchServices'
							];

	function branchListCtrl(
							$state,
							$cookieStore,
							$scope,
							$stateParams,
							SweetAlert,
							BranchServices
							) {

		isLogin();
		$scope.branches = [];
		$scope.addBranch = addBranch;
		$scope.updateBranch = updateBranch;

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
			SweetAlert.swal({
			    title: "Are you sure?",
			    text: "You will not be able to recover this branch",
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
			      	SweetAlert.swal("Updated!", "Branch has been updated successfully", "success");
			    } else {
			    	$scope.loading = false;
			    	getBranchAll();
			      	SweetAlert.swal("Cancelled", "Branch does not update yet", "error");
			    }
			  }); 
		}
		
		
	}
})();