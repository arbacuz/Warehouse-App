(function() {
	'use strict';

	angular
			.module('app')
			.controller('itemInfoCtrl', itemInfoCtrl);

	itemInfoCtrl.$inject = ['$state', '$cookieStore', '$stateParams', 'ItemServices'];

	function itemInfoCtrl ($state, $cookieStore, $stateParams, ItemServices) {
		var vm = this;

		// Var Init

		// Func Init

		// Run
		isLogin();

		getItem($stateParams.id);

		function isLogin() {
			vm.user = $cookieStore.get('user');
			if(!vm.user) {
				$state.go('member');
			}
		}		

		function getItem(itemID) {
			ItemServices.getItem(itemID)
				.success(function(data) {
					vm.item = data.data;
				}).error(function(data) {
					console.log(data);
				})
		}

	}
})();