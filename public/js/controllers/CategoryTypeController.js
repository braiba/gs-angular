(function() {
    'use strict';

    angular
        .module('geekstitch')
        .controller('CategoryTypeController', CategoryTypeController);

    CategoryTypeController.$inject = ['categoryType', 'Chunker', ];

    function CategoryTypeController(categoryType, Chunker) {
        var vm = this;

        vm.data = {
            categoryType: categoryType,
            chunkedCategories: Chunker.getChunkedArray(categoryType.categories, 5)
        };
    }
})();