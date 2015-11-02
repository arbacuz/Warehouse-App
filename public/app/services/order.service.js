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
			updateOrderStatus: 		updateOrderStatus,
			updateReqStatus: 		updateReqStatus,
			deleteOrder: 			deleteOrder,
			deleteStatus: 			deleteStatus,
			getOrdersAll: 			getOrdersAll,
			getOrder: 				getOrder,
			getOrdersByTime: 		getOrdersByTime,
			getOrdersByItem: 		getOrdersByItem,
			getOrdersByStatus: 		getOrdersByStatus,
			getOrdersByInvoice: 	getOrdersByInvoice,
			getStatus: 				getStatus
		};
		return orderServices;

		function addOrder(order) {
			/* JSON ORDER with orderDetails and orderItems */
		}

		function addRequest(request) {

		}

		function addStatus(status) {

		}

		function updateOrderStatus(orderCode) {

		}

		function updateReqStatus(reqCode) {

		}

		function deleteOrder(orderCode) {

		}

		function deleteStatus(status) {

		}

		function getOrdersAll() {

		}

		function getOrder(orderCode) {

		}

		function getOrdersByTime(time) {

		}

		function getOrdersByItem(item) {
			/* itemCode or itemName */
		}

		function getOrdersByStatus(status) {

		}

		function getOrdersByInvoice(invCode) {

		}

		function getStatus() {
			
		}

	}

})();