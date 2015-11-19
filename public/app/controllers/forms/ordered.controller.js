(function() {
	'use strict';

	angular
			.module('app')
			.controller('OrderedItemsCtrl', OrderedItemsCtrl);

	OrderedItemsCtrl.$inject = ['$state','$cookieStore','CompanyServices','ItemServices','SweetAlert','OrderServices'];

	function OrderedItemsCtrl($state,$cookieStore,CompanyServices,ItemServices,SweetAlert,OrderServices) {
		var vm = this;

		// Var Init
		vm.today = new Date().getTime();
		vm.items = [];

		// Func Init
		vm.deleteItem = deleteItem;
		vm.addToOrder = addToOrder;
		vm.sendOrder = sendOrder;

		// Run
		getCompanyByTypeID(1); // supplier
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

		function addToOrder(item) {
			SweetAlert.swal({
				title: "How many?",
				text: "How many item you want to order",
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
				} else {
					SweetAlert.swal("Nice!", item.attributes.name + " " + qty + " units have been add to order" , "success");
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

		function sendOrder(company,items,user) {
			OrderServices.addOrder(company,items,user)
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