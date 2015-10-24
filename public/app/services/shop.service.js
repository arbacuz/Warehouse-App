(function() {
	'use strict';
	
	angular
			.module('app')
			.factory('ShopServices', ShopServices);

	ShopServices.$inject = ['$q', '$http', '$rootScope'];
	
	function ShopServices($q, $http, $rootScope) {
		var shopServices = {
			saveShop					: saveShop,
			shopDestroy				: shopDestroy,
			shopCount					: shopCount,
			itemCount					: itemCount,
			createShop				: createShop,
			searchShop				: searchShop,
			getShopAll				: getShopAll,
			getShopInfo				: getShopInfo,
			getShopByUser			: getShopByUser,
			getShopWithItems	: getShopWithItems,
			getItemAll				: getItemAll,
			getItemByShop			: getItemByShop,
			getPages 					: getPages,
			getCategory				: getCategory,
			fileUpload 				: fileUpload,
			instagramGetUser	: instagramGetUser
		};
		return shopServices;

		function saveShop(fb_token, shopData) {
			var defer = $q.defer();
			Parse.Cloud.run('saveShop', {
												'shop_id'					: shopData.id,
												'fb_token'				: fb_token,
												'page_id'					: shopData.attributes.fb_page_id,
												'fb_page_name'		: shopData.attributes.fb_page_name,
												'fb_page_token'		: shopData.attributes.fb_page_token,
												'ig_id'						: shopData.attributes.ig_id,
												'ig_access_token'	: shopData.attributes.ig_access_token,
												'name'						: shopData.attributes.name,
												'about'						: shopData.attributes.description 
											}, {
			  success: function(shop) {
			  	var sub_categories = [false, false, false, false, false, false, false, false, false, false, false, false];
			  	var sub_categories_length = 12;
			  	var Category = Parse.Object.extend("Category");
					var category = new Category();
					if(shopData.attributes.category_id.id) {
						category.id = shopData.attributes.category_id.id;
						shop.set("category_id", category);
					}
					if(!shopData.attributes.ig_access_token) {
						shop.set("ig_access_token", "");
					}
					if(shopData.attributes.profile_image_url) {
						shop.set("profile_image_url", shopData.attributes.profile_image_url);
					}
					if(shopData.attributes.line_qr_img_url) {
						shop.set("line_qr_img_url", shopData.attributes.line_qr_img_url);
					}
					for(var i = 0; i < sub_categories_length; i++) {
						if(shopData.attributes.sub_categories) {
							if(shopData.attributes.sub_categories[i]) {
								sub_categories[i] = true;
							}
						} else {
							sub_categories[i] = false;
						}
					}
					shop.set("sub_categories", sub_categories);
					shop.set("line_id", shopData.attributes.line_id);

					shop.save(null, {
						success: function(shop) {
							defer.resolve(shop);
						},
						error: function(shop, error) {
							console.log(error);
							defer.reject(error);
						}
					});
			  },
			  error: function(error) {
					console.log(error);
					defer.reject(error);
			  }
			});
			return defer.promise;
		}
		
		function shopDestroy(shopId) {
			$rootScope.loading = true;
			var defer = $q.defer();
			var Shop = Parse.Object.extend("Shop");
			var query = new Parse.Query(Shop);
			query.get(shopId, {
				success: function(shop) {
					shop.destroy({
						success: function(shop) {
							defer.resolve(shop);
							$rootScope.loading = false;
						},
						error: function(shop, error) {
							alert(error);
							console.log("shopDestroy: " + error.code + " Reason: " + error.messages);
							defer.reject(error);
							$rootScope.loading = false;
						}
					});
				},
				error: function(shop, error) {
					console.log(error);
					defer.reject(error);
					$rootScope.loading = false;
				}
			});
			return defer.promise;
		}

		function shopCount(limit, category_id) {
			$rootScope.loading = true;
			var defer = $q.defer();
			Parse.Cloud.run('searchShop', {
												'category_id'		: category_id
											},{
				success: function(shops) {
					var count = shops.length;
					var pages = Math.ceil(count / limit);
					$rootScope.loading = false;
					defer.resolve(pages);
				},
				error: function(error) {
					$rootScope.loading 	= false;
					console.log(error);
					defer.reject(error);
				}
			});
			return defer.promise;
		}

		function itemCount(shopId, itemPerPage) {
			$rootScope.loading = true;
			var defer = $q.defer();
			var Item = Parse.Object.extend("Item");
			var Shop = Parse.Object.extend("Shop");
			var shop = new Shop();
			var query = new Parse.Query(Item);
			shop.id = shopId;
			query.equalTo("shop_id", shop);
			query.count({
				success: function(count){
					var pages = Math.ceil(count / itemPerPage);
					$rootScope.loading = false;
					defer.resolve(pages);
				},
				error: function(error) {
					$rootScope.loading = false;
					console.log(error);
					defer.reject(error);
				}
			});
			return defer.promise;
		}

		function createShop(fb_token, shopData) {
			var defer = $q.defer();
			console.log(shopData);
			if(!shopData.attributes.pageData) {
				shopData.attributes.pageData = {};
				shopData.attributes.pageData.id = "";
			}
			Parse.Cloud.run('saveShop', {
												'fb_token'				: fb_token,
												'page_id'					: shopData.attributes.fb_page_id,
												'fb_page_name'		: shopData.attributes.fb_page_name,
												'fb_page_token'		: shopData.attributes.fb_page_token,
												'ig_id'						: shopData.attributes.ig_id,
												'ig_access_token'	: shopData.attributes.ig_access_token,
												'name'						: shopData.attributes.name,
												'about'						: shopData.attributes.description 
											}, {
			  success: function(shop) {
			  	var sub_categories = [false, false, false, false, false, false, false, false, false, false, false, false];
			  	var sub_categories_length = 12;
			  	var Category = Parse.Object.extend("Category");
					var category = new Category();
					if(shopData.attributes.category_id.id) {
						category.id = shopData.attributes.category_id.id;
						shop.set("category_id", category);
					}
					if(shopData.attributes.profile_image_url) {
						shop.set("profile_image_url", shopData.attributes.profile_image_url);
					}
					if(shopData.attributes.line_qr_img_url) {
						shop.set("line_qr_img_url", shopData.attributes.line_qr_img_url);
					}
					for(var i = 0; i < sub_categories_length; i++) {
						if(shopData.attributes.sub_categories) {
							if(shopData.attributes.sub_categories[i]) {
								sub_categories[i] = true;
							}
						} else {
							sub_categories[i] = false;
						}
					}
					shop.set("sub_categories", sub_categories);
					shop.set("line_id", shopData.attributes.line_id);

					shop.save(null, {
						success: function(shop) {
							defer.resolve(shop);
						},
						error: function(shop, error) {
							console.log(error);
							defer.reject(error);
						}
					});
			  },
			  error: function(error) {
			  	console.log(error);
			  	defer.reject(error);
			  }
			});
			return defer.promise;
		}

		function searchShop(limit, page, category_id) {
			$rootScope.loading = true;
			var defer = $q.defer();
			var shopData = [];
			var Item = Parse.Object.extend("Item");
			Parse.Cloud.run('searchShop', {
												'limit'					: limit,
												'page'					: page,
												'category_id'		: category_id
											}, {
			  success: function(shops) {
					for(var i = 0; i < shops.length; i++) {
						shopData.push(shops[i]);
						shopData[i].itemData = [];
						var queryItem = new Parse.Query(Item);
						queryItem.exists("image_url");
						queryItem.equalTo("shop_id", shopData[i]);
						queryItem.limit(20);
						queryItem.find({
							success: function(items) {
								for(var j = 0; j < shopData.length; j++) {
									for(var k = 0; k < items.length; k++) {
										if(items[k].attributes.shop_id.id == shopData[j].id) {
											shopData[j].itemData.push(items[k]);
										}
									}
									$rootScope.loading = false;
									defer.resolve(shopData);
								}
							},
							error: function(items, error) {
								$rootScope.loading = false;
								alert(error);
								console.log(error);
								defer.reject(error);
							}
						});
					}
				},
			  error: function(error) {
			  	$rootScope.loading 	= false;
			  	console.log(error);
			  	defer.reject(error);
			  }
			});
			return defer.promise;
		}

		function getShopAll() {
			$rootScope.loading = true;
			var defer = $q.defer();
			var Shop = Parse.Object.extend("Shop");
			var query = new Parse.Query(Shop);
			query.find({
				success: function(shop) {
					$rootScope.loading = false;
					defer.resolve(shop);
				},
				error: function(shop, error) {
					$rootScope.loading = false;
					console.log(error);
					defer.reject(error);
				}
			});
			return defer.promise;
		}

		function getShopInfo(shopId) {
			$rootScope.loading = true;
			var defer = $q.defer();
			var Shop = Parse.Object.extend("Shop");
			var query = new Parse.Query(Shop);
			query.get(shopId, {
				success: function(shop) {
					$rootScope.loading = false;
					defer.resolve(shop);
				},
				error: function(shop, error) {
					$rootScope.loading = false;
					console.log(error);
					defer.reject(error);
				}
			});
			return defer.promise;
		}	
		
		function getShopByUser(user) {
			var defer = $q.defer();
			var Shop = Parse.Object.extend("Shop");
			var query = new Parse.Query(Shop);
			query.equalTo("owner_id", user);
			query.find({
				success: function(shops) {
					defer.resolve(shops);
				},
				error: function(shops, error) {
					console.log(error);
					defer.reject(error);
				}
			});
			return defer.promise;
		}

		function getShopWithItems() {
			$rootScope.loading = true;
			var defer = $q.defer();
			var shopData = [];
			var Shop = Parse.Object.extend("Shop");
			var Item = Parse.Object.extend("Item");
			var query = new Parse.Query(Shop);
			query.find({
				success: function(shops){
					for(var i = 0; i < shops.length; i++) {
						shopData.push(shops[i]);
						shopData[i].itemData = [];
						var queryItem = new Parse.Query(Item);
						queryItem.exists("image_url");
						queryItem.equalTo("shop_id", shopData[i]);
						queryItem.limit(20);
						queryItem.find({
							success: function(items) {
								for(var j = 0; j < shopData.length; j++) {
									for(var k = 0; k < items.length; k++) {
										if(items[k].attributes.shop_id.id == shopData[j].id) {
											shopData[j].itemData.push(items[k]);
										}
									}
									$rootScope.loading = false;
									defer.resolve(shopData);
								}
							},
							error: function(items, error) {
								$rootScope.loading = false;
								console.log(error);
								defer.reject(error);
							}
						});
					}
				},
				error: function(error) {
					$rootScope.loading = false;
					console.log(error);
					defer.reject(error);
				}
			});
			return defer.promise;
		}

		function getItemAll() {
			$rootScope.loading = true;
			var defer = $q.defer();
			var Item = Parse.Object.extend("Item");
			var query = new Parse.Query(Item);
			query.exists("image_url");
			query.include("shop_id");
			query.find({
				success: function(items) {
					$rootScope.loading = false;
					defer.resolve(items);
				},
				error: function(error) {
					$rootScope.loading = false;
					console.log(error);
					defer.reject(error);
				}
			});
			return defer.promise;
		}

		function getItemByShop(shopId, skip) {
			$rootScope.loading = true;
			var defer = $q.defer();
			var Item = Parse.Object.extend("Item");
			var Shop = Parse.Object.extend("Shop");
			var shop = new Shop();
			var query = new Parse.Query(Item);
			shop.id = shopId;
			query.exists("image_url");
			query.equalTo("shop_id", shop);
			query.skip(skip);
			query.limit(20);
			query.find({
				success: function(items) {
					$rootScope.loading = false;
					defer.resolve(items);
				},
				error: function(items, error) {
					$rootScope.loading = false;
					console.log(error);
					defer.reject(error);
				}
			});
			return defer.promise;
		}

		function getCategory() {
			var defer = $q.defer();
			Parse.Cloud.run('getCategory', {
											},{
				success: function(categories) {
					defer.resolve(categories);
				},
				error: function(error) {
					console.log(error);
					defer.reject(error);
				}
			});
			return defer.promise;
		}

		function getPages(fb_token) {
			var defer = $q.defer();
			Parse.Cloud.run('facebookGetPages', {
												'fb_token'	: fb_token
											},{
			  success: function(shops) {
			    defer.resolve(shops.data);
			  },
			  error: function(error) {
			  	console.log(error);
			  	defer.reject(error);
			  }
			});
			return defer.promise;
		}

		function fileUpload(files) {
			var defer = $q.defer();
			if(files.length > 0) {
				var fileForUpload = files[0];
				var name = "image.jpg";
				var parseFile = new Parse.File(name, fileForUpload);
				parseFile.save({
					success:function(file) {
						defer.resolve(file);
					},
					error: function(error) {
						console.log(error);
						defer.reject(error);
					}
				});
			} else {
				defer.reject("no file");
			}
			return defer.promise;
		}

		function instagramGetUser(ig_access_token) {
			var defer = $q.defer();
			$http.get('https://api.instagram.com/v1/users/self?access_token='+ig_access_token)
				.then(function(data) {
					console.log(data);
					defer.resolve(data);
				},
				function(error) {
					console.log(error);
					defer.reject(error);
				});
			return defer.promise;
		}

	}

})();