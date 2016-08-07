(function() {
    'use strict';

    angular
        .module('geekstitch')
        .service('ShippingType', ShippingType);

    ShippingType.$inject = ['$http'];

    function ShippingType($http) {
        return {
            list: list
        };

        function list() {
            return $http.get('./shipping-types')
                .then(function(response) {
                    return response.data;
                });
        }
    }
})();
