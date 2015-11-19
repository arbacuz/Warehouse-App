(function() {
	'use strict';

	angular
			.module('app')
			.controller('stockAdjustmentCtrl', stockAdjustmentCtrl);

	stockAdjustmentCtrl.$inject = ['$state', '$cookieStore', 'ItemServices', 'SweetAlert'];

	function stockAdjustmentCtrl($state, $cookieStore, ItemServices, SweetAlert) {
		var vm = this;

		// Var Init
		vm.today = new Date().getTime();
		vm.items = [];

		// Func Init
		vm.stockAdjust = stockAdjust;

		// Run
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
					vm.items = data.data;
				}).error(function(error) {
					console.log(error);
				})
		}

		function stockAdjust(items) {
			ItemServices.stockAdjust(items,vm.user)
				.success(function(data) {
					if(data.status == "success") {
						SweetAlert.swal("Completed", "Stock adjust successfully.", "success");
						getItemsByBranch(vm.user.relationships.branch);
					}
				}).error(function(error) {
					console.log(error);
					SweetAlert.swal("Error", error, "error");
				})
		}
	}
})();