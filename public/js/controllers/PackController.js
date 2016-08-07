(function() {
    'use strict';

    angular
        .module('geekstitch')
        .controller('PackController', PackController);

    PackController.$inject = ['$rootScope', 'pack'];

    function PackController($rootScope, pack) {
        var vm = this;

        vm.data = {
            pack: pack
        };

        vm.inCartQuantity = 0;

        activate();

        function activate() {
            onCartItemsUpdate($rootScope.cart.items);

            $rootScope.$watch('cart.items', onCartItemsUpdate);
        }

        function onCartItemsUpdate(cartItems) {
            cartItems.some(function(cartItem) {
                if (cartItem.product.id === pack.id) {
                    vm.inCartQuantity = cartItem.quantity;
                    return true;
                }

                return false;
            });
        }
    }
})();