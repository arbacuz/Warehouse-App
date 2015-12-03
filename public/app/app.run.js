(function() {
	'use strict';

	angular
			.module('app')
			.run(appRun);

	appRun.$inject = ['$rootScope','$cookieStore','$state','$timeout'];

	function appRun($rootScope,$cookieStore,$state,$timeout) {
		$rootScope.$on('$stateChangeSuccess', function(event, to, toParams, from, fromParams) {
            $rootScope.$previousState = from;
        });
		// isLogin();
		
		// function isLogin() {
		// 	$rootScope.user = $cookieStore.get('user');
		// 	console.log($rootScope.user);
		// 	if(!$rootScope.user) {
		// 		var transition = $timeout(function() {
	 //                $state.go("member");
	 //            });
		// 	}
		// }
	}
})();