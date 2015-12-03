(function() {
	'use strict';

	angular
			.module('app')
			.controller('RequestedItemsCtrl', RequestedItemsCtrl);

	RequestedItemsCtrl.$inject = ['$state','$cookieStore','CompanyServices','ItemServices','BranchServices','SweetAlert','OrderServices'];

	function RequestedItemsCtrl($state,$cookieStore,CompanyServices,ItemServices,BranchServices,SweetAlert,OrderServices) {
		var vm = this;

		// Var Init
		vm.today = new Date().getTime();
		vm.items = [];

		// Func Init
		vm.deleteItem = deleteItem;
		vm.addToRequest = addToRequest;
		vm.sendRequest = sendRequest;

		// Run
		getBranchAll();
		isLogin();

		function isLogin() {
			vm.user = $cookieStore.get('user');
			if(!vm.user) {
				$state.go('member');
			} else {
				getItemsByBranch(vm.user.relationships.branch);
			}
		}		

		function getItemsByBranch(branch) {
			ItemServices.getItemsByBranch(branch)
				.success(function(data) {
					vm.itemsAll = data.data;
				}).error(function(error) {
					console.log(error);
				})
		}

		function deleteItem(idx) {
	    	vm.items.splice(idx, 1);
	  	}

	  	function getBranchAll() {
			BranchServices.getBranchAll()
				.success(function(data) {
					vm.branches = data.data;
				}).error(function(error) {
					console.log(error);
				});
		}

		function addToRequest(item) {
			SweetAlert.swal({
				title: "How many?",
				text: "How many item you want to request",
				type: "input",
				showCancelButton: true,
				closeOnConfirm: false,
				inputPlaceholder: "1 - 99999"
			},function(qty){
				if (qty === false) return false;
				if (qty === "") {
					swal.showInputError("You need to add number!");
					return false;
				} else if(isNaN(parseInt(qty))) {
					swal.showInputError("Please enter the number!");
				} else if (item.attributes.quantity - qty < 0) {
					swal.showInputError("Not enough item units!");
					return false;
				} else if (qty < 0) {
					swal.showInputError("You cannot assign number of item lower than 0.");
					return false;
				} else {
					SweetAlert.swal("Nice!", item.attributes.name + " " + qty + " units have been add to request" , "success");
					item.attributes.quantity -= qty;
					var found = 0;
					for(var i = 0; i < vm.items.length; i++) {
						if(vm.items[i]._id == item.attributes._id) {
							vm.items[i].quantity += parseInt(qty);
							found = 1;
							break;
						} else {
							found = 0;
						}
					};
					if(!found) {
						var newItem = {
							_id: item.attributes._id,
							name: item.attributes.name,
							type: item.relationships.type.name,
							cost: item.attributes.cost,
							quantity: parseInt(qty)
						}
						vm.items.push(newItem);
					}
				}
			});
		}

		function sendRequest(company,items,user) {
			OrderServices.addRequest(company,items,user)
				.success(function(data) {
					if(data.status == "success") {
						SweetAlert.swal("Completed!",data.messages,"success");
						vm.items = [];
					} else {
						SweetAlert.swal("Error!",data.messages,"error");
					}
				}).error(function(data) {
					SweetAlert.swal("Error!",data.messages,"error");
				})
		}
	}
})();