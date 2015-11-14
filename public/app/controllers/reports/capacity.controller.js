(function() {
	'use strict';

	angular
			.module('app')
			.controller('capacityCtrl', capacityCtrl);

	capacityCtrl.$inject = [
							'$state',
							'$cookieStore',
							'$scope',
							'$stateParams',
							'SweetAlert',
							'BranchServices'
							];

	function capacityCtrl(
							$state,
							$cookieStore,
							$scope,
							$stateParams,
							SweetAlert,
							BranchServices
							) {

		isLogin();
		$scope.branch = "";

		function isLogin() {
			$scope.user = $cookieStore.get('user');
			if(!$scope.user) {
				$state.go('member');
			} else {
				getCapacity($scope.user.relationships.branch);
			}
		}

		function getCapacity(branch) {
			BranchServices.getCapacity(branch)
				.success(function(data) {
					$scope.branch = data.data;
					var free = $scope.branch.attributes.free;
					var usage = $scope.branch.attributes.usage;
					$scope.chartLabels = [ "Usage" , "Free"];
					$scope.chartColours = [{
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
					$scope.chartOptions = {animationEasing: 'easeInOutSine'};
					$scope.chartData = [ usage , free ];
					console.log($scope.chartData);
				}).error(function(error) {
					console.log(error);
				})
		}
		
		
	}
})();