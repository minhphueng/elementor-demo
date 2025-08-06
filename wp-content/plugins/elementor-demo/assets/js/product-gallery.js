/**
 * Product Gallery Widget JavaScript
 *
 * @package Product Gallery Widget addons for Elementor
 */

jQuery(document).ready(function($) {
    $('#product-sort, #product-category').on('change', function() {
        var sortBy   = $('#product-sort').val();
        var category = $('#product-category').val();
        var perPage  = $('.product-gallery-container').data('per-page');

        $('.product-gallery-container').toggleClass('freeze');

        $.ajax({
            url: productGalleryAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'product_gallery_filter',
                sort_by: sortBy,
                category: category,
                limit: perPage,
                nonce: productGalleryAjax.nonce
            },
            success: function(response) {
                if (response.success) {
                    $('.product-gallery-container').toggleClass('freeze');
                    $('#product-gallery-list').html(response.data.html);
                    $('#number').html($('.product-item').length);
                }
            },
            error: function(xhr, status, error) {
                $('.product-gallery-container').toggleClass('freeze');
                $('#number').html('n/a');
                alert('AJAX error: ' + error);
            }
        });
    });
});