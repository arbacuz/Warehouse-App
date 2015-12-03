(function() {
	'use strict';

	angular
			.module('app')
			.controller('positionListCtrl', positionListCtrl);

	positionListCtrl.$inject = ['$state', '$cookieStore', '$stateParams', 'SweetAlert', 'MemberServices'];

	function positionListCtrl($state, $cookieStore, $stateParams, SweetAlert, MemberServices) {
		var vm = this;

		// Var Init
		vm.positions = [];

		// Func Init
		vm.addPosition = addPosition;
		vm.updatePosition = updatePosition;
		vm.deletePosition = deletePosition;

		// Run
		isLogin();
		getPositionsAll();

		function isLogin() {
			vm.user = $cookieStore.get('user');
			if(!vm.user) {
				$state.go('member');
			}
		}
		
		function getPositionsAll() {
			MemberServices.getPositionsAll()
				.success(function(data) {
					vm.positions = data.data;
				}).error(function(error) {
					console.log(error);
				});
		}

		function addPosition(position) {
			vm.loading = true;
			MemberServices.addPosition(position)
				.success(function(data) {
					if(data.status == "success") {
						getPositionsAll();
					}
					vm.loading = false;
				}).error(function(error) {
					console.log(error);
					vm.loading = false;
				})
			vm.newPosition = ""
		}

		function updatePosition(position) {
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
					MemberServices.updatePosition(position)
						.success(function(data) {
							console.log(data);
							if(data.status == "success") {
								position.update = false;
							}
							vm.loading = false;
						}).error(function(error) {
							console.log(error);
							vm.loading = false;
						})
			      	SweetAlert.swal("Updated!", "User has been updated successfully", "success");
			    } else {
			    	vm.loading = false;
			    	getPositionsAll();
			      	SweetAlert.swal("Cancelled", "User does not update yet", "error");
			    }
			  }); 
		}

		function deletePosition(position) {
			vm.loading = true;
			SweetAlert.swal({
			    title: "Are you sure?",
			    text: "All of related data will be cascade permanently! (include staffs)",
			    type: "warning",
			    showCancelButton: true,
			    confirmButtonColor: "#DD6B55",
			    confirmButtonText: "Yes, delete it!",
			    closeOnConfirm: false,
			    cancelButtonText: "No, cancel please!",
				closeOnCancel: false
			  },
			  function(isConfirm){
			  	if (isConfirm) {
			  		MemberServices.deletePosition(position)
						.success(function(data) {
							console.log(data);
							if(data.status == "success") {
								position.update = false;
								SweetAlert.swal("Deleted!", "Position has been deleted successfully", "success");
							} else {
								SweetAlert.swal("Error", data.messages, "error");
							}
							vm.loading = false;
							getPositionsAll();
						}).error(function(error) {
							console.log(error);
							vm.loading = false;
						})
			    } else {
			    	vm.loading = false;
			    	getPositionsAll();
			      	SweetAlert.swal("Cancelled", "User does not update yet", "error");
			    }
			  }); 
		}



	}
})();