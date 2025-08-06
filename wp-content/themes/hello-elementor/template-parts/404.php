<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package HelloElementor
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<main id="content" class="site-main">

	<?php if ( apply_filters( 'hello_elementor_page_title', true ) ) : ?>
		<div class="page-header">
			<h1 class="entry-title"><?php echo esc_html__( 'The page can&rsquo;t be found.', 'hello-elementor' ); ?></h1>
		</div>
	<?php endif; ?>

	<div class="page-content">
		<h1 class="mt-5 mb-3">404</h1>
  	<h3 class="mb-5">
			<?php echo esc_html__( 'It looks like nothing was found at this location.', 'hello-elementor' ); ?> <br>
			<a href="/">Back to the Gallery</a>
		</h3>
	</div>

</main>
