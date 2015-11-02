(function() {
	'use strict';
	
	angular
			.module('app')
			.factory('CompanyServices', CompanyServices);

	CompanyServices.$inject = ['$q', '$http', '$rootScope'];
	
	function CompanyServices($q, $http, $rootScope) {
		var urlBase = "http://localhost:8888/warehouse-proj/Warehouse-App/public/";
		var companyServices = {
			addCompany: 		addCompany,
			updateCompany: 		updateCompany,
			deleteCompany: 		deleteCompany,
			getCompaniesAll: 	getCompaniesAll,
			getCompany: 		getCompany,
			addCompanyType: 	addCompanyType,
			updateCompanyType: 	updateCompanyType,
			deleteCompanyType: 	deleteCompanyType,
			getCompanyType: 	getCompanyType
		};
		return companyServices;

		function addCompany(company) {

		}

		function updateCompany(company) {

		}

		function deleteCompany(company) {

		}

		function getCompaniesAll() {

		}

		function getCompany(company) {

		}

		function addCompanyType() {

		}

		function updateCompanyType(type) {

		}

		function deleteCompanyType(type) {

		}

		function getCompanyType() {

		}


	}

})();