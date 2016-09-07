(function() {
    'use strict';

    angular
        .module('geekstitch')
        .service('Cart', Cart);

    Cart.$inject = ['$http', '$q', '$rootScope', 'ShippingType'];

    function Cart($http, $q, $rootScope, ShippingType) {
        var vm = this;

        vm.eventScope = $rootScope.$new(true);

        vm.data = {
            shippingTypes: {}
        };

        vm.cart = {
            items: {},
            shippingType: null
        };

        var itemCount = 0;
        var totalItemCost = 0;
        var shippingCost = 0;
        var totalCost = 0;

        init();

        return {
            addPack: addPack,
            removePack: removePack,
            getPackCount: getPackCount,
            setPackCount: setPackCount,
            getItems: getItems,
            getItemCount: getItemCount,
            getTotalItemCost: getTotalItemCost,
            getShippingType: getShippingType,
            setShippingType: setShippingType,
            getShippingCost: getShippingCost,
            getTotalCost: getTotalCost,
            getEventScope: getEventScope
        };

        function init() {
            var cartDataPromise = $http.get('./cart')
                .then(function(response) {
                    return response.data;
                });

            var promises = {
                cartData: cartDataPromise,
                shippingTypes: ShippingType.list()
            };

            $q.all(promises)
                .then(function(results) {
                    angular.forEach(results.shippingTypes, function(shippingType) {
                        vm.data.shippingTypes[shippingType.handle] = shippingType;
                    });

                    angular.forEach(results.cartData.items, function(cartItem) {
                        vm.cart.items[cartItem.product.handle] = cartItem;
                    });

                    vm.cart.shippingType = results.cartData.shippingType;

                    calculateMetadata();

                    vm.eventScope.$emit('cart:update');
                });
        }

        function calculateMetadata() {
            var itemCount = 0;
            var totalItemCost = 0;

            angular.forEach(vm.cart.items, function (cartItem) {
                itemCount += cartItem.quantity;
                totalItemCost += cartItem.quantity * cartItem.product.price;
            });

            vm.itemCount = itemCount;
            vm.totalItemCost = totalItemCost;

            if (vm.data.shippingTypes.hasOwnProperty(vm.cart.shippingType)) {
                vm.shippingCost = vm.data.shippingTypes[vm.cart.shippingType].cost;
            } else {
                vm.shippingCost = 0;
            }

            vm.totalCost = vm.totalItemCost + vm.shippingCost;
        }

        function addPack(pack) {
            if (vm.cart.items.hasOwnProperty(pack.handle)) {
                vm.cart.items[pack.handle].quantity++;
            } else {
                vm.cart.items[pack.handle] = {
                    product: pack,
                    quantity: 1
                };
            }

            calculateMetadata();
            vm.eventScope.$emit('cart:update');

            var patchRequest = {
                items: [
                    {
                        product: pack.handle,
                        quantity: vm.cart.items[pack.handle].quantity
                    }
                ]
            };
            $http.patch('./cart', patchRequest);
        }

        function removePack(packHandle) {
            if (!vm.cart.items.hasOwnProperty(packHandle)) {
                return;
            }

            delete vm.cart.items[packHandle];

            calculateMetadata();
            vm.eventScope.$emit('cart:update');

            var patchRequest = {
                items: [
                    {
                        product: packHandle,
                        quantity: 0
                    }
                ]
            };
            $http.patch('./cart', patchRequest);
        }

        function setPackCount(packHandle, count) {
            if (!vm.cart.items.hasOwnProperty(packHandle)) {
                return;
            }
            if (vm.cart.items[packHandle].quantity === count) {
                return; // No change
            }

            vm.cart.items[packHandle].quantity = count;

            calculateMetadata();
            vm.eventScope.$emit('cart:update');

            var patchRequest = {
                items: [
                    {
                        product: packHandle,
                        quantity: count
                    }
                ]
            };
            $http.patch('./cart', patchRequest);
        }

        function getPackCount(packHandle) {
            if (!vm.cart.items.hasOwnProperty(packHandle)) {
                return 0;
            }

            return vm.cart.items[packHandle];
        }

        function getItems() {
            return vm.cart.items;
        }

        function getItemCount() {
            return vm.itemCount;
        }

        function getTotalItemCost() {
            return vm.totalItemCost;
        }

        function getShippingType() {
            if (!vm.data.shippingTypes.hasOwnProperty(vm.cart.shippingType)) {
                return {
                    handle: null,
                    cost: 0.00
                };
            }
            return vm.data.shippingTypes[vm.cart.shippingType];
        }

        function setShippingType(shippingType) {
            vm.cart.shippingType = shippingType;

            if (vm.data.shippingTypes.hasOwnProperty(vm.cart.shippingType)) {
                vm.shippingCost = vm.data.shippingTypes[vm.cart.shippingType].cost;
            } else {
                vm.shippingCost = 0;
            }

            vm.totalCost = vm.totalItemCost + vm.shippingCost;

            vm.eventScope.$emit('cart:update');

            var patchRequest = {
                shippingType: shippingType
            };
            $http.patch('./cart', patchRequest);
        }

        function getShippingCost() {
            return vm.shippingCost;
        }

        function getTotalCost() {
            return vm.totalCost;
        }

        function getEventScope() {
            return vm.eventScope;
        }
    }
})();