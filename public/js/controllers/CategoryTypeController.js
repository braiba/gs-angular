(function() {
    'use strict';

    angular
        .module('geekstitch')
        .controller('CategoryTypeController', CategoryTypeController);

    CategoryTypeController.$inject = ['$http', 'Chunker', 'categoryType'];

    function CategoryTypeController($http, Chunker, categoryType) {
        var vm = this;

        vm.categoryType = null;
        vm.chunkedCategories = [];

        $http.get('./ajax/' + categoryType)
            .then(function(res){
                vm.categoryType = res.data;
                vm.chunkedCategories = Chunker.getChunkedArray(vm.categoryType.categories, 5);
            });
    }
})();