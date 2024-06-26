<?php
$els = (array) martfury_get_option( 'navigation_els_mobile' );

if ( empty( $els ) ) {
	return;
}

?>

<div class="mf-navigation-mobile" id="mf-navigation-mobile">
    <div class="navigation-list">
		<?php
		foreach ( $els as $el ) {
			if ( 'home' == $el ) {
				echo sprintf( '<a href="%s" class="navigation-icon navigation-mobile_home"><i class="icon-home"></i> %s</a>', esc_url( home_url( '/' ) ), esc_html( martfury_get_option( 'navigation_home_mobile' ) ) );
			} elseif ( 'cat' == $el ) {
				echo sprintf( '<a href="#" class="navigation-icon navigation-mobile_cat" id="navigation-mobile_cat"><i class="icon-menu"></i> %s</a>', esc_html( martfury_get_option( 'navigation_cat_mobile' ) ) );
			} elseif ( 'search' == $el ) {
				echo sprintf( '<a href="#" class="navigation-icon navigation-mobile_search"><i class="icon-magnifier"></i> %s</a>', esc_html( martfury_get_option( 'navigation_search_mobile' ) ) );
			} elseif ( 'cart' == $el ) {
				if ( ! function_exists( 'wc_get_cart_url' ) ) {
					continue;
				}

				$cart_active = '';
				if ( function_exists( 'is_cart' ) && is_cart() && martfury_get_option( 'navigation_cart_behaviour' ) == 'link' ) {
					$cart_active = 'active';
				}
				global $woocommerce;
				echo sprintf( '<a href="%s" class="navigation-icon navigation-mobile_cart cart-contents %s"><i class="icon-bag2"></i><span class="mini-item-counter mf-background-primary">%s</span> %s</a>', esc_url( wc_get_cart_url() ), esc_attr( $cart_active ),intval( $woocommerce->cart->cart_contents_count), esc_html( martfury_get_option( 'navigation_cart_mobile' ) ) );
			} elseif ( 'wishlist' == $el ) {
				if( shortcode_exists( 'wcboost_wishlist_button' ) ) {
					$wishlist_page_url = wc_get_page_permalink( 'wishlist' );
				} elseif ( function_exists( 'YITH_WCWL' ) ) {
					$wishlist_page_url =  get_permalink( get_option( 'yith_wcwl_wishlist_page_id' ) );
				} else {
					continue;
				}

				$cart_active      = '';
				if ( is_page( $wishlist_page_url ) ) {
					$cart_active = 'active';
				}
				echo sprintf( '<a href="%s" class="navigation-icon navigation-mobile_wishlist %s"><i class="icon-heart"></i> %s</a>', esc_url(  $wishlist_page_url ), esc_attr( $cart_active ), esc_html( martfury_get_option( 'navigation_wishlist_mobile' ) ) );
			} elseif ( 'compare' == $el ) {
				if ( ! class_exists( 'YITH_Woocompare' ) ) {
					continue;
				}

				echo sprintf( '<a href="#" class="navigation-icon navigation-mobile_compare yith-woocompare-open"><i class="icon-chart-bars"></i> %s</a>', esc_html( martfury_get_option( 'navigation_compare_mobile' ) ) );
			} elseif ( 'account' == $el ) {
				$account_link = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );

				if ( is_user_logged_in() ) {
					$user_id = get_current_user_id();
					$author  = get_user_by( 'id', $user_id );

					if ( function_exists( 'dokan_get_navigation_url' ) && in_array( 'seller', $author->roles ) ) {
						if ( ! empty( martfury_get_option( 'vendor_logged_link' ) ) ) {
							$account_link = martfury_get_option( 'vendor_logged_link' );
						} else {
							$account_link = function_exists( 'dokan_get_navigation_url' ) ? dokan_get_navigation_url() : $account_link;
						}
					} elseif ( class_exists( 'WCVendors_Pro' ) && in_array( 'vendor', $author->roles ) ) {
						if ( ! empty( martfury_get_option( 'vendor_logged_link' ) ) ) {
							$account_link = martfury_get_option( 'vendor_logged_link' );
						} else {
							$dashboard_page_id = get_option( 'wcvendors_dashboard_page_id' );
							$dashboard_page_id = is_array( $dashboard_page_id ) ? $dashboard_page_id[0] : $dashboard_page_id;
							if ( $dashboard_page_id ) {
								$account_link = get_permalink( $dashboard_page_id );
							}
						}

					} elseif ( class_exists( 'WC_Vendors' ) && in_array( 'vendor', $author->roles ) ) {
						if ( ! empty( martfury_get_option( 'vendor_logged_link' ) ) ) {
							$account_link = martfury_get_option( 'vendor_logged_link' );
						} else {
							$vendor_dashboard_page = get_option( 'wcvendors_vendor_dashboard_page_id' );
							$account_link          = get_permalink( $vendor_dashboard_page );
						}

					} elseif ( class_exists( 'MVX' ) && in_array( 'dc_vendor', $author->roles ) ) {
						if ( ! empty( martfury_get_option( 'vendor_logged_link' ) ) ) {
							$account_link = martfury_get_option( 'vendor_logged_link' );
						} else {
							if ( function_exists( 'mvx_vendor_dashboard_page_id' ) && mvx_vendor_dashboard_page_id() ) {
								$account_link = get_permalink( mvx_vendor_dashboard_page_id() );
							}
						}
					} elseif ( class_exists( 'WCFM' ) && in_array( 'wcfm_vendor', $author->roles ) ) {
						if ( ! empty( martfury_get_option( 'vendor_logged_link' ) ) ) {
							$account_link = martfury_get_option( 'vendor_logged_link' );
						} else {
							$pages = get_option( "wcfm_page_options" );
							if ( isset( $pages['wc_frontend_manager_page_id'] ) && $pages['wc_frontend_manager_page_id'] ) {
								$account_link = get_permalink( $pages['wc_frontend_manager_page_id'] );
							}
						}
					}
				}

				echo sprintf( '<a href="%s" class="navigation-icon navigation-mobile_account"><i class="icon-user"></i> %s</a>', esc_url( $account_link ), esc_html( martfury_get_option( 'navigation_account_mobile' ) ) );
			}
		}
		?>
    </div>
</div>