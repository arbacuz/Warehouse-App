(function() {
	'use strict';

	angular
			.module('app')
			.controller('invoiceHistoryCtrl', invoiceHistoryCtrl);

	invoiceHistoryCtrl.$inject = ['$state','$cookieStore','OrderServices','ItemServices','SweetAlert','$stateParams'];

	function invoiceHistoryCtrl($state,$cookieStore,OrderServices,ItemServices,SweetAlert,$stateParams) {
		var vm = this;

		// Var Init
		vm.statuses = [];

		// Func Init
		vm.getInvoiceHistory = getInvoiceHistory;
		vm.updateOrder = updateOrder;

		// Run
		isLogin();
		getStatusesAll();

		function isLogin() {
			vm.user = $cookieStore.get('user');
			if(!vm.user) {
				$state.go('member');
			}
		}

		function getInvoiceHistory(date,branch) {
			OrderServices.getInvoiceHistory(date,branch)
				.success(function (data) {
					console.log(data);
					if(data.status == "error") {
						SweetAlert.swal("Error!",data.messages,"error");
					}
					vm.orders = data.data;
				}).error(function (data) {
					SweetAlert.swal("Error!",data.messages,"error");
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


		function updateOrder(order) {
			OrderServices.updateOrder(order)
				.success(function(data) {
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