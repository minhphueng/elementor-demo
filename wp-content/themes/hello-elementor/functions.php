<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_ELEMENTOR_VERSION', '3.4.4' );
define( 'EHP_THEME_SLUG', 'hello-elementor' );

define( 'HELLO_THEME_PATH', get_template_directory() );
define( 'HELLO_THEME_URL', get_template_directory_uri() );
define( 'HELLO_THEME_ASSETS_PATH', HELLO_THEME_PATH . '/assets/' );
define( 'HELLO_THEME_ASSETS_URL', HELLO_THEME_URL . '/assets/' );
define( 'HELLO_THEME_SCRIPTS_PATH', HELLO_THEME_ASSETS_PATH . 'js/' );
define( 'HELLO_THEME_SCRIPTS_URL', HELLO_THEME_ASSETS_URL . 'js/' );
define( 'HELLO_THEME_STYLE_PATH', HELLO_THEME_ASSETS_PATH . 'css/' );
define( 'HELLO_THEME_STYLE_URL', HELLO_THEME_ASSETS_URL . 'css/' );
define( 'HELLO_THEME_IMAGES_PATH', HELLO_THEME_ASSETS_PATH . 'images/' );
define( 'HELLO_THEME_IMAGES_URL', HELLO_THEME_ASSETS_URL . 'images/' );

define( 'DUMMY_PRODUCT', isset( $_GET['dummy_id'] ) ? get_dummy_product( $_GET['dummy_id'] ) : false );

if ( ! isset( $content_width ) ) {
	$content_width = 800; // Pixels.
}

if ( ! function_exists( 'hello_elementor_setup' ) ) {
	/**
	 * Set up theme support.
	 *
	 * @return void
	 */
	function hello_elementor_setup() {
		if ( is_admin() ) {
			hello_maybe_update_theme_version_in_db();
		}

		if ( apply_filters( 'hello_elementor_register_menus', true ) ) {
			register_nav_menus( [ 'menu-1' => esc_html__( 'Header', 'hello-elementor' ) ] );
			register_nav_menus( [ 'menu-2' => esc_html__( 'Footer', 'hello-elementor' ) ] );
		}

		if ( apply_filters( 'hello_elementor_post_type_support', true ) ) {
			add_post_type_support( 'page', 'excerpt' );
		}

		if ( apply_filters( 'hello_elementor_add_theme_support', true ) ) {
			add_theme_support( 'post-thumbnails' );
			add_theme_support( 'automatic-feed-links' );
			add_theme_support( 'title-tag' );
			add_theme_support(
				'html5',
				[
					'search-form',
					'comment-form',
					'comment-list',
					'gallery',
					'caption',
					'script',
					'style',
					'navigation-widgets',
				]
			);
			add_theme_support(
				'custom-logo',
				[
					'height'      => 100,
					'width'       => 350,
					'flex-height' => true,
					'flex-width'  => true,
				]
			);
			add_theme_support( 'align-wide' );
			add_theme_support( 'responsive-embeds' );

			/*
			 * Editor Styles
			 */
			add_theme_support( 'editor-styles' );
			add_editor_style( 'editor-styles.css' );

			/*
			 * WooCommerce.
			 */
			if ( apply_filters( 'hello_elementor_add_woocommerce_support', true ) ) {
				// WooCommerce in general.
				add_theme_support( 'woocommerce' );
				// Enabling WooCommerce product gallery features (are off by default since WC 3.0.0).
				// zoom.
				add_theme_support( 'wc-product-gallery-zoom' );
				// lightbox.
				add_theme_support( 'wc-product-gallery-lightbox' );
				// swipe.
				add_theme_support( 'wc-product-gallery-slider' );
			}
		}
	}
}
add_action( 'after_setup_theme', 'hello_elementor_setup' );

function hello_maybe_update_theme_version_in_db() {
	$theme_version_option_name = 'hello_theme_version';
	// The theme version saved in the database.
	$hello_theme_db_version = get_option( $theme_version_option_name );

	// If the 'hello_theme_version' option does not exist in the DB, or the version needs to be updated, do the update.
	if ( ! $hello_theme_db_version || version_compare( $hello_theme_db_version, HELLO_ELEMENTOR_VERSION, '<' ) ) {
		update_option( $theme_version_option_name, HELLO_ELEMENTOR_VERSION );
	}
}

if ( ! function_exists( 'hello_elementor_display_header_footer' ) ) {
	/**
	 * Check whether to display header footer.
	 *
	 * @return bool
	 */
	function hello_elementor_display_header_footer() {
		$hello_elementor_header_footer = true;

		return apply_filters( 'hello_elementor_header_footer', $hello_elementor_header_footer );
	}
}

if ( ! function_exists( 'hello_elementor_scripts_styles' ) ) {
	/**
	 * Theme Scripts & Styles.
	 *
	 * @return void
	 */
	function hello_elementor_scripts_styles() {
		if ( apply_filters( 'hello_elementor_enqueue_style', true ) ) {
			wp_enqueue_style(
				'hello-elementor',
				HELLO_THEME_STYLE_URL . 'reset.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}

		if ( apply_filters( 'hello_elementor_enqueue_theme_style', true ) ) {
			wp_enqueue_style(
				'hello-elementor-theme-style',
				HELLO_THEME_STYLE_URL . 'theme.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}

		if ( hello_elementor_display_header_footer() ) {
			wp_enqueue_style(
				'hello-elementor-header-footer',
				HELLO_THEME_STYLE_URL . 'header-footer.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_scripts_styles' );

if ( ! function_exists( 'hello_elementor_register_elementor_locations' ) ) {
	/**
	 * Register Elementor Locations.
	 *
	 * @param ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager theme manager.
	 *
	 * @return void
	 */
	function hello_elementor_register_elementor_locations( $elementor_theme_manager ) {
		if ( apply_filters( 'hello_elementor_register_elementor_locations', true ) ) {
			$elementor_theme_manager->register_all_core_location();
		}
	}
}
add_action( 'elementor/theme/register_locations', 'hello_elementor_register_elementor_locations' );

if ( ! function_exists( 'hello_elementor_content_width' ) ) {
	/**
	 * Set default content width.
	 *
	 * @return void
	 */
	function hello_elementor_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'hello_elementor_content_width', 800 );
	}
}
add_action( 'after_setup_theme', 'hello_elementor_content_width', 0 );

if ( ! function_exists( 'hello_elementor_add_description_meta_tag' ) ) {
	/**
	 * Add description meta tag with excerpt text.
	 *
	 * @return void
	 */
	function hello_elementor_add_description_meta_tag() {
		if ( ! apply_filters( 'hello_elementor_description_meta_tag', true ) ) {
			return;
		}

		if ( ! is_singular() ) {
			return;
		}

		$post = get_queried_object();
		if ( empty( $post->post_excerpt ) ) {
			return;
		}

		echo '<meta name="description" content="' . esc_attr( wp_strip_all_tags( $post->post_excerpt ) ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'hello_elementor_add_description_meta_tag' );

// Settings page
require get_template_directory() . '/includes/settings-functions.php';

// Header & footer styling option, inside Elementor
require get_template_directory() . '/includes/elementor-functions.php';

if ( ! function_exists( 'hello_elementor_customizer' ) ) {
	// Customizer controls
	function hello_elementor_customizer() {
		if ( ! is_customize_preview() ) {
			return;
		}

		if ( ! hello_elementor_display_header_footer() ) {
			return;
		}

		require get_template_directory() . '/includes/customizer-functions.php';
	}
}
add_action( 'init', 'hello_elementor_customizer' );

if ( ! function_exists( 'hello_elementor_check_hide_title' ) ) {
	/**
	 * Check whether to display the page title.
	 *
	 * @param bool $val default value.
	 *
	 * @return bool
	 */
	function hello_elementor_check_hide_title( $val ) {
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$current_doc = Elementor\Plugin::instance()->documents->get( get_the_ID() );
			if ( $current_doc && 'yes' === $current_doc->get_settings( 'hide_title' ) ) {
				$val = false;
			}
		}
		return $val;
	}
}
add_filter( 'hello_elementor_page_title', 'hello_elementor_check_hide_title' );

/**
 * BC:
 * In v2.7.0 the theme removed the `hello_elementor_body_open()` from `header.php` replacing it with `wp_body_open()`.
 * The following code prevents fatal errors in child themes that still use this function.
 */
if ( ! function_exists( 'hello_elementor_body_open' ) ) {
	function hello_elementor_body_open() {
		wp_body_open();
	}
}

/* =========================
 * BEGIN OF CUSTOMIZATION
 * =========================
 */

/**
 * Enqueue addtional assets
 */
function enqueue_custom_scripts_styles() {
	wp_enqueue_style( 'custom-style', HELLO_THEME_STYLE_URL . 'custom-style.css', array(), filemtime( HELLO_THEME_STYLE_PATH . 'custom-style.css' ) );
	wp_enqueue_script( 'custom-script', HELLO_THEME_SCRIPTS_URL . 'custom-script.js', array( 'jquery' ), filemtime( HELLO_THEME_SCRIPTS_PATH . 'custom-script.js' ), true );

	if ( is_single() ) {
		wp_enqueue_style( 'slick', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css', array(), '1.8.1' );
		wp_enqueue_style( 'slick-theme', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css', array(), '1.8.1' );
		wp_enqueue_script( 'slick', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array( 'jquery' ), '1.8.1', true );
	}
}
add_action( 'wp_enqueue_scripts', 'enqueue_custom_scripts_styles' );

/**
 * Custom redirect action for DummyJSON API
 */
function custom_single_template() {
	global $wp_query;

	if ( defined( 'DUMMY_PRODUCT' ) && ! empty( DUMMY_PRODUCT ) ) {
    $template = HELLO_THEME_PATH . '/single-product.php';
    if ( file_exists( $template ) ) { // Check if the template file exists.
      $wp_query->is_single = true;
      status_header( 200 );
      require_once $template ;
      exit;
    }
		else { // Fallback to 404 if template not found.
      include get_query_template( '404' );
      exit;
    }
  }
}
add_action( 'template_redirect', 'custom_single_template' );

/**
 * Helper function to get product information and store in cache
 *
 * @return array
*/
function get_dummy_product( $product_id ) {
	$product_id    = absint( $product_id );
	$transient_key = 'dummy_product_' . $product_id;
	$product       = get_transient( $transient_key );

	if ( false === $product ) {
		$api_url = 'https://dummyjson.com/products/' . $product_id;
		$args    = array(
				'timeout' => 10,
		);

		$response = wp_remote_get( $api_url, $args );

		if ( is_wp_error( $response ) ) {
			error_log( 'DummyJSON API Error: ' . $response->get_error_message() );
			return false;
		}

		$body    = wp_remote_retrieve_body( $response );
		$product = json_decode( $body, true );

		if ( empty( $product ) || ! is_array( $product ) || isset( $product['error'] ) || isset( $product['message'] ) ) {
			error_log( 'DummyJSON API Invalid Response: ' . $body );
			return false;
		}

		set_transient( $transient_key, $product, 10 * MINUTE_IN_SECONDS ); // Cache for 10 minutes.
	}

	return $product;
}

/**
 * Helper function to fetch related products (same category, excluding current product)
 *
 * @return array
 */
function get_related_products( $category, $exclude_id, $limit = 3 ) {
	$category      = sanitize_title( $category );
	$exclude_id    = absint( $exclude_id );
	$transient_key = 'dummy_related_products_' . $exclude_id;

	$products = get_transient( $transient_key );

	if ( false === $products ) {
		$response = wp_remote_get( 'https://dummyjson.com/products/category/' . urlencode( $category ) );

		if ( is_wp_error( $response ) ) {
			return [];
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if ( empty( $data['products'] ) ) {
			return [];
		}

		$products = $data['products'];
		set_transient( $transient_key, $products, 10 * MINUTE_IN_SECONDS ); // Cache for 10 minutes.
	}

	// Filter out current product and randomize results.
	$filtered = array_filter( $products, function( $product ) use ( $exclude_id ) {
		return $product['id'] != $exclude_id;
	} );

	shuffle( $filtered );
	return array_slice( $filtered, 0, $limit );
}

/**
 * Helper function to generate SEO friend URL for dummy product
 *
 * @return string
*/
function generate_dummy_product_url( $category, $product_name ) {
	if ( ! $category || ! $product_name ) {
		wp_die( 'Category or product name is null' );
	}

	return sanitize_title( $category ) . '/' . sanitize_title( $product_name );
}

/**
 * Change meta title for dummy product single template.
 *
 * @return string
 */
function custom_dummy_product_meta_title( $title ) {
	if ( defined( 'DUMMY_PRODUCT' ) && ! empty( DUMMY_PRODUCT ) ) {
		$product = get_dummy_product( $_GET['dummy_id'] );
		if ( $product && ! empty( $product['title'] ) && ! empty( $product['category'] ) ) {
				return esc_html( ucfirst( $product['category'] ) ) . ' - ' . esc_html( $product['title'] ) . ' | ' . get_bloginfo( 'name' );
		}
	}
	return $title;
}
add_filter( 'pre_get_document_title', 'custom_dummy_product_meta_title' );
add_filter( 'rank_math/frontend/title', 'custom_dummy_product_meta_title', 999 );

/**
 * Override meta robots for dummy product single template.
 *
 * @return array
 */
add_filter( 'rank_math/frontend/robots', function( $robots ) {
	return [
		'index'  => 'index',
		'follow' => 'follow, max-snippet:-1, max-video-preview:-1, max-image-preview:large',
	];
} );

/* =========================
 * END OF CUSTOMIZATION
 * =========================
 */

require HELLO_THEME_PATH . '/theme.php';

HelloTheme\Theme::instance();
