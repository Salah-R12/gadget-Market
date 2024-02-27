/* global wcboost_products_compare_fragments_params, woocommerce_params */
jQuery( function( $ ) {

	var WCBoostProductsCompareFragments = function() {
		var self = this;

		// Methods.
		this.updateFragments = this.updateFragments.bind( this );
		this.getProductIds   = this.getProductIds.bind( this );

		// Events.
		$( document.body )
			.on( 'products_compare_fragments_refresh products_compare_list_updated', { productsCompareFragments: self }, self.refreshFragments )
			.on( 'added_to_compare removed_from_compare', { productsCompareFragments: self }, self.updateFragmentsOnChanges );

		// Refresh when page is shown after back button (safari).
		$( window ).on( 'pageshow' , function( event ) {
			if ( event.originalEvent.persisted ) {
				$( document.body ).trigger( 'products_compare_fragments_refresh', [ true ] );
			}
		} );

		// Refresh fragments if the option is enabled.
		if ( 'yes' === wcboost_products_compare_fragments_params.refresh_on_load ) {
			$( document.body ).trigger( 'products_compare_fragments_refresh' );
		}
	}

	WCBoostProductsCompareFragments.prototype.refreshFragments = function( event, includeButtons ) {
		var self = event.data.productsCompareFragments;
		var data = { time: new Date().getTime() };

		if ( 'yes' === wcboost_products_compare_fragments_params.refresh_on_load || includeButtons ) {
			data.product_button_ids = self.getProductIds();
		}

		$.post( {
			url: woocommerce_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'get_compare_fragments' ),
			data: data,
			dataType: 'json',
			timeout: wcboost_products_compare_fragments_params.request_timeout,
			success: function( response ) {
				if ( ! response.success ) {
					$( document.body ).trigger( 'products_compare_fragments_failed' );

					return;
				}

				self.updateFragments( response.data.fragments );

				$( document.body ).trigger( 'products_compare_fragments_refreshed' );
			},
			error: function() {
				$( document.body ).trigger( 'products_compare_fragments_ajax_error' );
			}
		} );
	}

	WCBoostProductsCompareFragments.prototype.getProductIds = function() {
		var ids = [];

		$( '.wcboost-products-compare-button' ).each( function( index, button ) {
			ids.push( button.dataset.product_id );
		} );

		return ids;
	}

	WCBoostProductsCompareFragments.prototype.updateFragmentsOnChanges = function( event, $button, fragments ) {
		var self = event.data.productsCompareFragments;

		self.updateFragments( fragments );
	}

	WCBoostProductsCompareFragments.prototype.updateFragments = function( fragments ) {
		$.each( fragments, function( key, value ) {
			$( key ).replaceWith( value );
		} );

		$( document.body ).trigger( 'products_compare_fragments_loaded' );
	}


	new WCBoostProductsCompareFragments();
} );
