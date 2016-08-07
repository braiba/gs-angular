(function() {
    'use strict';

    angular
        .module('geekstitch')
        .controller('CheckoutController', CheckoutController);

    CheckoutController.$inject = ['$http', '$rootScope'];

    function CheckoutController($http, $rootScope) {
        var vm = this;

        vm.data = {
            shippingTypes: {}
        };

        // Bound methods
        vm.removeCartItem = removeCartItem;
        vm.selectShippingType = selectShippingType;

        activate();

        function activate() {
            $http.get('./shipping-types')
                .then(function (res) {
                    vm.data.shippingTypes = res.data;

                    var shippingHandle = $rootScope.cart.shipping.handle;
                    if (vm.data.shippingTypes[shippingHandle]) {
                        $rootScope.cart.shippingCost = vm.data.shippingTypes[shippingHandle].cost;
                    }

                    $rootScope.$watch('cart.shipping', function (shipping) {
                        var shippingHandle = shipping.handle;
                        if (vm.data.shippingTypes[shippingHandle]) {
                            $rootScope.cart.shippingCost = vm.data.shippingTypes[shippingHandle].cost;
                        }
                    });
                });
        }

        function removeCartItem(cartItemIndex) {
            $rootScope.cart.items.splice(cartItemIndex, 1);
        }

        function selectShippingType(handle) {
            $rootScope.cart.shipping = vm.data.shippingTypes[handle];
        }
    }
})();