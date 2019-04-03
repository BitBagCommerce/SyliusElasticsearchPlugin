(function ( $ ) {
    'use strict';

    $.fn.extend({
        autocompleteSearch: function (autocompleteInputElement, apiEndpointPath) {
            $(autocompleteInputElement)
                .search({
                    type: 'category',
                    minCharacters: 3,
                    apiSettings: {
                        onResponse: function (autocompleteResponse) {
                            let
                                response = {
                                    results: {}
                                }
                            ;

                            $.each(autocompleteResponse.items, function (index, item) {
                                var
                                    taxonName = item.taxon_name,
                                    maxResults = 10
                                ;

                                if (index >= maxResults) {
                                    return false;
                                }

                                if (response.results[taxonName] === undefined) {
                                    response.results[taxonName] = {
                                        name: taxonName,
                                        results: []
                                    };
                                }

                                response.results[taxonName].results.push({
                                    title: item.name,
                                    description: item.description,
                                    url: item.slug,
                                    price: item.price,
                                    image: item.image
                                });
                            });

                            return response;
                        },
                        url: apiEndpointPath
                    }
                })
            ;
        }
    });
})( jQuery );
