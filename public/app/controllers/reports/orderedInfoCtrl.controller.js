(function() {
	'use strict';

	angular
			.module('app')
			.controller('orderedInfoCtrl', orderedInfoCtrl);

	orderedInfoCtrl.$inject = ['$state', '$cookieStore', '$stateParams', 'OrderServices'];

	function orderedInfoCtrl ($state, $cookieStore, $stateParams, OrderServices) {
		var vm = this;

		// Var Init
		vm.order = {};

		// Func Init

		// Run
		isLogin();

		getOrder($stateParams.id)

		function isLogin() {
			vm.user = $cookieStore.get('user');
			if(!vm.user) {
				$state.go('member');
			}
		}		

		function getOrder(orderID) {
			OrderServices.getOrder(orderID)
				.success(function(data) {
					console.log(data);
					vm.order = data.data;
				}).error(function(data){
					console.log(data);
				}) 
		}



	}
})();