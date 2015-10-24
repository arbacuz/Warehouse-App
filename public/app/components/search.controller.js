(function() {
	'use strict';

	angular
			.module('app')
			.controller('SearchCtrl', SearchCtrl);

	SearchCtrl.$inject = ['ShopServices', '$routeParams'];

	function SearchCtrl(ShopServices, $routeParams){
		var vm = this;

		// Variables Init
		var pageNum 				= $routeParams.page;
		var shopPerPage 		= 10;
		vm.pages 						= [];
		vm.keyword 					= $routeParams.keyword;

		// Functions Init
		vm.next 						= next;
		vm.prev 						= prev;

		// Run
		getShopWithItems(vm.keyword);
		shopPagination(shopPerPage);

		function getShopWithItems(keyword) {
			ShopServices.getShopWithItems(keyword)
				.then(function(shops) {
					vm.shops = shops;
				},
				function(error) {
					console.log(error);
				});
		}

		function shopPagination(shopPerPage) {
			ShopServices.shopCount(shopPerPage)
				.then(function(pages) {
					for(var i = 1; i <= pages; i++) {
						vm.pages.push({ number: i });
					}
					errorPageRedirect(pageNum);
				},
				function(error) {
					console.log(error);
				});
		}

		function errorPageRedirect(pageNum) {
			var isNum = isNaN(pageNum);
			if(!pageNum) {
				vm.pages[0].status = true;
			} else if(isNum) {
				window.location.href = "/#/search";
			} else if((pageNum < 1) || (pageNum > vm.pages.length)) {
				window.location.href = "/#/search";
			} else {
				vm.pages[pageNum-1].status = true;	
			}
		}

		function next(index) {
			vm.shops[index].itemIndex++;
		}

		function prev(index) {
			vm.shops[index].itemIndex--;
		}

	}

})();