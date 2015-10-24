(function() {
	'use strict';

	angular
			.module('app')
			.filter('stringSplit', stringSplit);

	function stringSplit() {
		return function (input, limit) {
			if(!limit) limit = 3;
			if(!input) return input;
			if(input.length < limit) {
				return input;
			} 
			var stringSpliting = input.split("",limit);
			var splitLength = stringSpliting.length;
			var splited = "";
			for(var i = 0;i<splitLength;i++) {
				splited += stringSpliting[i]; 
			}
			return splited += "...";
		}
	}

})();