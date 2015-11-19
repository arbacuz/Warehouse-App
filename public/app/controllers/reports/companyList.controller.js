(function() {
	'use strict';

	angular
			.module('app')
			.controller('companyListCtrl', companyListCtrl);

	companyListCtrl.$inject = ['$state', '$cookieStore', '$stateParams', 'SweetAlert', 'CompanyServices'];

	function companyListCtrl($state, $cookieStore, $stateParams, SweetAlert, CompanyServices) {
		var vm = this;

		// Var Init
		vm.loading = false;
		vm.companies = [];

		// Func Init
		vm.addCompany = addCompany;
		vm.updateCompany = updateCompany;

		// Run
		isLogin();
		getCompaniesAll();
		getCompanyTypesAll();

		function isLogin() {
			vm.user = $cookieStore.get('user');
			if(!vm.user) {
				$state.go('member');
			}
		}
		
		function getCompaniesAll() {
			vm.loading = true;
			CompanyServices.getCompaniesAll()
				.success(function(data) {
					vm.companies = data.data;
					vm.loading = false;
				}).error(function(error) {
					vm.loading = false;
				});
		}

		function getCompanyTypesAll() {
			CompanyServices.getCompanyTypesAll()
				.success(function(data) {
					vm.companiesType = data.data;
				}).error(function(error){
					console.log(error);
				})
		}

		function addCompany(company) {
			vm.loading = true;
			CompanyServices.addCompany(company)
				.success(function(data) {
					if(data.status == "success") {
						getCompaniesAll();
					}
					vm.loading = false;
				}).error(function(error) {
					console.log(error);
					vm.loading = false;
				})
			vm.newCompany = "";
		}

		function updateCompany(company) {
			vm.loading = true;
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
							vm.loading = false;
						}).error(function(error) {
							console.log(error);
							vm.loading = false;
						});
			      	SweetAlert.swal("Updated!", "Company has been updated successfully", "success");
			    } else {
			      	vm.loading = false;
			    	getCompaniesAll();
			      	SweetAlert.swal("Cancelled", "Company does not update yet", "error");
			    }
			  }); 
		}
	}
})();