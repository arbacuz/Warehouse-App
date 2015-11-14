(function() {
	'use strict';

	angular
			.module('app')
			.controller('companyListCtrl', companyListCtrl);

	companyListCtrl.$inject = [
							'$state',
							'$cookieStore',
							'$scope',
							'$stateParams',
							'SweetAlert',
							'CompanyServices'
							];

	function companyListCtrl(
							$state,
							$cookieStore,
							$scope,
							$stateParams,
							SweetAlert,
							CompanyServices
							) {

		isLogin();
		$scope.loading = false;
		$scope.companies = [];

		$scope.addCompany = addCompany;
		$scope.updateCompany = updateCompany;

		getCompaniesAll();
		getCompanyTypesAll();

		function isLogin() {
			$scope.user = $cookieStore.get('user');
			if(!$scope.user) {
				$state.go('member');
			} else {
				// getItemsByBranch($scope.user.relationships.branch);
			}
		}
		
		function getCompaniesAll() {
			$scope.loading = true;
			CompanyServices.getCompaniesAll()
				.success(function(data) {
					// console.log(data);
					$scope.companies = data.data;
					$scope.loading = false;
				}).error(function(error) {
					console.log(error);
					$scope.loading = false;
				});
		}

		function getCompanyTypesAll() {
			CompanyServices.getCompanyTypesAll()
				.success(function(data) {
					$scope.companiesType = data.data;
				}).error(function(error){
					console.log(error);
				})
		}

		function addCompany(company) {
			$scope.loading = true;
			CompanyServices.addCompany(company)
				.success(function(data) {
					console.log(data);
					if(data.status == "success") {
						getCompaniesAll();
					}
					$scope.loading = false;
				}).error(function(error) {
					console.log(error);
					$scope.loading = false;
				})
			$scope.newCompany = "";
		}

		function updateCompany(company) {
			$scope.loading = true;
			SweetAlert.swal({
			    title: "Are you sure?",
			    text: "You will not be able to recover this company",
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
			  		CompanyServices.updateCompany(company)
						.success(function(data) {
							console.log(data);
							if(data.status == "success") {
								company.update = false;
							}
							$scope.loading = false;
						}).error(function(error) {
							console.log(error);
							$scope.loading = false;
						});
			      	SweetAlert.swal("Updated!", "Company has been updated successfully", "success");
			    } else {
			      	$scope.loading = false;
			    	getCompaniesAll();
			      	SweetAlert.swal("Cancelled", "Company does not update yet", "error");
			    }
			  }); 
		}
	}
})();