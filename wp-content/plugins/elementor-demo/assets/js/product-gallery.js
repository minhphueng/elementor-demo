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
                    $('#product-gallery-list').html(response.data.html);
                }
            }
        });
    });
});