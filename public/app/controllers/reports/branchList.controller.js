(function() {
	'use strict';

	angular
			.module('app')
			.controller('branchListCtrl', branchListCtrl);

	branchListCtrl.$inject = ['$state', '$cookieStore', '$stateParams', 'SweetAlert', 'BranchServices'];

	function branchListCtrl($state, $cookieStore, $stateParams, SweetAlert, BranchServices) {
		var vm = this;

		// Var Init
		vm.branches = [];

		// Func Init
		vm.addBranch = addBranch;
		vm.updateBranch = updateBranch;

		// Run
		isLogin();
		getBranchAll();

		function isLogin() {
			vm.user = $cookieStore.get('user');
			if(!vm.user) {
				$state.go('member');
			}
		}
		
		function getBranchAll() {
			vm.loading = true;
			BranchServices.getBranchAll()
				.success(function(data) {
					vm.branches = data.data;
					vm.loading = false;;
				}).error(function(error) {
					console.log(error);
				});
		}

		function addBranch(branch) {
			vm.loading = true;
			BranchServices.addBranch(branch)
				.success(function(data) {
					if(data.status == "success") {
						getBranchAll();
					}
					vm.loading = false;
				}).error(function(error) {
					console.log(error);
					vm.loading = false;
				})
			vm.newBranch = ""
		}

		function updateBranch(branch) {
			vm.loading = true;
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
								SweetAlert.swal("Updated!", "Branch has been updated successfully", "success");
							} else {
								SweetAlert.swal("Error", data.messages, "error");
							}
							vm.loading = false;
						}).error(function(error) {
							console.log(error);
							vm.loading = false;
						})
			    } else {
			    	$scope.loading = false;
			    	getBranchAll();
			      	SweetAlert.swal("Cancelled", "Branch does not update yet", "error");
			    }
			  }); 
		}
		
		
	}
})();