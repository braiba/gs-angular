(function() {
    'use strict';

    angular
        .module('geekstitch')
        .directive('gsCategoryTypePanel', CategoryTypePanel);

    function CategoryTypePanel() {
        return {
            restrict: 'E',
            templateUrl: 'views/templates/category-type-panel.html',
            scope: {
                categoryType: '@'
            },
            controller: CategoryTypePanelController,
            controllerAs: 'vm',
            bindToController: true
        };
    }

    CategoryTypePanelController.$inject = ['$http', 'Chunker'];

    function CategoryTypePanelController($http, Chunker) {
        var vm = this;

        vm.data = {
            categoryType: null,
            chunkedCategories: null
        };

        $http.get('./ajax/' + vm.categoryType, {params: {important: true}})
            .then(function(res){
                vm.data.categoryType = res.data;
                vm.data.chunkedCategories = Chunker.getChunkedArray(vm.data.categoryType.categories, 3);
            });
    }
})();