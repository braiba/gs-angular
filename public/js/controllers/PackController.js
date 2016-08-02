(function() {
    'use strict';

    angular
        .module('geekstitch')
        .controller('PackController', PackController);

    PackController.$inject = ['pack'];

    function PackController(pack) {
        var vm = this;

        vm.data = {
            pack: pack
        };
    }
})();