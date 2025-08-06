<?php
/**
 * Product Gallery Item
 *
 * @package Product Gallery Widget addons for Elementor
 */

// Use schema.org/Product for each product item
echo '<div class="col-sm-6 col-xl-4 mb-4 item-wrapper">';
echo '<article class="product-item rounded-3" itemscope itemtype="https://schema.org/Product">';
echo '<figure itemprop="image" itemscope itemtype="https://schema.org/ImageObject">';
echo '<img src="' . esc_url( $product[ 'thumbnail' ] ) . '" alt="' . esc_attr( $product[ 'title' ] ) . '" itemprop="url" width="300" height="200" loading="lazy" fetchpriority="high">';
echo '</figure>';
echo '<h3 class="product-title mb-2" itemprop="name">' . esc_html( $product[ 'title' ] ) . '</h3>';
echo '<div class="category" itemprop="category">' . esc_html( $product[ 'category' ] ) . '</div>';
echo '<div class="d-flex align-items-center justify-content-center mb-3" itemprop="offers" itemscope itemtype="https://schema.org/Offer">';
echo '<meta itemprop="priceCurrency" content="USD" />';
echo '<h4 class="me-2 mb-0" itemprop="price">$' . esc_html( $product[ 'price' ] ) . '</h4>';
if ( $product[ 'discountPercentage' ] > 0 ) {
	echo '<div class="badge discount-badge text-danger" itemprop="discount">' . esc_html( $product[ 'discountPercentage' ] . '% OFF' ) . '</div>';
}
echo '<link itemprop="availability" href="https://schema.org/' . ( $product[ 'stock' ] > 0 ? 'InStock' : 'OutOfStock' ) . '" />';
echo '</div>';
echo '<a href="/' . generate_dummy_product_url( $product[ 'category' ], $product[ 'title' ] ) . '/?dummy_id=' . esc_attr( $product[ 'id' ] ) . '" class="btn btn-main stretched-link" data-product-id="' . esc_attr( $product['id'] ) . '" itemprop="url">View Product</a>';
echo '</article>';
echo '</div>';
