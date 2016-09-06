(function() {
    'use strict';

    var dependencies = [
        'ngRoute',
        'ngSanitize'
    ];

    angular
        .module('geekstitch', dependencies)
        .config(config);

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
            .when('/sorry', {templateUrl: 'views/sorry.html'})
            .when('/thank-you', {templateUrl: 'views/thank-you.html'})
            .otherwise({templateUrl: 'views/not-found.html'});
    }

    resolveCategory.$inject = ['$http', '$route'];

    function resolveCategory($http, $route) {
        return $http.get('./categories/' + $route.current.params.category + '?image-size=browse_thumbnail')
            .then(function(res){
                return res.data;
            });
    }

    resolveCategoryTypeFandoms.$inject = ['$http'];

    function resolveCategoryTypeFandoms($http) {
        return $http.get('./fandoms?image-size=browse_thumbnail')
            .then(function(res){
                return res.data;
            });
    }

    resolveCategoryTypeGenres.$inject = ['$http'];

    function resolveCategoryTypeGenres($http) {
        return $http.get('./genres?image-size=browse_thumbnail')
            .then(function(res){
                return res.data;
            });
    }

    resolveOffer.$inject = ['$http', '$route'];

    function resolveOffer($http, $route) {
        return $http.get('./offers/' + $route.current.params.offer + '?image-size=offer_thumbnail')
            .then(function(res){
                return res.data;
            });
    }

    resolvePack.$inject = ['$http', '$route'];

    function resolvePack($http, $route) {
        return $http.get('./packs/' + $route.current.params.pack + '?image-size=pack_page_main')
            .then(function(res){
                return res.data;
            });
    }
})();
