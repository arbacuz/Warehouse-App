(function() {
	'use strict';

	angular
			.module('app')
			.controller('itemRegHistoryCtrl', itemRegHistoryCtrl);

	itemRegHistoryCtrl.$inject = ['$state', '$cookieStore', '$stateParams', 'ItemServices'];

	function itemRegHistoryCtrl($state, $cookieStore, $stateParams, ItemServices) {
		var vm = this;

		// Var Init
		vm.items = [];

		// Func Init
		vm.getItemsByReg = getItemsByReg;

		// Run
		isLogin();

		function isLogin() {
			vm.user = $cookieStore.get('user');
			if(!vm.user) {
				$state.go('member');
			}
		}

		function getItemsByReg(registerCode,branch) {
			ItemServices.getItemsByReg(registerCode,branch)
				.success(function(data) {
					vm.items = data.data;
					console.log(data);
					if(data.status=="success") {
						vm.showTable = true;
					} else {
						vm.showTable = false;
					}
				}).error(function(error) {
					console.log(error);
					vm.showTable = false;
				})
		}
	}
})();