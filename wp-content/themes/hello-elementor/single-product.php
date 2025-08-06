<?php
/**
 * Template for displaying dummy product item from DummyJSON API
 *
 * @package HelloElementor
 */

  if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
  }

  get_header();
?>
<style>
  .product-thumbnails li {
    width: 25%;
  }

  .product-thumbnails img {
    width: 100%;
  }

  .breadcrumb-item + .breadcrumb-item::before {
    content: '»';
    margin: 0 6px;
  }

  .btn-main {
    display: inline-block;
    padding: 10px 25px;
    background: #61CE70;
    color: #fff;
    text-decoration: none;
    border-radius: 25px;
    font-weight: 500;
  }
</style>

<main id="content" <?php post_class( 'site-main' ); ?> role="main" itemscope itemtype="https://schema.org/Product">
  <?php if ( DUMMY_PRODUCT ) : ?>
  <div class="container mb-5">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb list-unstyled d-flex flex-wrap mb-4">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?php echo esc_html( ucfirst( DUMMY_PRODUCT['category'] ) ); ?></li>
      </ol>
    </nav>
    <!-- Main Product Section -->
    <section class="row mb-5" itemprop="mainEntity">
        <!-- Left Column - Product Image -->
        <div class="col-md-6 mb-4 mb-md-0">
            <figure class="bg-light p-3 rounded-3 mb-3" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
              <img src="<?php echo esc_url( DUMMY_PRODUCT[ 'thumbnail' ] ); ?>"
                   alt="<?php echo esc_attr( DUMMY_PRODUCT[ 'title' ] ); ?>"
                   class="img-fluid product-image d-block mx-auto"
                   itemprop="url">
            </figure>
            <ul class="product-thumbnails list-unstyled d-flex flex-wrap mb-0" aria-label="Product thumbnails">
              <?php for( $i = 0; $i < 4; $i++ ): ?>
              <li class="flex-shrink-0">
                <img class="thumbnail-img"
                     src="<?php echo esc_url( DUMMY_PRODUCT[ 'thumbnail' ] ); ?>"
                     alt="product thumbnail"
                     width="80"
                     height="80"
                     loading="lazy">
              </li>
              <?php endfor; ?>
            </ul>
        </div>

        <!-- Right Column - Product Info -->
        <div class="col-md-6">
            <h1 class="mb-3" itemprop="name"><?php echo esc_html( DUMMY_PRODUCT[ 'title' ] ); ?></h1>
            <p itemprop="description"><?php echo esc_html( DUMMY_PRODUCT['description'] ); ?></p>

            <div class="d-flex align-items-center" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
              <meta itemprop="priceCurrency" content="USD" />
              <h2 class="me-3 mb-0" itemprop="price">$<?php echo number_format( DUMMY_PRODUCT[ 'price' ], 2 ); ?></h2>
              <?php if ( DUMMY_PRODUCT[ 'discountPercentage' ] > 0 ) : ?>
                <div class="badge discount-badge text-danger" itemprop="discount">
                  <?php echo esc_html( DUMMY_PRODUCT[ 'discountPercentage' ] . '% OFF' ); ?>
                </div>
              <?php endif; ?>
              <link itemprop="availability" href="https://schema.org/<?php echo ( DUMMY_PRODUCT['stock'] > 0 ) ? 'InStock' : 'OutOfStock'; ?>" />
            </div>
            <div class="badge mb-3">
              <strong>Stock:</strong>
              <span itemprop="inventoryLevel"><?php echo esc_html ( DUMMY_PRODUCT['stock'] ); ?></span>
            </div>

            <div class="d-grid gap-2 d-md-flex">
              <button class="btn btn-primary btn-lg px-4 me-md-2" type="button">Add to Cart</button>
              <button class="btn btn-outline-secondary btn-lg px-4" type="button">Wishlist</button>
            </div>
        </div>
    </section>

    <!-- Related Products Section. There is Eater Egg over here ;) -->
    <?php
      $related_products = get_related_products( DUMMY_PRODUCT['category'], DUMMY_PRODUCT['id'], 6 );
      if ( ! empty( $related_products ) ) :
    ?>
    <h3 class="mb-5 text-center">Related Products</h3>
    <div class="row slider">
      <?php foreach ( $related_products as $related_product ) : ?>
      <div class="item-wrapper col-sm-4 mb-4">
        <article class="product-item rounded-3" itemscope itemtype="https://schema.org/Product">
          <figure itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
            <img src="<?php echo esc_url( $related_product['thumbnail'] ); ?>"
              alt="<?php echo esc_attr( $related_product['title'] ); ?>"
              itemprop="url"
              width="300"
              height="200"
              loading="lazy">
          </figure>
          <h3 class="product-title mb-2" itemprop="name"><?php echo esc_html( $related_product['title'] ); ?></h3>
          <div class="category" itemprop="category"><?php echo esc_html( $related_product['category'] ); ?></div>
          <div class="d-flex align-items-center justify-content-center mb-3" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
            <meta itemprop="priceCurrency" content="USD" />
            <h4 class="me-2 mb-0" itemprop="price">$ <?php echo esc_html( $related_product['price'] ); ?></h4>
            <?php if ( $related_product[ 'discountPercentage' ] > 0 ) : ?>
              <div class="badge discount-badge text-danger" itemprop="discount"><?php echo esc_html( $related_product[ 'discountPercentage' ] . '% OFF' ); ?></div>
            <?php endif; ?>
            <link itemprop="availability" href="https://schema.org/<?php echo ( $related_product['stock'] > 0 ? 'InStock' : 'OutOfStock' ); ?>" />
          </div>
          <a class="btn btn-main stretched-link mt-1 mb-2"
            href="/<?php echo generate_dummy_product_url( $related_product[ 'category' ], $related_product[ 'title' ] ); ?>/?dummy_id=<?php echo esc_attr( $related_product['id'] ); ?>"
            data-product-id="<?php echo esc_attr( $related_product['id'] ); ?>" itemprop="url">View Product</a>
        </article>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div> <!-- /end .container -->
  <?php else : ?>
  <div class="alert alert-danger">Product not found!</div>
  <?php endif; ?>
</main>

<?php

get_footer();