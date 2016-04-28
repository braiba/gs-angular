var GeekStitch = GeekStitch || {};

GeekStitch.helpers = {
    getChunkedArray: function(array, chunkSize) {
        var chunks = [];
        for (i=0,j=array.length; i<j; i+=chunkSize) {
            chunks.push(array.slice(i,i+chunkSize));
        }
        return chunks;
    },
    getChunkedMap: function(map, chunkSize) {
        var chunks = [];
        var chunk = {};
        var chunkItemCount = 0;
        angular.forEach(map, function(value, key) {
            chunk[key] = value;
            if (++chunkItemCount == chunkSize) {
                chunks.push(chunk);
                chunk = {};
                chunkItemCount = 0;
            }
        });

        if (chunkItemCount != 0) {
            chunks.push(chunk);
        }

        return chunks;
    },
    getChunked: function(chunkable, chunkSize) {
        if (Array.isArray(chunkable)) {
            return GeekStitch.getChunkedArray(chunkable, chunkSize);
        } else {
            return GeekStitch.getChunkedMap(chunkable, chunkSize);
        }
    }
};

angular
    .module('geekstitch', ['ngSanitize', 'ngRoute'])
    .config(['$routeProvider', function($routeProvider) {
        $routeProvider
            .when('/', {templateUrl: 'views/index.html'})
            .when('/checkout', {
                templateUrl: 'views/checkout.html',
                controller: 'CheckoutController'
            })
            .when('/contact-us', {templateUrl: 'views/contact-us.html'})
            .when('/faq', {templateUrl: 'views/faq.html'})
            .when('/category/:category', {
                templateUrl: 'views/category.html',
                controller: 'CategoryController'
            })
            .when('/fandom', {templateUrl: 'views/fandom-list.html'})
            .when('/fandom/:category', {
                templateUrl: 'views/category.html',
                controller: 'CategoryController'
            })
            .when('/genre', {templateUrl: 'views/genre-list.html'})
            .when('/genre/:category', {
                templateUrl: 'views/category.html',
                controller: 'CategoryController'
            })
            .when('/pack/:pack', {
                templateUrl: 'views/pack.html',
                controller: 'PackController'
            })
            .when('/offer/:offer', {
                templateUrl: 'views/offer.html',
                controller: 'OfferController'
            })
            .when('/offer/:offer', {
                templateUrl: 'views/offer.html',
                controller: 'OfferController'
            })
            .otherwise({templateUrl: 'views/not-found.html'});
    }])
    .run(['$rootScope', '$http', function($rootScope, $http) {
        $rootScope.cart = {
            "items": [],
            "itemTotal": 0,
            "shipping": null,
            "shippingCost": 0,
            "count": 0,
            "total": 0
        };

        $http.get('./dummy/cart-info.json')
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
            ['cart.itemTotal', 'cart.shippingCost'],
            function() {
                $rootScope.cart.total = $rootScope.cart.itemTotal + $rootScope.cart.shippingCost;
            }
        );
    }])
    .controller('CartInfoController', ['$scope', '$http', function($scope, $http) {

    }]).controller('CategoryPanelController', ['$scope', '$http', '$attrs', function($scope, $http, $attrs) {
        $scope.type = $attrs.gsType;
        $scope.itemMap = {};
        $scope.chunkedItems = [];

        $http.get('./dummy/' + $scope.type + 's.json')
            .then(function(res){
                $scope.itemMap = res.data;
                $scope.chunkedItems = GeekStitch.helpers.getChunkedMap($scope.itemMap, 3);
            });
    }]).controller('CategoryController', ['$scope', '$http', '$routeParams', function($scope, $http, $routeParams) {
        $scope.category = [];
        $scope.chunkedPacks = [];

        $http.get('./dummy/category.json?category=' + $routeParams.category)
            .then(function(res){
                $scope.category = res.data;
                $scope.chunkedPacks = GeekStitch.helpers.getChunkedArray($scope.category.packs, 5);
            });
    }]).controller('PackController', ['$scope', '$http', '$routeParams', function($scope, $http, $routeParams) {
        $scope.pack = [];

        $http.get('./dummy/pack.json?pack=' + $routeParams.pack)
            .then(function(res){
                $scope.pack = res.data;
            });
    }]).controller('OfferController', ['$scope', '$http', '$routeParams', function($scope, $http, $routeParams) {
        $scope.offer = [];
        $scope.chunkedPacks = [];

        $http.get('./dummy/offer.json?offer=' + $routeParams.offer)
            .then(function(res){
                $scope.offer = res.data;
                $scope.chunkedPacks = GeekStitch.helpers.getChunkedArray($scope.offer.packs, 5);
            });
    }]).controller('CheckoutController', ['$scope', '$http', function($scope, $http) {
        $scope.shippingTypes = [];

        $http.get('./dummy/shipping-types.json')
            .then(function(res){
                $scope.shippingTypes = res.data;

                if ($scope.shippingTypes[$scope.cart.shipping]) {
                    $scope.cart.shippingCost = $scope.shippingTypes[$scope.cart.shipping].cost;
                }
            });

        $scope.$watch('cart.shipping', function(shipping) {
            if ($scope.shippingTypes[shipping]) {
                $scope.cart.shippingCost = $scope.shippingTypes[shipping].cost;
            }
        })
    }]);