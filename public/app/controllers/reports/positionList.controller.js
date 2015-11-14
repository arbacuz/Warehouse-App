(function() {
	'use strict';

	angular
			.module('app')
			.controller('positionListCtrl', positionListCtrl);

	positionListCtrl.$inject = [
							'$state',
							'$cookieStore',
							'$scope',
							'$stateParams',
							'SweetAlert',
							'MemberServices'
							];

	function positionListCtrl(
							$state,
							$cookieStore,
							$scope,
							$stateParams,
							SweetAlert,
							MemberServices
							) {

		isLogin();
		$scope.positions = [];
		$scope.addPosition = addPosition;
		$scope.updatePosition = updatePosition;

		getPositionsAll();

		function isLogin() {
			$scope.user = $cookieStore.get('user');
			if(!$scope.user) {
				$state.go('member');
			} else {
				// getItemsByBranch($scope.user.relationships.branch);
			}
		}
		
		function getPositionsAll() {
			MemberServices.getPositionsAll()
				.success(function(data) {
					// console.log(data);
					$scope.positions = data.data;
				}).error(function(error) {
					console.log(error);
				});
		}

		function addPosition(position) {
			$scope.loading = true;
			MemberServices.addPosition(position)
				.success(function(data) {
					if(data.status == "success") {
						getPositionsAll();
					}
					$scope.loading = false;
				}).error(function(error) {
					console.log(error);
					$scope.loading = false;
				})
			$scope.newPosition = ""
		}

		function updatePosition(position) {
			$scope.loading = true;
			SweetAlert.swal({
			    title: "Are you sure?",
			    text: "You will not be able to recover this user",
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
					MemberServices.updatePosition(position)
						.success(function(data) {
							console.log(data);
							if(data.status == "success") {
								position.update = false;
							}
							$scope.loading = false;
						}).error(function(error) {
							console.log(error);
							$scope.loading = false;
						})
			      	SweetAlert.swal("Updated!", "User has been updated successfully", "success");
			    } else {
			    	$scope.loading = false;
			    	getPositionsAll();
			      	SweetAlert.swal("Cancelled", "User does not update yet", "error");
			    }
			  }); 
		}


	}
})();