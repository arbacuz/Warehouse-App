(function() {
	'use strict';

	angular
			.module('app')
			.controller('capacityCtrl', capacityCtrl);

	capacityCtrl.$inject = ['$state', '$cookieStore', '$stateParams', 'SweetAlert', 'BranchServices'];

	function capacityCtrl($state, $cookieStore, $stateParams, SweetAlert, BranchServices) {
		var vm = this;

		// Var Init
		vm.branch = "";

		// Run
		isLogin();

		function isLogin() {
			vm.user = $cookieStore.get('user');
			if(!vm.user) {
				$state.go('member');
			} else {
				getCapacity(vm.user.relationships.branch);
			}
		}

		function getCapacity(branch) {
			BranchServices.getCapacity(branch)
				.success(function(data) {
					vm.branch = data.data;
					var free = Math.round(vm.branch.attributes.free);
					var usage = Math.round(vm.branch.attributes.usage);
					vm.chartLabels = [ "Usage" , "Free"];
					vm.chartColours = [{
					    fillColor: 'rgba(231,76,60,0.8)',
					    strokeColor: 'rgba(231,76,60,0.8)',
					    highlightFill: 'rgba(231,76,60,0.8)',
					    highlightStroke: 'rgba(231,76,60,0.8)'
					},
					{
					    fillColor: 'rgb(212,212,212)',
					    strokeColor: 'rgb(212,212,212)',
					    highlightFill: 'rgb(212,212,212)',
					    highlightStroke: 'rgb(212,212,212)'
					}];
					vm.chartOptions = {animationEasing: 'easeInOutSine'};
					vm.chartData = [ usage , free ];
				}).error(function(error) {
					console.log(error);
				})
		}
		
		
	}
})();