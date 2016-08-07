(function() {
    'use strict';

    angular
        .module('geekstitch')
        .directive('gsHeaderCartInfo', HeaderCartInfo);

    function HeaderCartInfo() {
        return {
            restrict: 'E',
            templateUrl: 'views/templates/header-cart-info.html',
            scope: {
                categoryType: '@'
            },
            controller: HeaderCartInfoController,
            controllerAs: 'vm',
            bindToController: true
        };
    }

    HeaderCartInfoController.$inject = ['Cart'];

    function HeaderCartInfoController(Cart) {
        var vm = this;

        vm.itemCount = 0;

        activate();

        function activate() {
            refreshItemCount();

            Cart.getEventScope().$on('cart:update', refreshItemCount);
        }

        function refreshItemCount() {
            vm.itemCount = Cart.getItemCount();
        }
    }
})();