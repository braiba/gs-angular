(function() {
    'use strict';

    angular
        .module('geekstitch')
        .controller('OfferController', OfferController);

    OfferController.$inject = ['offer', 'Chunker'];

    function OfferController(offer, Chunker) {
        var vm = this;

        vm.data = {
            offer: offer,
            chunkedPacks: Chunker.getChunkedArray(offer.packs, 5)
        };
    }
})();