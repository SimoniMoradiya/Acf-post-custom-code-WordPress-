jQuery(document).ready(function($) {
    // Filter button click event
    $('.filter-button').click(function() {
        var filterValue = $(this).attr('data-filter'); // Get the filter value
        $('.custom-post').hide(); // Hide all posts initially
        if (filterValue == 'all') {
            $('.custom-post').show(); // If "All" button clicked, show all posts
        } else {
            $('.custom-post').each(function() {
                // Check if post contains the selected location
                if ($(this).find('.location').text().indexOf(filterValue) !== -1) {
                    $(this).show(); // Show post if it matches the selected location
                }
            });
        }
    });
});
