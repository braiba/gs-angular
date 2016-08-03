(function() {
    'use strict';

    var dependencies = [
        'ngRoute',
        'ngSanitize'
    ];

    angular
        .module('geekstitch', dependencies)
        .config(config)
        .run(run);

    config.$inject = ['$routeProvider'];

    function config($routeProvider) {
        $routeProvider
            .when('/', {templateUrl: 'views/index.html'})
            .when('/checkout', {
                templateUrl: 'views/checkout.html',
                controller: 'CheckoutController',
                controllerAs: 'vm'
            })
            .when('/contact-us', {templateUrl: 'views/contact-us.html'})
            .when('/faq', {templateUrl: 'views/faq.html'})
            .when('/categories/:category', {
                templateUrl: 'views/category.html',
                controller: 'CategoryController',
                controllerAs: 'vm',
                resolve: {
                    category: resolveCategory
                }
            })
            .when('/fandoms', {
                templateUrl: 'views/category-type.html',
                controller: 'CategoryTypeController',
                controllerAs: 'vm',
                resolve: {
                    categoryType: resolveCategoryTypeFandoms
                }
            })
            .when('/fandoms/:category', {
                templateUrl: 'views/category.html',
                controller: 'CategoryController',
                controllerAs: 'vm',
                resolve: {
                    category: resolveCategory
                }
            })
            .when('/genres', {
                templateUrl: 'views/category-type.html',
                controller: 'CategoryTypeController',
                controllerAs: 'vm',
                resolve: {
                    categoryType: resolveCategoryTypeGenres
                }
            })
            .when('/genres/:category', {
                templateUrl: 'views/category.html',
                controller: 'CategoryController',
                controllerAs: 'vm',
                resolve: {
                    category: resolveCategory
                }
            })
            .when('/packs/:pack', {
                templateUrl: 'views/pack.html',
                controller: 'PackController',
                controllerAs: 'vm',
                resolve: {
                    pack: resolvePack
                }
            })
            .when('/offers/:offer', {
                templateUrl: 'views/offer.html',
                controller: 'OfferController',
                controllerAs: 'vm',
                resolve: {
                    offer: resolveOffer
                }
            })
            .otherwise({templateUrl: 'views/not-found.html'});
    }

    run.$inject = ['$rootScope', '$http'];

    function run($rootScope, $http) {
        $rootScope.cart = {
            "items": [],
            "itemTotal": 0,
            "shipping": {
                "cost": 0
            },
            "shippingCost": 0,
            "count": 0,
            "total": 0
        };

        $http.get('./ajax/cart-info?image-size=cart_thumbnail')
            .then(function(res){
                $rootScope.cart = res.data;
            });

        $rootScope.$watch(
            'cart.items',
            function() {
                var itemTotal = 0;
                var itemCount = 0;
                angular.forEach($rootScope.cart.items, function(item) {
                    itemTotal += item.quantity * item.product.price;
                    itemCount += item.quantity;
                });
                $rootScope.cart.count = itemCount;
                $rootScope.cart.itemTotal = itemTotal;
            },
            true
        );

        $rootScope.$watchGroup(
            ['cart.itemTotal', 'cart.shipping'],
            function() {
                $rootScope.cart.total = $rootScope.cart.itemTotal + $rootScope.cart.shipping.cost;
            }
        );
    }

    resolveCategory.$inject = ['$http', '$route'];

    function resolveCategory($http, $route) {
        return $http.get('./ajax/categories/' + $route.current.params.category + '?image-size=browse_thumbnail')
            .then(function(res){
                return res.data;
            });
    }

    resolveCategoryTypeFandoms.$inject = ['$http'];

    function resolveCategoryTypeFandoms($http) {
        return $http.get('./ajax/fandoms?image-size=browse_thumbnail')
            .then(function(res){
                return res.data;
            });
    }

    resolveCategoryTypeGenres.$inject = ['$http'];

    function resolveCategoryTypeGenres($http) {
        return $http.get('./ajax/genres?image-size=browse_thumbnail')
            .then(function(res){
                return res.data;
            });
    }

    resolveOffer.$inject = ['$http', '$route'];

    function resolveOffer($http, $route) {
        return $http.get('./ajax/offers/' + $route.current.params.offer + '?image-size=offer_thumbnail')
            .then(function(res){
                return res.data;
            });
    }

    resolvePack.$inject = ['$http', '$route'];

    function resolvePack($http, $route) {
        return $http.get('./ajax/packs/' + $route.current.params.pack + '?image-size=pack_page_main')
            .then(function(res){
                return res.data;
            });
    }
})();
