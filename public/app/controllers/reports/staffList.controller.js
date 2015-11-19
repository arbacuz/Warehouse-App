(function() {
	'use strict';

	angular
			.module('app')
			.controller('staffListCtrl', staffListCtrl);

	staffListCtrl.$inject = ['$state', '$cookieStore', '$stateParams', 'SweetAlert', 'MemberServices', 'BranchServices'];

	function staffListCtrl($state, $cookieStore, $stateParams, SweetAlert, MemberServices, BranchServices) {
		var vm = this;

		// Var Init
		vm.staffs = [];
		vm.positions = [];
		vm.branches = [];

		// Func Init
		vm.addUser = addUser;
		vm.updateUser = updateUser;

		// Run
		isLogin();
		getUsersAll();
		getPositionsAll();
		getBranchAll();

		function isLogin() {
			vm.user = $cookieStore.get('user');
			if(!vm.user) {
				$state.go('member');
			} else {
				// getItemsByBranch(vm.user.relationships.branch);
			}
		}
		
		function getUsersAll() {
			MemberServices.getUsersAll()
				.success(function(data) {
					// console.log(data);
					vm.staffs = data.data;
				}).error(function(error) {
					console.log(error);
				});
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

		function getPositionsAll() {
			MemberServices.getPositionsAll()
				.success(function(data) {
					vm.positions = data.data;
				}).error(function(error){
					console.log(error);
				})
		}

		function addUser(staff) {
			vm.loading = true;
			MemberServices.addUser(staff)
				.success(function(data) {
					if(data.status == "success") {
						getUsersAll();
					}
					vm.loading = false;
				}).error(function(error) {
					console.log(error);
					vm.loading = false;
				})
			vm.newStaff = ""
		}

		function updateUser(staff) {
			vm.loading = true;
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
							vm.loading = false;
						}).error(function(error) {
							console.log(error);
							vm.loading = false;
						})
			      	SweetAlert.swal("Updated!", "User has been updated successfully", "success");
			    } else {
			    	vm.loading = false;
			    	getUsersAll();
			      	SweetAlert.swal("Cancelled", "User does not update yet", "error");
			    }
			  }); 
		}

		
		
	}
})();