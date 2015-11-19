(function() {
	'use strict';

	angular
			.module('app')
			.controller('orderedHistoryStatusCtrl', orderedHistoryStatusCtrl);

	orderedHistoryStatusCtrl.$inject = ['$state','$cookieStore','OrderServices','ItemServices','SweetAlert'];

	function orderedHistoryStatusCtrl($state,$cookieStore,OrderServices,ItemServices,SweetAlert) {
		var vm = this;

		// Var Init
		vm.statuses = [];

		// Func Init
		vm.getOrdersByStatus = getOrdersByStatus;
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

		function getOrdersByStatus(status,branch) {
			OrderServices.getOrdersByStatus(status,branch)
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