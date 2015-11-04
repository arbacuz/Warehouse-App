(function() {
	'use strict';

	angular
			.module('app')
			.controller('ItemRegistrationCtrl', ItemRegistrationCtrl);

	ItemRegistrationCtrl.$inject = ['$scope','$stateParams','ItemServices','CompanyServices'];

	function ItemRegistrationCtrl($scope,$stateParams,ItemServices,CompanyServices) {
		$scope.addItem = addItem;
		$scope.completeItems = completeItems;
		$scope.items = [];
		$scope.today = new Date().getTime();

		getCompanyByTypeName("supplier");
		getItemTypesAll();

		function addItem(item) {
			// console.log(item);
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

		function completeItems(supplier, items) {
			ItemServices.addItem(supplier, items)
				.success(function(data) {
					console.log(data);
				}).error(function(error) {
					console.log(error);
				})
		}

		function getCompanyByTypeName(type) {
			CompanyServices.getCompanyByTypeName(type)
				.success(function(data) {
					if(data.status == "success") {
						// console.log(data);
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
						$scope.itemTypes = data.data;
					} else {
						console.log(data.status + data.messages);
					}
				}).error(function(error) {
					console.log(error);
				})
		}
	}
})();