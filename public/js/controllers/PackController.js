(function() {
    'use strict';

    angular
        .module('geekstitch')
        .controller('PackController', PackController);

    PackController.$inject = ['$http', '$routeParams'];

    function PackController($http, $routeParams) {
        var vm = this;

        vm.pack = [];

        $http.get('./ajax/packs/' + $routeParams.pack)
            .then(function(res){
                vm.pack = res.data;
            });
    }
})();