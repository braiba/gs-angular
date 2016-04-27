var GeekStitch = GeekStitch || {};

GeekStitch.helpers = {
    getChunked: function(array, chunkSize) {
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
    }
};

angular
    .module('geekstitch', ['ngSanitize'])
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
                angular.forEach($rootScope.cart.items, function(value) {
                   itemTotal += value.quantity * value.product.price;
                });
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

    }]).controller('GenreListController', ['$scope', '$http', function($scope, $http) {
        $scope.genreMap = {};
        $scope.chunkedGenres = [];

        $http.get('./dummy/genres.json')
            .then(function(res){
                $scope.genreMap = res.data;
                $scope.chunkedGenres = GeekStitch.helpers.getChunkedMap(res.data, 3);
            });
    }]).controller('FandomListController', ['$scope', '$http', function($scope, $http) {
        $scope.fandomMap = {};
        $scope.chunkedFandoms = [];

        $http.get('./dummy/fandoms.json')
            .then(function(res){
                $scope.fandomMap = res.data;
                $scope.chunkedFandoms = GeekStitch.helpers.getChunkedMap(res.data, 3);
            });
    }]).controller('CartController', ['$scope', '$http', function($scope, $http) {
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