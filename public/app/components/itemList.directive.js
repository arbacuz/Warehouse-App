(function() {
	'use strict';

	/**
	* @desc 
	* @example <div item-list></div>
	*/
	angular
			.module('app')
	    .directive('itemList', itemList);

	function itemList() {
    var directive = {
	    templateUrl: 'app/components/itemList.directive.html'
    };

    return directive;
	}	

})();