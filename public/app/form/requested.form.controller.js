(function() {
	'use strict';

	angular
			.module('app')
			.controller('RequestedItemsCtrl', RequestedItemsCtrl);

	RequestedItemsCtrl.$inject = ['$scope'];

	function RequestedItemsCtrl($scope) {
		$scope.deleteItem = deleteItem;
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

		function deleteItem(idx) {
	    $scope.items.splice(idx, 1);
	  }
	}
})();