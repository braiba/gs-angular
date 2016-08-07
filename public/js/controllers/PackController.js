(function() {
    'use strict';

    angular
        .module('geekstitch')
        .controller('PackController', PackController);

    PackController.$inject = ['Cart', 'pack'];

    function PackController(Cart, pack) {
        var vm = this;

        vm.data = {
            pack: pack
        };

        vm.inCartQuantity = 0;

        // Bound methods
        vm.addToCartAction = addToCartAction;

        activate();

        function activate() {
            onCartItemsUpdate();

            Cart.getEventScope().$on('cart:update', onCartItemsUpdate);
        }

        function onCartItemsUpdate(cartItems) {
            Cart.getPackCount(vm.data.pack.handle);
        }

        function addToCartAction() {
            Cart.addPack(vm.data.pack);
        }
    }
})();