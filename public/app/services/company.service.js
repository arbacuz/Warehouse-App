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
			getCompanyByTypeID: getCompanyByTypeID,
			addCompanyType: 	addCompanyType,
			updateCompanyType: 	updateCompanyType,
			deleteCompanyType: 	deleteCompanyType,
			getCompanyTypesAll: getCompanyTypesAll
		};
		return companyServices;

		function addCompany(company) {
			var data = angular.toJson({'company':company});
			return $http.post(urlBase+'api/company/addCompany.php',data);
		}

		function updateCompany(company) {
			var data = angular.toJson({'company':company});
			return $http.post(urlBase+'api/company/updateCompany.php',data);
		}

		function deleteCompany(company) {
			console.log(company);
			var data = angular.toJson({'company':company});
			return $http.post(urlBase+'api/company/deleteCompany.php',data);
		}

		function getCompaniesAll() {
			return $http.get(urlBase+'api/company/getCompaniesAll.php');
		}

		function getCompany(company) {
			var data = angular.toJson({'company':company});
			return $http.post(urlBase+'api/company/getCompany.php',data);
		}

		function getCompanyByTypeID(type) {
			var data = angular.toJson({'companyTypeID':type});
			return $http.post(urlBase+'api/company/getCompanyByTypeID.php',data);
		}

		function addCompanyType(companyType) {
			var data = angular.toJson({'companyType':companyType});
			return $http.post(urlBase+'api/company/addCompanyType.php',data);
		}

		function updateCompanyType(companyType) {
			var data = angular.toJson({'companyType':companyType});
			return $http.post(urlBase+'api/company/updateCompanyType.php',data);
		}

		function deleteCompanyType(companyType) {
			var data = angular.toJson({'companyType':companyType});
			return $http.post(urlBase+'api/company/deleteCompanyType.php',data);
		}

		function getCompanyTypesAll() {
			return $http.get(urlBase+'api/company/getCompanyTypesAll.php');
		}


	}

})();