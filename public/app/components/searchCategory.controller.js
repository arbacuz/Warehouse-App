(function() {
	'use strict';

	angular
			.module('app')
			.controller('SearchCategoryCtrl', SearchCategoryCtrl);

	SearchCategoryCtrl.$inject = ['ShopServices', '$routeParams'];

	function SearchCategoryCtrl(ShopServices, $routeParams){
		var vm = this;

		// Variables Init
		var pageNum 						= $routeParams.page;
		var limit 							= 2;
		vm.pages 								= [];
		vm.category_id 					= $routeParams.id;
		if(!pageNum) pageNum 		= 1;

		// Functions Init
		vm.next = next;
		vm.prev = prev;

		// Run
		shopPagination(limit,vm.category_id,pageNum);
		searchShop(limit,pageNum,vm.category_id);

		function searchShop(limit, pageNum, category_id) {
			ShopServices.searchShop(limit, pageNum-1, category_id)
				.then(function(shops) {
					vm.shops = shops;
				},
				function(error) {
					console.log(error);
				})
		}

		function shopPagination(limit, category_id, pageNum) {
			ShopServices.shopCount(limit, category_id)
				.then(function(pages) {
					for(var i = 1; i <= pages; i++) {
						vm.pages.push({ number: i });
					}
					errorPageRedirect(pageNum, vm.category_id);
				},
				function(error) {
					console.log(error);
				});
		}

		function errorPageRedirect(pageNum, category_id) {
			var isNum = isNaN(pageNum);
			if(!pageNum) {
				vm.pages[0].status = true;
			} else if(isNum) {
				window.location.href = "/#/category/"+category_id;
			} else if((pageNum < 1) || (pageNum > vm.pages.length)) {
				window.location.href = "/#/category/"+category_id;
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