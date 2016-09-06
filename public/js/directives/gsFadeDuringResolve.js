(function() {
    'use strict';

    angular
        .module('geekstitch')
        .directive('gsFadeDuringResolve', FadeDuringResolve);

    FadeDuringResolve.$inject = ['$rootScope', '$timeout'];

    function FadeDuringResolve($rootScope, $timeout) {
        return {
            restrict: 'A',
            link: function(scope, element) {
                $rootScope.$on('$routeChangeStart', function(event, currentRoute, previousRoute) {
                    if (!previousRoute) return;

                    element.addClass('faded');
                });

                $rootScope.$on('$routeChangeSuccess', function() {
                    element.removeClass('faded');
                });
            }
        };
    }
})();
