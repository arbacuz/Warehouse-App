(function() {
	'use strict';

	angular
			.module('app')
			.controller('DashboardCtrl', DashboardCtrl);

	DashboardCtrl.$inject = ['$state','$cookieStore','OrderServices'];

	function DashboardCtrl($state,$cookieStore,OrderServices) {
		var vm = this;

		// Func Init
		vm.orderChart = [];
		vm.labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul','Aug','Sep','Oct','Nov','Dec'];
		vm.series = ['Delivered', 'Pending','Canceled'];
		
		// Run
		isLogin();
		getInOutOrder(vm.user);
		console.log('dashboard');
		function isLogin() {
			vm.user = $cookieStore.get('user');
			if(!vm.user) {
				$state.go('member');
			}
		}


		function getInOutOrder(branch) {
			OrderServices.dashboard(branch)
				.success(function(data) {
					// vm.orderChart = data.data;
					console.log(data.data.delivered);
					vm.orderChart = [
						data.data.delivered,
						data.data.pending,
						data.data.canceled,
					];
					console.log(data);
				})
		}




	}
})();