(function() {
	'use strict';

	angular
			.module('app')
			.controller('positionListCtrl', positionListCtrl);

	positionListCtrl.$inject = [
							'$state',
							'$cookieStore',
							'$scope',
							'$stateParams',
							'MemberServices'
							];

	function positionListCtrl(
							$state,
							$cookieStore,
							$scope,
							$stateParams,
							MemberServices
							) {

		isLogin();
		$scope.positions = [];
		$scope.addPosition = addPosition;
		$scope.updatePosition = updatePosition;
		$scope.deletePosition = deletePosition;

		getPositionsAll();

		function isLogin() {
			$scope.user = $cookieStore.get('user');
			if(!$scope.user) {
				$state.go('member');
			} else {
				// getItemsByBranch($scope.user.relationships.branch);
			}
		}
		
		function getPositionsAll() {
			MemberServices.getPositionsAll()
				.success(function(data) {
					// console.log(data);
					$scope.positions = data.data;
				}).error(function(error) {
					console.log(error);
				});
		}

		function addPosition(position) {
			$scope.loading = true;
			MemberServices.addPosition(position)
				.success(function(data) {
					if(data.status == "success") {
						getPositionsAll();
					}
					$scope.loading = false;
				}).error(function(error) {
					console.log(error);
					$scope.loading = false;
				})
			$scope.newPosition = ""
		}

		function updatePosition(position) {
			$scope.loading = true;
			MemberServices.updatePosition(position)
				.success(function(data) {
					console.log(data);
					if(data.status == "success") {
						position.update = false;
					}
					$scope.loading = false;
				}).error(function(error) {
					console.log(error);
					$scope.loading = false;
				})
		}

		function deletePosition(position) {
			$scope.loading = true;
			MemberServices.deletePosition(position)
				.success(function(data) {
					console.log(data);
					getPositionsAll();
					$scope.loading = false;
				}).error(function(error) {
					console.log(error);
					$scope.loading = false;
				})
		}

		
		
	}
})();