(function() {
    'use strict';

    angular
        .module('geekstitch')
        .controller('OfferController', OfferController);

    OfferController.$inject = ['$http', '$routeParams', 'Chunker'];

    function OfferController($http, $routeParams, Chunker) {
        var vm = this;

        vm.offer = [];
        vm.chunkedPacks = [];

        $http.get('./ajax/offer?offer=' + $routeParams.offer)
            .then(function(res){
                vm.offer = res.data;
                vm.chunkedPacks = Chunker.getChunkedArray(vm.offer.packs, 5);
            });
    }
})();