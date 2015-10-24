(function() {
	'use strict';

	/**
	* @desc 
	* @example <div shop-with-item-list></div>
	*/
	angular
			.module('app')
	    .directive('shopWithItemList', shopWithItemList);

	function shopWithItemList() {
    var directive = {
	    templateUrl: 'app/components/shopWithItemList.directive.html'
    };

    return directive;
	}

})();