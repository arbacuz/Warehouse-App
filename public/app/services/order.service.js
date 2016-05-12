(function() {
	'use strict';
	
	angular
			.module('app')
			.factory('OrderServices', OrderServices);

	OrderServices.$inject = ['$q', '$http', '$rootScope'];
	
	function OrderServices($q, $http, $rootScope) {
		var urlBase = "http://localhost:8888/warehouse-proj/Warehouse-App/public/";
		var orderServices = {
			addOrder: 				addOrder,
			addRequest: 			addRequest,
			addStatus: 				addStatus,
			updateOrder: 			updateOrder,
			updateRequest: 			updateRequest,
			deleteOrder: 			deleteOrder,
			deleteRequest: 			deleteRequest,
			deleteStatus: 			deleteStatus,
			getOrdersAll: 			getOrdersAll,
			getOrder: 				getOrder,
			getOrdersByTime: 		getOrdersByTime,
			getInvoiceHistory: 		getInvoiceHistory,
			getOrdersByItem: 		getOrdersByItem,
			getOrdersByStatus: 		getOrdersByStatus,
			getOrdersByInvoice: 	getOrdersByInvoice,
			getStatusesAll: 		getStatusesAll,
			getStockcard: 			getStockcard,
			dashboard: 				dashboard
		};
		return orderServices;

		function addOrder(company,items,user) {
			var data = angular.toJson({'company':company,'items':items,'user':user});
			return $http.post(urlBase+'api/order/addOrder.php',data);
		}

		function addRequest(company,items,user) {
			var data = angular.toJson({'company':company,'items':items,'user':user});
			return $http.post(urlBase+'api/order/addRequest.php',data);
		}

		function addStatus(status) {
			var data = angular.toJson({'status':status});
			return $http.post(urlBase+'api/order/addStatus.php',data);
		}

		function updateOrder(order) {
			var data = angular.toJson({'order':order,'status':status});
			return $http.post(urlBase+'api/order/updateOrder.php',data);
		}

		function updateRequest(request) {
			var data = angular.toJson({'order':request});
			return $http.post(urlBase+'api/order/updateRequest.php',data);
		}

		function deleteOrder(order) {
			var data = angular.toJson({'order':order});
			return $http.post(urlBase+'api/order/deleteOrder.php',data);
		}

		function deleteRequest(request) {
			var data = angular.toJson({'request':request});
			return $http.post(urlBase+'api/order/deleteRequest.php',data);
		}

		function deleteStatus(status) {
			var data = angular.toJson({'status':status});
			return $http.post(urlBase+'api/order/deleteStatus.php',data);
		}

		function getOrdersAll() {
			return $http.get(urlBase+'api/order/getOrdersAll.php');
		}

		function getOrder(orderID) {
			var order = {'_id':orderID};
			var data = angular.toJson({'order':order});
			return $http.post(urlBase+'api/order/getOrder.php',data);
		}

		function getOrdersByTime(date,branch) {
			var data = angular.toJson({'date':date,'branch':branch});
			return $http.post(urlBase+'api/order/getOrdersByTime.php',data);
		}

		function getInvoiceHistory(date,branch) {
			var data = angular.toJson({'date':date,'branch':branch});
			return $http.post(urlBase+'api/order/getInvoiceHistory.php',data);
		}

		function getOrdersByItem(item,branch) {
			var data = angular.toJson({'item':item,'branch':branch});
			return $http.post(urlBase+'api/order/getOrdersByItem.php',data);
		}

		function getOrdersByStatus(status,branch) {
			var data = angular.toJson({'status':status,'branch':branch});
			return $http.post(urlBase+'api/order/getOrdersByStatus.php',data);
		}

		function getOrdersByInvoice(invCode) {
			var data = angular.toJson({'order':order});
			return $http.post(urlBase+'api/order/getOrdersByInvoice.php',data);
		}

		function getStatusesAll() {
			return $http.get(urlBase+'api/order/getStatusesAll.php');
		}

		function getStockcard(branch,month,item) {
			var data = angular.toJson({'branch':branch, 'month':month, 'itemName':item});
			return $http.post(urlBase+'api/order/getStockcard.php',data);
		}

		function dashboard(user) {
			console.log(user);
			var data = angular.toJson({'user':user});
			return $http.post(urlBase+'api/order/dashboard.php',data);
		}

	}

})();