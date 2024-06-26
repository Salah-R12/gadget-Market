<?php
/**
 * Functions and Hooks for product meta box data
 *
 * @package Martfury
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * martfury_Meta_Box_Product_Data class.
 */
class Martfury_Meta_Box_Product_Data {

	/**
	 * Constructor.
	 */
	public function __construct() {

		if ( ! function_exists( 'is_woocommerce' ) ) {
			return;
		}

		// Add admin style
		add_filter( 'woocommerce_screen_ids', array( $this, 'brand_screen_ids' ), 20 );

		// Add form
		add_action( 'woocommerce_product_data_panels', array( $this, 'product_meta_fields' ) );
		add_action( 'woocommerce_product_data_tabs', array( $this, 'product_meta_tab' ) );
		add_action( 'woocommerce_process_product_meta', array( $this, 'product_meta_fields_save' ) );

		add_action( 'wp_ajax_product_meta_fields', array( $this, 'instance_product_meta_fields' ) );
		add_action( 'wp_ajax_nopriv_product_meta_fields', array( $this, 'instance_product_meta_fields' ) );

	}

	/**
	 * Add  all WooCommerce screen ids.
	 *
	 * @since  1.0
	 *
	 * @param  array $screen_ids
	 *
	 * @return array
	 */
	function brand_screen_ids( $screen_ids ) {
		$screen_ids[] = 'edit-mf_product_brand';

		return $screen_ids;
	}


	/**
	 * Get product data fields
	 *
	 */
	public function instance_product_meta_fields() {
		$post_id = $_POST['post_id'];
		ob_start();
		$this->create_product_extra_fields( $post_id );
		$response = ob_get_clean();
		wp_send_json_success( $response );
		die();
	}

	/**
	 * Product data tab
	 */
	public function product_meta_tab( $product_data_tabs ) {

		$product_data_tabs['martfury_attributes_extra'] = array(
			'label'  => esc_html__( 'Extra', 'martfury' ),
			'target' => 'product_attributes_extra',
			'class'  => 'product-attributes-extra'
		);

		return $product_data_tabs;
	}

	/**
	 * Add product data fields
	 *
	 */
	public function product_meta_fields() {
		global $post;
		$this->create_product_extra_fields( $post->ID );
	}

	/**
	 * product_meta_fields_save function.
	 *
	 * @param mixed $post_id
	 */
	public function product_meta_fields_save( $post_id ) {

		if ( isset( $_POST['custom_badges_text'] ) ) {
			$woo_data = $_POST['custom_badges_text'];
			update_post_meta( $post_id, 'custom_badges_text', $woo_data );
		}

		if ( isset( $_POST['_is_new'] ) ) {
			$woo_data = $_POST['_is_new'];
			update_post_meta( $post_id, '_is_new', $woo_data );
		} else {
			update_post_meta( $post_id, '_is_new', 0 );
		}
	}

	/**
	 * Create product meta fields
	 *
	 * @param $post_id
	 */
	public function create_product_extra_fields( $post_id ) {
		global $post;

		echo '<div id="product_attributes_extra" class="panel woocommerce_options_panel">';
		woocommerce_wp_text_input(
			array(
				'id'       => 'custom_badges_text',
				'label'    => esc_html__( 'Custom Badge Text', 'martfury' ),
				'desc_tip' => esc_html__( 'Enter this optional to show your badges.', 'martfury' ),
			)
		);
		woocommerce_wp_checkbox(
			array(
				'id'          => '_is_new',
				'label'       => esc_html__( 'New product?', 'martfury' ),
				'description' => esc_html__( 'Enable to set this product as a new product. A "New" badge will be added to this product.', 'martfury' ),
			)
		);
		echo '</div>';
	}
}

new Martfury_Meta_Box_Product_Data;