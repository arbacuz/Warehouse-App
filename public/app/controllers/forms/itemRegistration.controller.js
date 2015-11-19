(function() {
	'use strict';

	angular
			.module('app')
			.controller('ItemRegistrationCtrl', ItemRegistrationCtrl);

	ItemRegistrationCtrl.$inject = ['$state', '$cookieStore', 'SweetAlert', 'ItemServices', 'CompanyServices', 'BranchServices'];

	function ItemRegistrationCtrl ($state, $cookieStore, SweetAlert, ItemServices, CompanyServices, BranchServices) {
		var vm = this;

		// Var Init
		vm.items = [];
		vm.today = new Date().getTime();

		// Func Init
		vm.addItem = addItem;
		vm.completeItems = completeItems;
		vm.deleteItem = deleteItem;

		// Run
		isLogin();
		getCompanyByTypeID(2);
		getItemTypesAll();


		function isLogin() {
			vm.user = $cookieStore.get('user');
			console.log(vm.user);
			if(!vm.user) {
				$state.go('member');
			}
		}
		function addItem(item) {
			if(!item.name || !item.type || !item.cost || !item.quantity) {
				console.log("some value is missing");
			} else {
				var newItem = {
					name: item.name,
					type: item.type,
					cost: item.cost,
					quantity: item.quantity
				}
				vm.items.push(newItem);
				item.name = "";
				item.type = vm.itemTypes[0];
				item.cost = "";
				item.quantity = "";
			}
		}

		function completeItems(supplier, items, user) {
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
			  		ItemServices.registerItem(supplier, items, user)
						.success(function(data) {
							if(data.status == "success") {
								SweetAlert.swal("Complete!", "Item has been registered successfully", "success");
								vm.loading = false;
							} else {
								SweetAlert.swal("Error!", "Item does not register yet", "error");
								vm.loading = false;
							}
							vm.items = [];
						}).error(function(error) {
							SweetAlert.swal("Error!", "Item does not register yet", "error");
						})
			    } else {
			    	vm.loading = false;
			      	SweetAlert.swal("Cancelled", "Item does not update yet", "error");
			    }
			  }); 
		}

		function getCompanyByTypeID(type) {
			CompanyServices.getCompanyByTypeID(type)
				.success(function(data) {
					if(data.status == "success") {
						vm.companies = data.data;
					} else {
						console.log(data.status + data.messages);
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
						console.log(data.status + data.messages);
					}
				}).error(function(error) {
					console.log(error);
				})
		}

		function deleteItem(idx) {
	    	vm.items.splice(idx, 1);
	  	}
	}
})();