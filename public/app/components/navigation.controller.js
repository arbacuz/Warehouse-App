(function() {
	'use strict';

	angular
			.module('app')
			.controller('NavigationCtrl', NavigationCtrl);

	NavigationCtrl.$inject = ['UserServices', 'ShopServices'];

	function NavigationCtrl(UserServices, ShopServices) {
		var vm = this;

		// Variables Init
		vm.categories	 			= []; 
		vm.userData;

		// Functions Init
		vm.logOut 					= logOut;

		// Run
		getUser();
		getCategory();

		function getUser() {
			UserServices.getUser()
				.then(function(user) {
					vm.userData = user.attributes;
				},
				function(error){
					console.log(error);
					if(error.code == 209) {
						window.location.href = '#/app/logout';
					}
				});
		}

		function getCategory() {
			ShopServices.getCategory()
				.then(function(categories) {
					var page = Math.ceil(categories.length / 5);
					var initial = 0;
					for(var i = 0; i < page; i++) {
						vm.categories[i] = [];
						for(var j = initial;(j < initial + 5) && (j < categories.length); j++) {
							vm.categories[i].push(categories[j]);
						}
						initial = j;
					}
				},
				function(error) {
					console.log(error);
					if(error.code == 209) {
						window.location.href = '#/app/logout';
					}
				});
		}

		function logOut() {
			UserServices.logOut();
			// window.location.reload();
			window.location = '/';		
		}
	}
})();