(function() {
	'use strict';
	
	angular
			.module('app')
			.factory('ItemServices', ItemServices);

	ItemServices.$inject = ['$q', '$http', '$rootScope'];
	
	function ItemServices($q, $http, $rootScope) {
		var urlBase = "http://localhost:8888/warehouse-proj/Warehouse-App/public/";
		var itemServices = {
			registerItem: registerItem
		};
		return itemServices;

		function registerItem(supplier, items) {
			// var data = angular.toJson({"name":items.name,"cost":items.cost,"quantity":items.quantity,"type":items.type});
			var data = angular.toJson(items);
			console.log(data);
			return $http.post(urlBase+'api/registerItem/post.php',data);
		}
	}

})();