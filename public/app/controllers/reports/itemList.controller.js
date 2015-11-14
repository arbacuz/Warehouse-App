(function() {
	'use strict';

	angular
			.module('app')
			.controller('itemListCtrl', itemListCtrl);

	itemListCtrl.$inject = [
							'$state',
							'$cookieStore',
							'$scope',
							'$stateParams',
							'SweetAlert',
							'ItemServices'
							];

	function itemListCtrl(
							$state,
							$cookieStore,
							$scope,
							$stateParams,
							SweetAlert,
							ItemServices
							) {

		isLogin();
		$scope.items = [];
		$scope.itemTypes = [];

		$scope.updateItem = updateItem;
		$scope.addItem = addItem;
		
		getItemsAll();
		getItemTypesAll();

		function isLogin() {
			$scope.user = $cookieStore.get('user');
			if(!$scope.user) {
				$state.go('member');
			} else {
				// getItemsByBranch($scope.user.relationships.branch);
			}
		}
		
		function getItemsAll() {
			ItemServices.getItemsAll()
				.success(function(data) {
					// console.log(data);
					$scope.items = data.data;
				}).error(function(error) {
					console.log(error);
				});
		}

		function getItemTypesAll() {
			ItemServices.getItemTypesAll()
				.success(function(data) {
					if(data.status == "success") {
						// console.log(data.data);
						$scope.itemTypes = data.data;
					} else {
						console.log(data.status + data.messages);
					}
				}).error(function(error) {
					console.log(error);
				})
		}

		function addItem(item) {
			$scope.loading = true;
			ItemServices.addItem(item)
				.success(function(data) {
					console.log(data);
					if(data.status == "success") {
						getItemsAll();
					}
					$scope.loading = false;
				}).error(function(error) {
					console.log(error);
					$scope.loading = false;
				})
			$scope.newItem = "";
		}

		function updateItem(item) {
			$scope.loading = true;
			SweetAlert.swal({
			    title: "Are you sure?",
			    text: "You cannot recover the item after upadted.",
			    type: "warning",
			    showCancelButton: true,
			    confirmButtonColor: "#DD6B55",
			    confirmButtonText: "Yes, update it!",
			    closeOnConfirm: false,
			    cancelButtonText: "No, cancel please!",
				closeOnCancel: false
			  },
			  function(isConfirm){
			  	if (isConfirm) {
			  		ItemServices.updateItem(item)
						.success(function(data) {
							console.log(data);
							if(data.status == "success") {
								item.update = false;
							}
							$scope.loading = false;
						}).error(function(error) {
							console.log(error);
							$scope.loading = false;
						})
			      	SweetAlert.swal("Updated!", "Item has been updated successfully", "success");
			    } else {
			    	$scope.loading = false;
			    	getItemsAll();
			      	SweetAlert.swal("Cancelled", "Item does not update yet", "error");
			    }
			  }); 
		}
		
	}
})();