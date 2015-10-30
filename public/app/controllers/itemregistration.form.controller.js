(function() {
	'use strict';

	angular
			.module('app')
			.controller('ItemRegistrationCtrl', ItemRegistrationCtrl);

	ItemRegistrationCtrl.$inject = ['$scope','$stateParams','ItemServices'];

	function ItemRegistrationCtrl($scope,$stateParams,ItemServices) {
		$scope.addItem = addItem;
		$scope.completeItems = completeItems;
		$scope.items = [
				{
					name: 'TV Sony 42" Model: 5G112TG',
					type: 'Product',
					cost: '18,500',
					quantity: '3,000'
				},
				{
					name: 'Microwave SR992',
					type: 'Product',
					cost: '2,700',
					quantity: '40,000'
				}
		];
		function addItem(item) {
			// console.log(item);
			var newItem = {
				name: item.name,
				type: item.type,
				cost: item.cost,
				quantity: item.quantity
			}
			$scope.items.push(newItem);
			item.name = "";
			item.type = "";
			item.cost = "";
			item.quantity = "";
		}

		function completeItems(supplier, items) {
			ItemServices.registerItem(supplier, items)
				.success(function(data) {
					console.log(data);
				}).error(function(error) {
					console.log(error);
				})
		}
	}
})();