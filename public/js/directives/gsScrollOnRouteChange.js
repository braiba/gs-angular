(function() {
    'use strict';

    angular
        .module('geekstitch')
        .directive('gsScrollOnRouteChange', ScrollOnRouteChange);

    ScrollOnRouteChange.$inject = ['$rootScope', '$window'];

    function ScrollOnRouteChange($rootScope, $window) {
        var data = {
            startingPos: 0,
            endingPos: 0,
            startTime: 0
        };

        var scope = null;
        var interval = null;

        return {
            restrict: 'A',
            link: link,
            scope: {
                duration: '=',
                frame: '='
            }
        };

        function link(linkScope, element) {
            scope = linkScope;
            
            $rootScope.$on('$routeChangeStart', function() {
                data.startingPos = $window.scrollY;
                data.endingPos = getEndLocation(element);
                data.startTime = (new Date()).getTime();

                if (data.startingPos !== data.endingPos) {
                    interval = setInterval(animate, parseInt(scope.frame));
                }
            });
        }

        function animate() {
            var now = (new Date()).getTime();
            var timePassed = (now - data.startTime);
            if (timePassed >= scope.duration) {
                timePassed = scope.duration;
                clearInterval(interval);
            }

            var pos = data.startingPos + getEased(timePassed / scope.duration) * (data.endingPos - data.startingPos);
            $window.scrollTo(0, pos);
        }

        function getEased(position) {
            if (position < 0.5) {
                return 2 * position * position;
            }

            return 1 - 2 * (1 - position) * (1 - position);
        }

        function getEndLocation(element) {
            var location = 0;
            if (element.offsetParent) {
                do {
                    location += element.offsetTop;
                    element = element.offsetParent;
                } while (element);
            }

            return Math.max(location, 0);
        }
    }
})();
