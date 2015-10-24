(function() {
	'use strict';

	angular
			.module('app')
			.factory('UserServices', UserServices);

	UserServices.$inject = ['$q'];

	function UserServices($q) {
		var userServices = {
			loginFacebook					: loginFacebook,
			loginUser							: loginUser,
			logOut								: logOut,
			registerUser					: registerUser,
			isLogin								: isLogin,
			requestPasswordReset	: requestPasswordReset,
			getUser								: getUser,
			editUser							: editUser
		};
		return userServices;
		
		function loginFacebook() {
			var current 	= Parse.User.current();
			var defer 		= $q.defer();
			if(!current) {
				Parse.FacebookUtils.logIn("public_profile,manage_pages", {
				  success: function(user) {
				  	if(!user.attributes.name) {
				  		FB.api(
						    "/me",
						    function (response) {
						      if (response && !response.error) {
						      	console.log(response.name);
						        user.set("name", response.name);
						        user.save(null, {
											success: function(userSaved) {
												defer.resolve(userSaved);
											},
											error: function(userSaved, error) {
												defer.reject(error);
											}
										});
						      } else {
						      	defer.reject("no username set");
						      }
						    }
							);
				  	} else {
							defer.resolve(user);
				  	}
				  },
				  error: function(user, error) {
				    alert("User cancelled the Facebook login or did not fully authorize.");
						defer.reject(error);
				  }
				});
			} else {
				if (!Parse.FacebookUtils.isLinked(current)) {
				  Parse.FacebookUtils.link(current, "public_profile,manage_pages", {
				    success: function(user) {
				      defer.resolve(user);
				    },
				    error: function(user, error) {
				      defer.reject(error);
				      alert(error.message);
				    }
				  });
				} else {
					defer.resolve(current);
				}
			}
			return defer.promise;
		}

		function loginUser(userData) {
			var defer = $q.defer();
			Parse.User.logIn(userData.email, userData.password,{
				success: function(user) {
					defer.resolve(user);
				},
				error: function(user, error){
					alert(error.message);
					defer.reject(error);
				}
			});
			return defer.promise;
		}

		function logOut() {
			Parse.User.logOut();
		}
		
		function registerUser(userData) {
			var defer 			= $q.defer();
			var user 				= new Parse.User();
			if(userData.name) {
				user.set("name", userData.name);
			} else {
				user.set("name", userData.email);
			}
			user.set("username", userData.email);
			user.set("email", userData.email);
			user.set("password", userData.password);
			user.set("phone", userData.phone);
			
			user.signUp(null, {
				success: function(user) {
					defer.resolve(user);
				},
				error: function(user, error) {
					alert(error.message);
					defer.reject(error);
				}
			});
			return defer.promise;
		}

		function isLogin() {
			var currentUser 	= Parse.User.current();
			var data 					= {};
			if(currentUser) {
				data = {
					user 			: currentUser,
					status		: true
				};
			} else {
				console.log('Redirect, user does not login.');
				data = {
					user 			: null,
					status 		: false
				};
			}
			return data;
		}

		function requestPasswordReset(userData) {
			Parse.User.requestPasswordReset(userData.email, {
				success: function() {
					console.log("Password reset request was sent.");
				},
				error: function(error) {
					alert(error.message);
					console.log(error);
				}
			});
		}

		function getUser() {
			var defer 	= $q.defer();
			var user 		= Parse.User.current();
			if(!user) {
				user = {attributes:""};
				defer.resolve(user);
			} else {
				// defer.resolve(user);
				var query = new Parse.Query(Parse.User);
				query.get(user.id, {
					success: function(user) {
						defer.resolve(user);
					},
					error: function(user, error) {
						// alert(error.message);
						defer.reject(error);
					}
				});
			}
			return defer.promise;
		}

		function editUser(userData) {
			console.log(userData);
			var defer 				= $q.defer();
			var currentUser 	= Parse.User.current();
			console.log(currentUser);
			var query 				= new Parse.Query(Parse.User);
			query.get(currentUser.id, {
				success: function(user) {
					user.set("name", userData.name);
					user.set("phone", userData.phone);
					user.set("address", userData.address);
					user.set("personId", userData.personId);
					user.save(null, {
						success: function(userSaved) {
							defer.resolve(userSaved);
						},
						error: function(userSaved, error) {
							defer.reject(error);
						}
					});
				},
				error: function(user, error) {
					alert(error.message);
					defer.reject(error);
				}
			});
			return defer.promise;
		}
	}
})();

