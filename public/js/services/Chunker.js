(function() {
    'use strict';

    angular
        .module('geekstitch')
        .service('Chunker', Chunker);

    Chunker.$inject = [];

    function Chunker() {
        this.getChunkedArray = function(array, chunkSize) {
            var chunks = [];
            for (var i=0, j=array.length; i<j; i+=chunkSize) {
                chunks.push(array.slice(i, i+chunkSize));
            }
            return chunks;
        };

        this.getChunkedMap = function(map, chunkSize) {
            var chunks = [];
            var chunk = {};
            var chunkItemCount = 0;
            angular.forEach(map, function(value, key) {
                chunk[key] = value;
                if (++chunkItemCount == chunkSize) {
                    chunks.push(chunk);
                    chunk = {};
                    chunkItemCount = 0;
                }
            });

            if (chunkItemCount != 0) {
                chunks.push(chunk);
            }

            return chunks;
        };

        this.getChunked = function(chunkable, chunkSize) {
            if (Array.isArray(chunkable)) {
                return GeekStitch.getChunkedArray(chunkable, chunkSize);
            } else {
                return GeekStitch.getChunkedMap(chunkable, chunkSize);
            }
        };
    }
})();