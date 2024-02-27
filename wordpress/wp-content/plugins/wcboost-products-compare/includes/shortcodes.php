<?php
namespace WCBoost\ProductsCompare;

defined( 'ABSPATH' ) || exit;

/**
 * Shortcodes class
 */
class Shortcodes {

	/**
	 * Initialize shortcodes
	 */
	public static function init() {
		add_shortcode( 'wcboost_compare', [ __CLASS__, 'compare_page' ] );
		add_shortcode( 'wcboost_compare_button', [ __CLASS__, 'compare_button' ] );
	}

	/**
	 * Compare page shortcode
	 *
	 * @param  array $atts
	 * @return string
	 */
	public static function compare_page( $atts ) {
		$product_ids = ! empty( $_GET['compare_products'] ) ? array_map( 'absint', explode( ',', $_GET['compare_products'] ) ) : [];
		$atts        = shortcode_atts(
			[
				'product_ids' => $product_ids,
			],
			$atts,
			'wcboost_compare'
		);

		$list = empty( $atts['product_ids'] ) ? Plugin::instance()->list : new Compare_List( $atts['product_ids'] );
		$args = [
			'compare_list' => $list,
			'return_url'   => apply_filters( 'wcboost_products_compare_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ),
		];

		$args = apply_filters( 'wcboost_products_compare_template_args', $args, $list );
		$html = wc_get_template_html( 'compare/compare.php', $args, '', Plugin::instance()->plugin_path() . '/templates/' );

		return '<div class="woocommerce wcboost-products-compare">' . $html . '</div>';
	}

	/**
	 * Compare button shortcode
	 *
	 * @param  array $atts
	 * @return string
	 */
	public static function compare_button( $atts ) {
		$atts = shortcode_atts(
			[
				'product_id' => '',
				'class'      => '',
			],
			$atts,
			'wcboost_compare_button'
		);

		$atts['product_id'] = $atts['product_id'] ? $atts['product_id'] : ( ! empty( $GLOBALS['product'] ) ? $GLOBALS['product']->get_id() : 0 );

		if ( ! $atts['product_id'] ) {
			return '';
		}

		/** @var \WC_Product | \WC_Product_Variable $_product */
		$_product = wc_get_product( $atts['product_id'] );

		if ( ! $_product ) {
			return '';
		}

		$list = Plugin::instance()->list;

		if ( $list && $list->has_item( $_product ) && 'hide' == get_option( 'wcboost_products_compare_exists_item_button_behaviour' ) ) {
			return '';
		}

		$args = Frontend::instance()->get_button_template_args( $_product );

		if ( ! empty( $atts['class'] ) ) {
			$args['class'] .= ' ' . $atts['class'];
		}

		$html = wc_get_template_html( 'loop/add-to-compare.php', $args, '', Plugin::instance()->plugin_path() . '/templates/' );

		return apply_filters( 'wcboost_products_compare_shortcode_button_html', $html, $atts );
	}
}
