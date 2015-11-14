(function() {
	'use strict';

	angular
			.module('app')
			.controller('ItemRegistrationCtrl', ItemRegistrationCtrl);

	ItemRegistrationCtrl.$inject = [
									'$state',
									'$cookieStore',
									'$scope',
									'$stateParams',
									'SweetAlert',
									'ItemServices',
									'CompanyServices',
									'BranchServices'
									];

	function ItemRegistrationCtrl (
								$state,
								$cookieStore,
								$scope,
								$stateParams,
								SweetAlert,
								ItemServices,
								CompanyServices,
								BranchServices
								) {

		$scope.addItem = addItem;
		$scope.completeItems = completeItems;
		$scope.items = [];
		$scope.today = new Date().getTime();
		$scope.deleteItem = deleteItem;

		isLogin();
		getCompanyByTypeID(2);
		getItemTypesAll();


		function isLogin() {
			$scope.user = $cookieStore.get('user');
			console.log($scope.user);
			if(!$scope.user) {
				$state.go('member');
			}
		}
		function addItem(item) {
			if(!item.name || !item.type || !item.cost || !item.quantity) {
				console.log("some value is missing");
			} else {
				var newItem = {
					name: item.name,
					type: item.type,
					cost: item.cost,
					quantity: item.quantity
				}
				$scope.items.push(newItem);
				item.name = "";
				item.type = $scope.itemTypes[0];
				item.cost = "";
				item.quantity = "";
			}
		}

		function completeItems(supplier, items, user) {
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
			  		ItemServices.registerItem(supplier, items, user)
						.success(function(data) {
							if(data.status == "success") {
								SweetAlert.swal("Complete!", "Item has been registered successfully", "success");
								$scope.loading = false;
							} else {
								SweetAlert.swal("Error!", "Item does not register yet", "error");
								$scope.loading = false;
							}
							$scope.items = [];
						}).error(function(error) {
							SweetAlert.swal("Error!", "Item does not register yet", "error");
						})
			    } else {
			    	$scope.loading = false;
			      	SweetAlert.swal("Cancelled", "Item does not update yet", "error");
			    }
			  }); 
		}

		function getCompanyByTypeID(type) {
			CompanyServices.getCompanyByTypeID(type)
				.success(function(data) {
					if(data.status == "success") {
						$scope.companies = data.data;
					} else {
						console.log(data.status + data.messages);
					}
				}).error(function(error) {
					console.log(error);
				});
		}

		function getItemTypesAll() {
			ItemServices.getItemTypesAll()
				.success(function(data) {
					if(data.status == "success") {
						console.log(data.data);
						$scope.itemTypes = data.data;
					} else {
						console.log(data.status + data.messages);
					}
				}).error(function(error) {
					console.log(error);
				})
		}

		function deleteItem(idx) {
	    	$scope.items.splice(idx, 1);
	  	}
	}
})();