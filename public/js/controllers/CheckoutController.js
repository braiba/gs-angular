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

                    if (vm.shippingTypes[$rootScope.cart.shipping]) {
                        $rootScope.cart.shippingCost = vm.shippingTypes[vm.cart.shipping].cost;
                    }
                });

            $rootScope.$watch('cart.shipping', function (shipping) {
                if (vm.shippingTypes[shipping]) {
                    $rootScope.cart.shippingCost = vm.shippingTypes[shipping].cost;
                }
            });
        }

        function removeCartItem(cartItemIndex) {
            $rootScope.cart.items.splice(cartItemIndex, 1);
        }

        function selectShippingType(handle) {
            $rootScope.cart.shipping = vm.shippingTypes[handle];
        }
    }
})();