(function() {
	'use strict';

	angular
			.module('app')
			.controller('stockCardCtrl', stockCardCtrl);

	stockCardCtrl.$inject = ['$state', '$cookieStore', 'OrderServices'];

	function stockCardCtrl($state, $cookieStore, OrderServices) {
		var vm = this;

		// Var Init
		vm.transactions = [];

		// Func Init
		vm.searchStock = searchStock;

		// Run
		isLogin();

		function isLogin() {
			vm.user = $cookieStore.get('user');
			if(!vm.user) {
				$state.go('member');
			}
		}	

		function searchStock(branch,month,item) {
			OrderServices.getStockcard(branch,month,item)
				.success(function(data){
					console.log("success");
					console.log(data);
					vm.transactions = data.data;
				}).error(function(error) {
					console.log("error");
					console.log(error);
				})
		}
	}
})();