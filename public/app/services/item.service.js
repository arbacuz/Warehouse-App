(function() {
	'use strict';
	
	angular
			.module('app')
			.factory('ItemServices', ItemServices);

	ItemServices.$inject = ['$q', '$http', '$rootScope'];
	
	function ItemServices($q, $http, $rootScope) {
		var urlBase = "http://localhost:8888/warehouse-proj/Warehouse-App/public/";
		var itemServices = {
			registerItem: 		registerItem,
			addItemByBranch: 	addItemByBranch,
			updateItems: 		updateItems,
			updateItemsByBranch:updateItemsByBranch,
			deleteItem: 		deleteItem,
			deleteItemByBranch: deleteItemByBranch,
			getItemsAll: 		getItemsAll,
			getItem: 			getItem,
			getItemsByReg: 		getItemsByReg,
			getItemsByOrder: 	getItemsByOrder,
			getItemsByInvoice: 	getItemsByInvoice,
			getItemsByBranch: 	getItemsByBranch,
			getItemsByQuantity: getItemsByQuantity
		};
		return itemServices;

		function registerItem(supplier, items) {
			// var data = angular.toJson({"name":items.name,"cost":items.cost,"quantity":items.quantity,"type":items.type});
			var data = angular.toJson(items);
			console.log(data);
			return $http.post(urlBase+'api/item/addItem.php',data);
		}

		function addItemByBranch(item, branch) {

		}

		function updateItems(item) {
			/* itemCode,itemName */
		}

		function updateItemsByBranch(item,branch) {
			/* itemCode,itemName */
		}

		function deleteItem(item) {

		}

		function deleteItemByBranch(item, branch) {

		}

		function getItemsAll() {

		}

		function getItem(item) {
			/* itemCode,itemName */
		}

		function getItemsByReg(regCode) {

		}

		function getItemsByOrder(orderCode) {

		}

		function getItemsByInvoice(invCode) {

		}

		function getItemsByBranch(branch) {

		}

		function getItemsByQuantity(qty) {

		}

	}

})();