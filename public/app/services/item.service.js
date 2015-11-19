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
			stockAdjust: 		stockAdjust,
			addItem: 			addItem,
			addItemByBranch: 	addItemByBranch,
			updateItem: 		updateItem,
			updateItemsByBranch:updateItemsByBranch,
			deleteItem: 		deleteItem,
			deleteItemByBranch: deleteItemByBranch,
			getItemsAll: 		getItemsAll,
			getItem: 			getItem,
			getItemsByReg: 		getItemsByReg,
			getItemsByOrder: 	getItemsByOrder,
			getItemsByInvoice: 	getItemsByInvoice,
			getItemsByBranch: 	getItemsByBranch,
			getItemsByQuantity: getItemsByQuantity,
			getItemTypesAll: 	getItemTypesAll
		};
		return itemServices;

		function registerItem(supplier, items, user) {
			var data = angular.toJson({'supplier':supplier,'items':items,'user':user});
			return $http.post(urlBase+'api/item/registerItem.php',data);
		}

		function stockAdjust(items, user) {
			var updateItems = [];
			angular.forEach(items,function(item) {
				if(item.update == true) {
					updateItems.push(item);
				}
			});
			
			var data = angular.toJson({'items':updateItems,'user':user});
			return $http.post(urlBase+'api/item/stockAdjust.php',data);
		}

		function addItem(item) {
			var data = angular.toJson({'item':item});
			return $http.post(urlBase+'api/item/addItem.php',data);
		}

		function addItemByBranch(item, branch) {

		}

		function updateItem(item) {
			var data = angular.toJson({'item':item});
			return $http.post(urlBase+'api/item/updateItem.php',data);
		}

		function updateItemsByBranch(item,branch) {
			/* itemCode,itemName */
		}

		function deleteItem(item) {
			var data = angular.toJson({'item':item});
			return $http.post(urlBase+'api/item/deleteItem.php',data);
		}

		function deleteItemByBranch(item, branch) {

		}

		function getItemsAll() {
			return $http.get(urlBase+'api/item/getItemsAll.php');
		}

		function getItem(itemID) {
			var item = {'_id':itemID};
			var data = angular.toJson({'item':item});
			console.log(data);
			return $http.post(urlBase+'api/item/getItem.php',data);
		}

		function getItemsByReg(regCode,branch) {
			var data = angular.toJson({'register_code':regCode,'branch':branch});
			return $http.post(urlBase+'api/item/getItemsByReg.php',data);
		}

		function getItemsByOrder(orderCode) {

		}

		function getItemsByInvoice(invCode) {

		}

		function getItemsByBranch(branch) {
			var data = angular.toJson({'branch':branch});
			return $http.post(urlBase+'api/item/getItemsByBranch.php',data);
		}

		function getItemsByQuantity(branch,quantity) {
			var data = angular.toJson({'branch':branch, 'quantity':quantity});
			return $http.post(urlBase+'api/item/getItemsByQuantity.php',data);
		}

		function getItemTypesAll() {
			return $http.get(urlBase+'api/item/getItemTypesAll.php');
		}

	}

})();