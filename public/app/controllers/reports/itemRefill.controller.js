(function() {
	'use strict';

	angular
			.module('app')
			.controller('itemRefillCtrl', itemRefillCtrl);

	itemRefillCtrl.$inject = ['$state', '$cookieStore', '$stateParams', 'ItemServices'];

	function itemRefillCtrl($state, $cookieStore, $stateParams, ItemServices) {
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
				getItemsByQuantity(vm.user.relationships.branch,50);
			}
		}

		function getItemsByQuantity(branch,quantity) {
			ItemServices.getItemsByQuantity(branch,quantity)
				.success(function(data) {
					console.log(data);
					if(data.status == "success") {
						vm.items = data.data;
					} else {
						SweetAlert.swal("Error", data.messages, "error");
					}
				}).error(function(error) {
					console.log(error);
				})
		}
		
	}
})();