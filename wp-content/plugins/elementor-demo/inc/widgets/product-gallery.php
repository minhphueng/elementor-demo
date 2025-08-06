<?php
/**
 * Product Gallery Widget
 *
 * @package Product Gallery Widget addons for Elementor
 */

namespace ProductGalleryWidget\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Product Gallery Widget Class
 * This class defines the Product Gallery widget for Elementor.
 */
class Product_Gallery extends Widget_Base {
    /**
     * Get widget name.
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'product_gallery';
    }

    /**
     * Get widget title.
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Product Gallery', 'product-gallery-widget' );
    }

    /**
     * Get widget icon.
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-gallery-grid';
    }

    /**
     * Get widget categories.
     *
     * @return array Widget categories.
     */
    public function get_categories() {
        return array( 'general' );
    }

    /**
     * Register widget controls.
     * This method defines the controls for the widget in the Elementor editor.
     */
    protected function _register_controls() {
        // Controls for layout, sorting, etc.
        $this->start_controls_section(
            'section_content',
            array(
                'label' => __( 'Settings', 'product-gallery-widget' ),
            )
        );

        $this->add_control(
            'products_per_page',
            array(
                'label'   => __( 'Products Per Page', 'product-gallery-widget' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 9,
            )
        );

        $this->add_control(
            'sort_by',
            array(
                'label'   => __( 'Sort By', 'product-gallery-widget' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'price' => __( 'Price', 'product-gallery-widget' ),
                    'title' => __( 'Title', 'product-gallery-widget' ),
                ),
                'default' => 'price',
            )
        );

        $this->add_control(
            'category',
            array(
                'label'       => __( 'Category', 'product-gallery-widget' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'description' => __( 'Enter category to filter (leave blank for all)', 'product-gallery-widget' ),
            )
        );

        $this->end_controls_section();
    }

    /**
     * This method renders the HTML content for the widget.
     * It also cache the products and categories to improve performance.
     *
     * @return void
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $products = $this->fetch_products( $settings['products_per_page'] );
        $sort_by  = $settings['sort_by'] ?? 'price';
        $category = $settings['category'] ?? '';

        echo '<div class="product-filters">';
        echo '<select id="product-sort" class="form-select">';
        echo '<option value="price"' . selected( $sort_by, 'price', false ) . '>Sort by Price</option>';
        echo '<option value="title"' . selected( $sort_by, 'title', false ) . '>Sort by Title</option>';
        echo '</select>';

        echo '<select id="product-category" class="form-select">';
        echo '<option value="">All Categories</option>';
        $cat_transient_key = 'product_gallery_categories_' . $settings['products_per_page'];
        $categories        = get_transient( $cat_transient_key );
        if ( false === $categories ) {
            $categories = array_unique( array_column( $products, 'category' ) );
            set_transient( $cat_transient_key, $categories, 10 * MINUTE_IN_SECONDS );
        }
        foreach ( $categories as $cat ) {
            echo '<option value="' . esc_attr( $cat ) . '"' . selected( $category, $cat, false ) . '>' . esc_html( ucfirst( $cat ) ) . '</option>';
        }
        echo '</select>';
        echo '</div>';

        echo '<div class="container-fluid product-gallery-container" data-per-page="' . esc_attr( $settings['products_per_page'] ) . '">';
        echo '<div class="row" id="product-gallery-list">';
        foreach ( $products as $product ) {
            require plugin_dir_path( __FILE__ ) . 'product-item.php';
        }
        echo '</div>';
        echo '</div>';
    }

    /**
     * Fetch products from the API and cache them.
     *
     * @param int $limit Number of products to fetch.
     * @return array $product List of products.
     */
    private function fetch_products( $limit = 9 ) {
        $transient_key = 'product_gallery_products_' . $limit;
        $products      = get_transient( $transient_key );

        if ( false === $products ) {
            $response = wp_remote_get( 'https://dummyjson.com/products?limit=' . $limit );
            if ( is_wp_error( $response ) ) {
                return array();
            }
            $body     = wp_remote_retrieve_body( $response );
            $data     = json_decode( $body, true );
            $products = $data['products'] ?? array();
            set_transient( $transient_key, $products, 10 * MINUTE_IN_SECONDS );
        }

        return $products;
    }
}
