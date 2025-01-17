(function() {
	'use strict';

	angular
			.module('app')
			.controller('itemListCtrl', itemListCtrl);

	itemListCtrl.$inject = ['$state', '$cookieStore', '$stateParams', 'SweetAlert', 'ItemServices'];

	function itemListCtrl($state, $cookieStore, $stateParams, SweetAlert, ItemServices) {
		var vm = this;

		// Var Init
		vm.items = [];
		vm.itemTypes = [];

		// Func Init
		vm.updateItem = updateItem;
		vm.addItem = addItem;
		vm.deleteItem = deleteItem;
		
		// Run
		isLogin();
		getItemsAll();
		getItemTypesAll();

		function isLogin() {
			vm.user = $cookieStore.get('user');
			if(!vm.user) {
				$state.go('member');
			}
		}
		
		function getItemsAll() {
			ItemServices.getItemsAll()
				.success(function(data) {
					vm.items = data.data;
					if(data.status == "error") {
						SweetAlert.swal("Error", data.messages, "error");
					}
				}).error(function(error) {
					console.log(error);
				});
		}

		function getItemTypesAll() {
			ItemServices.getItemTypesAll()
				.success(function(data) {
					if(data.status == "success") {
						vm.itemTypes = data.data;
					} else {
						SweetAlert.swal("Error", data.messages, "error");
					}
				}).error(function(error) {
					console.log(error);
				})
		}

		function addItem(item) {
			vm.loading = true;
			ItemServices.addItem(item)
				.success(function(data) {
					if(data.status == "success") {
						getItemsAll();
					} else {
						SweetAlert.swal("Error", data.messages, "error");
					}
					vm.loading = false;
				}).error(function(error) {
					console.log(error);
					vm.loading = false;
				})
			vm.newItem = "";
		}

		function updateItem(item) {
			vm.loading = true;
			SweetAlert.swal({
			    title: "Are you sure?",
			    text: "You cannot recover the item after upadted.",
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
			  		ItemServices.updateItem(item)
						.success(function(data) {
							console.log(data);
							if(data.status == "success") {
								item.update = false;
								SweetAlert.swal("Updated!", "Item has been updated successfully", "success");
							} else {
								SweetAlert.swal("Error", data.messages, "error");
							}
							vm.loading = false;
						}).error(function(error) {
							console.log(error);
							vm.loading = false;
						})
			    } else {
			    	vm.loading = false;
			    	getItemsAll();
			      	SweetAlert.swal("Cancelled", "Item does not update yet", "error");
			    }
			  }); 
		}

		function deleteItem(item) {
			vm.loading = true;
			SweetAlert.swal({
			    title: "Are you sure?",
			    text: "All of related data will be cascade permanently!",
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
			  		ItemServices.deleteItem(item)
						.success(function(data) {
							console.log(data);
							if(data.status == "success") {
								item.update = false;
								SweetAlert.swal("Deleted!", "Item has been deleted successfully", "success");
							} else {
								SweetAlert.swal("Error", data.messages, "error");
							}
							vm.loading = false;
							getItemsAll();
						}).error(function(error) {
							console.log(error);
							vm.loading = false;
						})
			    } else {
			    	vm.loading = false;
			    	getItemsAll();
			      	SweetAlert.swal("Cancelled", "Item does not update yet", "error");
			    }
			  }); 
		}
		
	}
})();