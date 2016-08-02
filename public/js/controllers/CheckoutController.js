(function() {
    'use strict';

    angular
        .module('geekstitch')
        .controller('CheckoutController', CheckoutController);

    CheckoutController.$inject = ['$http', '$rootScope'];

    function CheckoutController($http, $rootScope) {
        var vm = this;

        vm.shippingTypes = [];

        vm.selectShippingType = selectShippingType;

        activate();

        function activate() {
            $http.get('./ajax/shipping-types')
                .then(function (res) {
                    vm.shippingTypes = res.data;

                    if (vm.shippingTypes[vm.cart.shipping]) {
                        vm.cart.shippingCost = vm.shippingTypes[vm.cart.shipping].cost;
                    }
                });

            vm.$watch('cart.shipping', function (shipping) {
                if (vm.shippingTypes[shipping]) {
                    vm.cart.shippingCost = vm.shippingTypes[shipping].cost;
                }
            });
        }

        function selectShippingType(handle) {
            $rootScope.cart.shipping = vm.shippingTypes[handle];
        }
    }
})();