<?php
/**
 * Template for displaying the add-to-compare button on the single product page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-compare.php.
 *
 * @author  WCBoost
 * @package WCBoost\ProductsCompare\Templates
 * @version 1.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

echo apply_filters(
	'wcboost_products_compare_single_add_to_compare_link', // WPCS: XSS ok.
	sprintf(
		'<a href="%s" data-product_id="%d" class="%s" aria-label="%s">
			%s
			<span class="wcboost-products-compare-button__text">%s</span>
		</a>',
		esc_url( isset( $args['url'] ) ? $args['url'] : add_query_arg( [ 'add-to-compare' => $product->get_id() ] ) ),
		esc_attr( $product->get_id() ),
		esc_attr( isset( $args['class'] ) ? $args['class'] : 'wcboost-products-compare-button wcboost-products-compare-button--single button' ),
		esc_attr( isset( $args['aria-label'] ) ? $args['aria-label'] : sprintf( __( 'Compare %s', 'wcboost-products-compare' ), '&ldquo;' . $product->get_title() . '&rdquo;' ) ),
		empty( $args['icon'] ) ? '' : '<span class="wcboost-products-compare-button__icon">' . $args['icon'] . '</span>',
		esc_html( isset( $args['label'] ) ? $args['label'] : __( 'Compare', 'wcboost-products-compare' ) )
	),
	$args
);
