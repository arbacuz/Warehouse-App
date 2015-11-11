(function() {
	'use strict';

	angular
			.module('app')
			.controller('itemListCtrl', itemListCtrl);

	itemListCtrl.$inject = [
							'$state',
							'$cookieStore',
							'$scope',
							'$stateParams',
							'ItemServices'
							];

	function itemListCtrl(
							$state,
							$cookieStore,
							$scope,
							$stateParams,
							ItemServices
							) {

		isLogin();
		$scope.items = [];
		$scope.itemTypes = [];

		$scope.updateItem = updateItem;
		$scope.deleteItem = deleteItem;
		
		getItemsAll();
		getItemTypesAll();

		function isLogin() {
			$scope.user = $cookieStore.get('user');
			if(!$scope.user) {
				$state.go('member');
			} else {
				// getItemsByBranch($scope.user.relationships.branch);
			}
		}
		
		function getItemsAll() {
			ItemServices.getItemsAll()
				.success(function(data) {
					// console.log(data);
					$scope.items = data.data;
				}).error(function(error) {
					console.log(error);
				});
		}

		function getItemTypesAll() {
			ItemServices.getItemTypesAll()
				.success(function(data) {
					if(data.status == "success") {
						// console.log(data.data);
						$scope.itemTypes = data.data;
					} else {
						console.log(data.status + data.messages);
					}
				}).error(function(error) {
					console.log(error);
				})
		}

		function updateItem(item) {
			$scope.loading = true;
			ItemServices.updateItem(item)
				.success(function(data) {
					console.log(data);
					if(data.status == "success") {
						item.update = false;
					}
					$scope.loading = false;
				}).error(function(error) {
					console.log(error);
					$scope.loading = false;
				})
		}
		
		function deleteItem(item) {
			ItemServices.deleteItem(item)
				.success(function(data) {
					console.log(data);
					getItemsAll();
					$scope.loading = false;
				}).error(function(error) {
					console.log(error);
					$scope.loading = false;
				})
		}
		
	}
})();