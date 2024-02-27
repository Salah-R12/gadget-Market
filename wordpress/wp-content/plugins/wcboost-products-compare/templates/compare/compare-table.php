<?php
/**
 * Template for displaying the table of compared products.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/compare/compare-table.php.
 *
 * @author  WCBoost
 * @package WCBoost\ProductsCompare\Templates
 * @version 1.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! isset( $compare_items ) ) {
	return;
}
?>

<?php do_action( 'wcboost_products_compare_before_compare_table', $compare_items ); ?>

<div class="wcboost-products-compare__table">
	<table class="shop_table compare_table" cellspacing="0">
		<tbody>
			<?php foreach ( $compare_fields as $field => $field_name ) : ?>
				<tr class="product-<?php echo esc_attr( $field ); ?>" data-title="<?php echo esc_attr( $field_name ); ?>">
					<th><?php echo wp_kses_post( $field_name ); ?></th>
					<?php foreach ( $compare_items as $item_key => $_product ) : ?>
						<td>
							<?php \WCBoost\ProductsCompare\Helper::compare_field( $field, $_product, $item_key ); ?>
						</td>
					<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

<?php do_action( 'wcboost_products_compare_after_compare_table', $compare_items ); ?>
