<?php
/**
 * Template for displaying the compare list.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/compare/compare.php.
 *
 * @author  WCBoost
 * @package WCBoost\ProductsCompare\Templates
 * @version 1.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! isset( $compare_list ) ) {
	return;
}

do_action( 'wcboost_products_compare_before_content', $compare_list );

do_action( 'wcboost_products_compare_content', $compare_list );

do_action( 'wcboost_products_compare_after_content', $compare_list );
