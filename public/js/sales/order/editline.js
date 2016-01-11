$(document).ready(function(){
    $('.typeahead').typeahead({
        name: 'itemCode',
        prefetch: 'inventory/items/search'
    });
});  