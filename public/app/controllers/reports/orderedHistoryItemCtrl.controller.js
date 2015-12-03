(function() {
	'use strict';

	angular
			.module('app')
			.controller('orderedHistoryItemCtrl', orderedHistoryItemCtrl);

	orderedHistoryItemCtrl.$inject = ['$state','$cookieStore','OrderServices','ItemServices','SweetAlert','$stateParams'];

	function orderedHistoryItemCtrl($state,$cookieStore,OrderServices,ItemServices,SweetAlert,$stateParams) {
		var vm = this;

		// Var Init
		vm.statuses = [];

		// Func Init
		vm.getOrdersByItem = getOrdersByItem;
		vm.updateOrder = updateOrder;

		// Run
		isLogin();
		getStatusesAll();

		function isLogin() {
			vm.user = $cookieStore.get('user');
			if(!vm.user) {
				$state.go('member');
			} else {
				getItemsByBranch(vm.user.relationships.branch);
			}
		}

		function getOrdersByItem(item,branch) {
			OrderServices.getOrdersByItem(item,branch)
				.success(function (data) {
					console.log(data);
					vm.orders = data.data;
				}).error(function (error) {
					console.log(error);
				})
		}

		function getStatusesAll() {
			OrderServices.getStatusesAll()
				.success(function(data) {
					vm.statuses = data.data;
				}).error(function(error) {
					console.log(error);
				})
		}

		function getItemsByBranch(branch) {
			ItemServices.getItemsByBranch(branch)
				.success(function(data) {
					vm.itemsAll = data.data;
				}).error(function(error) {
					console.log(error);
				})
		}

		function updateOrder(order) {
			OrderServices.updateOrder(order)
				.success(function(data) {
					console.log(data);
					if(data.status == "success") {
						SweetAlert.swal("Updated!",data.messages,"success");
						order.update = false;
					} else {
						SweetAlert.swal("Error!",data.messages,"error");
					}
				}).error(function(data) {
					SweetAlert.swal("Error!",data.messages,"error");
				})
		}
		
	}
})();