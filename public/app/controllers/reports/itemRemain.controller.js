(function() {
	'use strict';

	angular
			.module('app')
			.controller('itemRemainCtrl', itemRemainCtrl);

	itemRemainCtrl.$inject = ['$state', '$cookieStore', '$stateParams', 'ItemServices'];

	function itemRemainCtrl($state, $cookieStore, $stateParams, ItemServices) {
		var vm = this;

		// Var Init
		vm.items = [];

		// Func Init

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
	}
})();