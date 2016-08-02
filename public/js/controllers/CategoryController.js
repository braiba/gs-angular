(function() {
    'use strict';

    angular
        .module('geekstitch')
        .controller('CategoryController', CategoryController);

    CategoryController.$inject = ['$http', '$routeParams', 'Chunker'];

    function CategoryController($http, $routeParams, Chunker) {
        var vm = this;

        vm.category = {};
        vm.chunkedPacks = [];

        $http.get('./ajax/categories/' + $routeParams.category)
            .then(function(res){
                vm.category = res.data;
                vm.chunkedPacks = Chunker.getChunkedArray(vm.category.packs, 5);
            });
    }
})();