(function() {
    'use strict';

    angular
        .module('geekstitch')
        .controller('CategoryController', CategoryController);

    CategoryController.$inject = ['category', 'Chunker'];

    function CategoryController(category, Chunker) {
        var vm = this;

        vm.category = category;
        vm.chunkedPacks = Chunker.getChunkedArray(category.packs, 5);
    }
})();