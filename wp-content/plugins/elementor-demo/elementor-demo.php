<?php
/**
 * Plugin Name: Elementor DEMO
 * Description: Display and filter products from DummyJSON API.
 * Version: 1.0
 * Author: Foo Fung
 *
 * @package Product Gallery Widget addons for Elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Main class for the Product Gallery Widget.
 * This class initializes the widget, registers scripts / styles, and handles AJAX requests.
 */
final class Product_Gallery_Widget {

    const VERSION                   = '1.0';
    const MINIMUM_ELEMENTOR_VERSION = '3.0.0';

    private static $_instance = null;

    /**
     * Get the instance of the class.
     *
     * @return Product_Gallery_Widget
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor method.
     * Initializes the plugin by setting up actions and filters.
     */
    public function __construct() {
        add_action( 'init', array( $this, 'i18n' ) );
        add_action( 'plugins_loaded', array( $this, 'init' ) );
        add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );
        add_action( 'elementor/frontend/after_register_scripts', array( $this, 'register_scripts' ) );
        add_action( 'elementor/frontend/after_register_styles', array( $this, 'register_styles' ) );
        add_action( 'wp_ajax_product_gallery_filter', array( $this, 'product_gallery_filter_callback' ) );
        add_action( 'wp_ajax_nopriv_product_gallery_filter', array( $this, 'product_gallery_filter_callback' ) );
    }

    /*
    * Load plugin textdomain for translations.
    */
    public function i18n() {
        load_plugin_textdomain( 'product-gallery-widget' );
    }

    /**
     * Initialize the plugin.
     * Checks for Elementor and its version.
     */
    public function init() {
        if ( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', array( $this, 'admin_notice_missing_elementor' ) );
            return;
        }
        if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
            add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
            return;
        }
    }

    /**
     * Admin notice if Elementor is not installed.
     */
    public function admin_notice_missing_elementor() {
        echo '<div class="notice notice-warning is-dismissible"><p>'
            . esc_html__( 'Product Gallery Widget requires Elementor to be installed and activated.', 'product-gallery-widget' )
            . '</p></div>';
    }

    /**
     * Admin notice if Elementor version is below the minimum required.
     */
    public function admin_notice_minimum_elementor_version() {
        echo '<div class="notice notice-warning is-dismissible"><p>'
            . esc_html__( 'Product Gallery Widget requires Elementor version ' . self::MINIMUM_ELEMENTOR_VERSION . ' or greater.', 'product-gallery-widget' )
            . '</p></div>';
    }

    /**
     * Register the widget with Elementor.
     *
     * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
     */
    public function register_widgets( $widgets_manager ) {
        require_once __DIR__ . '/inc/widgets/product-gallery.php';
        $widgets_manager->register( new \ProductGalleryWidget\Widgets\Product_Gallery() );
    }

    /**
     * Register scripts for the widget.
     */
    public function register_scripts() {
        wp_enqueue_script( 'product-gallery', plugins_url( '/assets/js/product-gallery.js', __FILE__ ), array( 'jquery' ), filemtime( plugin_dir_path( __FILE__ ) . '/assets/js/product-gallery.js' ), true );
        wp_localize_script( 'product-gallery', 'productGalleryAjax', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'product_gallery_nonce' ),
        ) );
    }

    /**
     * Register styles for the widget.
     */
    public function register_styles() {
        wp_enqueue_style( 'bootstrap', plugins_url( '/assets/css/bootstrap.min.css', __FILE__ ), array(), '5.3.7' );
        wp_enqueue_style( 'product-gallery', plugins_url( '/assets/css/product-gallery.css', __FILE__ ), array( 'bootstrap' ), filemtime( plugin_dir_path( __FILE__ ) . '/assets/css/product-gallery.css' ) );
    }

    /**
     * Callback for AJAX filtering of products.
     * Handles the request, retrieves products, applies filters, and returns HTML.
     */
    public function product_gallery_filter_callback() {
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'product_gallery_nonce' ) ) {
            wp_send_json_error( array( 'message' => 'Invalid nonce.' ) );
        }

        $limit    = isset( $_POST['limit'] ) ? intval( $_POST['limit'] ) : 9;
        $sort_by  = isset( $_POST['sort_by'] ) ? sanitize_text_field( $_POST['sort_by'] ) : 'price';
        $category = isset( $_POST['category'] ) ? sanitize_text_field( $_POST['category'] ) : '';

        // Build transient key based on category and limit
        $transient_key = $category ? 'product_gallery_products_' . $category . '_' . $limit : 'product_gallery_products_all_' . $limit;
        $products      = get_transient( $transient_key );

        if ( false === $products ) {
            if ( $category ) {
                // Fetch products by category with limit
                $api_url = 'https://dummyjson.com/products/category/' . urlencode( $category ) . '?limit=' . $limit;
            } else {
                // Fetch all products with limit
                $api_url = 'https://dummyjson.com/products?limit=' . $limit;
            }
            $args = array(
                'timeout' => 30,
            );
            $response = wp_remote_get( $api_url, $args );
            if ( is_wp_error( $response ) ) {
                wp_send_json_error();
            }
            $body = wp_remote_retrieve_body( $response );
            $data = json_decode( $body, true );
            $products = $data['products'] ?? array();
            set_transient( $transient_key, $products, 30 * MINUTE_IN_SECONDS );
        }

        // Sort products
        usort(
            $products,
            function ( $a, $b ) use ( $sort_by ) {
                if ( 'price' === $sort_by ) {
                    return $a['price'] <=> $b['price'];
                }
                return strcmp( strtolower( $a['title'] ), strtolower( $b['title'] ) );
            }
        );

        ob_start();
        foreach ( $products as $product ) {
            require plugin_dir_path( __FILE__ ) . 'inc/widgets/product-item.php';
        }
        $html = ob_get_clean();

        wp_send_json_success( array( 'html' => $html ) );
    }
}

Product_Gallery_Widget::instance();
