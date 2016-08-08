(function() {
    'use strict';

    angular
        .module('geekstitch')
        .controller('CheckoutController', CheckoutController);

    CheckoutController.$inject = ['$http', 'Cart', 'ShippingType'];

    function CheckoutController($http, Cart, ShippingType) {
        var vm = this;

        vm.data = {
            shippingTypes: {},
            packImages: {}
        };

        vm.items = [];
        vm.shippingType = null;
        vm.itemCount = 0;
        vm.totalCost = 0.00;

        // Bound methods
        vm.setPackQuantity = setPackQuantity;
        vm.removeCartItem = removeCartItem;
        vm.selectShippingType = selectShippingType;

        activate();

        function activate() {
            ShippingType.list()
                .then(function(shippingTypes) {
                    vm.data.shippingTypes = shippingTypes;
                });

            refreshCartData();

            Cart.getEventScope().$on('cart:update', refreshCartData);
        }

        function refreshCartData() {
            vm.items = Cart.getItems();
            vm.shippingType = Cart.getShippingType();
            vm.itemCount = Cart.getItemCount();
            vm.totalCost = Cart.getTotalCost();

            angular.forEach(vm.items, function(cartItem) {
                var packHandle = cartItem.product.handle;

                if (!vm.data.packImages.hasOwnProperty(packHandle)) {
                    vm.data.packImages[packHandle] = null;

                    $http.get('./packs/' + packHandle + '?image-size=cart_thumbnail')
                        .then(function(res){
                            vm.data.packImages[packHandle] = res.data.image;
                        });
                }
            });
        }

        function removeCartItem(packHandle) {
            Cart.removePack(packHandle);
        }

        function setPackQuantity(packId, quantity) {
            Cart.setPackCount(packId, quantity);
        }

        function selectShippingType(handle) {
            Cart.setShippingType(handle);
        }
    }
})();