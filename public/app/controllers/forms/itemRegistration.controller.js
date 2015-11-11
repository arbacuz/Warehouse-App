(function() {
	'use strict';

	angular
			.module('app')
			.controller('ItemRegistrationCtrl', ItemRegistrationCtrl);

	ItemRegistrationCtrl.$inject = [
									'$state',
									'$cookieStore',
									'$scope',
									'$stateParams',
									'ItemServices',
									'CompanyServices',
									'BranchServices'
									];

	function ItemRegistrationCtrl (
								$state,
								$cookieStore,
								$scope,
								$stateParams,
								ItemServices,
								CompanyServices,
								BranchServices
								) {

		$scope.addItem = addItem;
		$scope.completeItems = completeItems;
		$scope.items = [];
		$scope.today = new Date().getTime();
		$scope.deleteItem = deleteItem;

		isLogin();
		getCompanyByTypeID(2);
		getItemTypesAll();


		function isLogin() {
			$scope.user = $cookieStore.get('user');
			console.log($scope.user);
			if(!$scope.user) {
				$state.go('member');
			}
		}
		function addItem(item) {
			if(!item.name || !item.type || !item.cost || !item.quantity) {
				console.log("some value is missing");
			} else {
				var newItem = {
					name: item.name,
					type: item.type,
					cost: item.cost,
					quantity: item.quantity
				}
				$scope.items.push(newItem);
				item.name = "";
				item.type = $scope.itemTypes[0];
				item.cost = "";
				item.quantity = "";
			}
		}

		function completeItems(supplier, items, user) {
			ItemServices.registerItem(supplier, items, user)
				.success(function(data) {
					console.log(data);
				}).error(function(error) {
					console.log(error);
				})
			$scope.items = [];
		}

		function getCompanyByTypeID(type) {
			CompanyServices.getCompanyByTypeID(type)
				.success(function(data) {
					if(data.status == "success") {
						$scope.companies = data.data;
					} else {
						console.log(data.status + data.messages);
					}
				}).error(function(error) {
					console.log(error);
				});
		}

		function getItemTypesAll() {
			ItemServices.getItemTypesAll()
				.success(function(data) {
					if(data.status == "success") {
						console.log(data.data);
						$scope.itemTypes = data.data;
					} else {
						console.log(data.status + data.messages);
					}
				}).error(function(error) {
					console.log(error);
				})
		}

		function deleteItem(idx) {
	    	$scope.items.splice(idx, 1);
	  	}
	}
})();