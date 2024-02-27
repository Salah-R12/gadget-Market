<?php
namespace WCBoost\ProductsCompare;

use WCBoost\ProductsCompare\Helper;

/**
 * Frontend class
 */
class Frontend {

	/**
	 * The single instance of the class.
	 * @var Frontend
	 */
	protected static $_instance = null;

	/**
	 * Main instance.
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @static
	 * @return Frontend
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Class constructor
	 */
	public function __construct() {
		add_action( 'wp', [ $this, 'template_hooks' ] );
		add_action( 'wp', [ $this, 'add_nocache_headers' ] );
		add_filter( 'wp_robots', [ $this, 'add_noindex_robots' ], 20 );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	/**
	 * Template hooks
	 */
	public function template_hooks() {
		add_filter( 'body_class', [ $this, 'body_class' ] );

		add_action( 'wcboost_products_compare_before_content', [ $this, 'print_notices' ], 5 );

		// Compare button.
		add_action( 'woocommerce_after_add_to_cart_form', [ $this, 'single_add_to_compare_button' ] );
		add_action( 'woocommerce_after_shop_loop_item', [ $this, 'loop_add_to_compare_button' ], 15 );

		// Compate page.
		add_action( 'wcboost_products_compare_content', [ $this, 'compare_content' ] );

		// Clear list button.
		add_action( 'wcboost_products_compare_after_content', [ $this, 'compare_footer' ] );

		// Popup.
		add_action( 'wp_footer', [ $this, 'compare_popup' ] );

		// Widget buttons.
		add_action( 'wcboost_products_compare_widget_buttons', [ $this, 'compare_button_open' ], 10 );
		add_action( 'wcboost_products_compare_widget_buttons', [ $this, 'compare_button_clear' ], 20 );

		// Compare bar.
		if ( get_option( 'wcboost_products_compare_bar' ) && ! Helper::is_compare_page() ) {
			add_action( 'wp_footer', [ $this, 'compare_bar' ] );
		}
	}

	/**
	 * Add nocache headers.
	 * Prevent caching on the compare page
	 */
	public function add_nocache_headers() {
		if ( ! headers_sent() && Helper::is_compare_page() ) {
			wc_nocache_headers();
		}
	}

	/**
	 * Tell search engines stop indexing the URL with add_to_compare param.
	 *
	 * @param array $robots
	 * @return array
	 */
	public function add_noindex_robots( $robots ) {
		if ( ! isset( $_GET['add_to_compare'] ) ) {
			return $robots;
		}

		return wp_robots_no_robots( $robots );
	}

	/**
	 * Enqueue styles and scripts
	 */
	public function enqueue_scripts() {
		$plugin = Plugin::instance();
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_style( 'wcboost-products-compare', $plugin->plugin_url( '/assets/css/compare.css' ), [], $plugin->version );

		wp_enqueue_script( 'wcboost-products-compare', $plugin->plugin_url( '/assets/js/compare' . $suffix . '.js' ), [ 'jquery' ], $plugin->version, true );
		wp_localize_script(
			'wcboost-products-compare',
			'wcboost_products_compare_params',
			apply_filters( 'wcboost_products_compare_params', [
				'page_url'             => apply_filters( 'wcboost_products_compares_add_to_list_redirect', wc_get_page_permalink( 'compare' ), null ),
				'added_behavior'       => get_option( 'wcboost_products_compare_added_behavior' ),
				'exists_item_behavior' => get_option( 'wcboost_products_compare_exists_item_button_behaviour', 'remove' ),
				'icon_normal'          => Helper::get_compare_icon( false ),
				'icon_checked'         => Helper::get_compare_icon( true ),
				'icon_loading'         => Helper::get_icon( 'spinner' ),
				'i18n_button_add'      => Helper::get_button_text( 'add' ),
				'i18n_button_remove'   => Helper::get_button_text( 'remove' ),
				'i18n_button_view'     => Helper::get_button_text( 'view' ),
			] )
		);

		wp_enqueue_script( 'wcboost-products-compare-fragments', $plugin->plugin_url( '/assets/js/compare-fragments' . $suffix . '.js' ), [ 'jquery' ], $plugin->version, true );
		wp_localize_script( 'wcboost-products-compare-fragments', 'wcboost_products_compare_fragments_params', [
			'refresh_on_load' => get_option( 'wcboost_products_compare_ajax_bypass_cache', defined( 'WP_CACHE' ) && WP_CACHE ? 'yes' : 'no' ),
			'timeout'         => apply_filters( 'wcboost_wishlist_ajax_timeout', 5000 ),
		] );
	}

	/**
	 * Add CSS classes to the body element on the compare page
	 *
	 * @param array $classes
	 * @return array
	 */
	public function body_class( $classes ) {
		if ( Helper::is_compare_page() ) {
			$classes[] = 'woocommerce-page';
			$classes[] = 'woocommerce-products-compare';
		}

		return $classes;
	}

	/**
	 * Display notices.
	 * Need the additional check to avoid errors with live editor like Elementor.
	 *
	 * @return void
	 */
	public function print_notices() {
		if ( WC()->session ) {
			wc_print_notices();
		}
	}

	/**
	 * Display the compare button on the single product page.
	 *
	 * @return void
	 */
	public function single_add_to_compare_button() {
		$args = $this->get_button_template_args();
		$args['class'] .= ' wcboost-products-compare-button--single';

		wc_get_template( 'single-product/add-to-compare.php', $args, '', Plugin::instance()->plugin_path() . '/templates/' );
	}

	/**
	 * Display the Compare button on catalog pages.
	 *
	 * @return void
	 */
	public function loop_add_to_compare_button() {
		$args = $this->get_button_template_args();
		$args['class'] .= ' wcboost-products-compare-button--loop';

		wc_get_template( 'loop/add-to-compare.php', $args, '', Plugin::instance()->plugin_path() . '/templates/' );
	}

	/**
	 * Get the button template args.
	 *
	 * @param \WC_Product $product
	 * @return array
	 */
	public function get_button_template_args( $product = false ) {
		$product = $product ? $product : $GLOBALS['product'];
		$list    = Plugin::instance()->list;

		$args = [
			'product_id' => $product->get_id(),
			'class'      => [ 'wcboost-products-compare-button' ],
			'label'      => Helper::get_button_text( 'add' ),
			'aria-label' => sprintf( __( 'Compare %s', 'wcboost-products-compare' ), '&ldquo;' . $product->get_title() . '&rdquo;' ),
			'url'        => Helper::get_add_url( $product ),
		];

		if ( apply_filters( 'wcboost_products_compare_button_uses_ajax', true ) ) {
			$args['class'][] = 'wcboost-products-compare-button--ajax';
		}

		if ( $list && $list->has_item( $product ) ) {
			$args['class'][] = 'added';
			$args['icon']    = Helper::get_compare_icon( true );

			switch ( get_option( 'wcboost_products_compare_exists_item_button_behaviour', 'remove' ) ) {
				case 'hide':
					$args['class'][] = 'hidden';
					break;

				case 'remove':
					$args['url']        = Helper::get_remove_url( $product );
					$args['aria-label'] = sprintf( __( 'Remove %s from the compare list', 'wcboost-products-compare' ), '&ldquo;' . $product->get_title() . '&rdquo;' );
					$args['label']      = Helper::get_button_text( 'remove' );
					break;

				case 'view':
					$args['url']        = wc_get_page_permalink( 'compare' );
					$args['aria-label'] = __( 'Open the compare list', 'wcboost-products-compare' );
					$args['label']      = Helper::get_button_text( 'view' );
					break;

				case 'popup':
					$args['url']        = wc_get_page_permalink( 'compare' );
					$args['aria-label'] = __( 'Open the compare list', 'wcboost-products-compare' );
					$args['label']      = Helper::get_button_text( 'view' );
					$args['class'][]    = 'wcboost-products-compare-button--popup';
					break;
			}
		} else {
			$args['icon']  = Helper::get_compare_icon( false );
		}

		$args = apply_filters( 'wcboost_products_compare_button_template_args', $args, $product );

		if ( in_array( 'button', $args['class'] ) ) {
			$args['class'][] = $this->get_element_class_name( 'button' );
		}

		$args['class'] = implode( ' ', (array) $args['class'] );

		return $args;
	}

	/**
	 * The content of the compare template
	 *
	 * @param Compare_list $list
	 * @return void
	 */
	public function compare_content( $list ) {
		$args = [
			'layout'         => apply_filters( 'wcboost_products_compare_layout', 'table' ),
			'compare_list'   => $list,
			'compare_items'  => [],
			'compare_fields' => $this->get_compare_fields(),
		];

		if ( ! $list || ! $list->count_items() ) {
			$template = 'compare/compare-empty.php';
			$args['return_url'] = apply_filters( 'wcboost_products_compare_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) );
		} else {
			$template = 'compare/compare-' . $args['layout'] . '.php';
			$args['compare_items'] = array_filter( array_map( 'wc_get_product', $list->get_items() ) );
		}

		wc_get_template( $template, $args, '', Plugin::instance()->plugin_path() . '/templates/' );
	}

	/**
	 * Display the compate page footer that performs some actions,
	 * such as the Clear button and Share button, etc.
	 *
	 * @param  Compare_List $list
	 * @return void
	 */
	public function compare_footer( $list ) {
		if ( ! $list->get_id() || ! $list->count_items() ) {
			return;
		}
		?>
		<div class="wcboost-products-compare__tools">
			<?php $this->compare_button_clear(); ?>
		</div>
		<?php
	}

	/**
	 * The button to clear compare list.
	 * This button is used for the default list only.
	 *
	 * @return void
	 */
	public function compare_button_clear( $args = [] ) {
		if ( Plugin::instance()->list->is_empty() ) {
			return;
		}

		$args = wp_parse_args( $args, [
			'class' => [ 'wcboost-products-compare-clear', 'button' ],
		] );
		$args = apply_filters( 'wcboost_products_compare_clear_link_args', $args );

		if ( in_array( 'button', $args['class'] ) ) {
			$args['class'][] = $this->get_element_class_name( 'button' );
		}

		echo apply_filters(
			'wcboost_products_compare_clear_link', // XSS: ok.
			sprintf(
				'<a href="%s" class="%s">%s</a>',
				esc_url( Helper::get_clear_url() ),
				esc_attr( implode( ' ', $args['class'] ) ),
				esc_html__( 'Clear list', 'wcboost-products-compare' )
			)
		);
	}

	/**
	 * The button to open the compare page/popup.
	 * This button is used for the default list only.
	 *
	 * @return void
	 */
	public function compare_button_open( $args = [] ) {
		if ( Plugin::instance()->list->is_empty() ) {
			return;
		}

		if ( ! wc_get_page_id( 'compare' ) ) {
			return;
		}

		$args = wp_parse_args( $args, [
			'class' => [ 'wcboost-products-compare-open', 'button', 'alt' ],
		] );
		$args = apply_filters( 'wcboost_products_compare_open_link_args', $args );

		if ( in_array( 'button', $args['class'] ) ) {
			$args['class'][] = $this->get_element_class_name( 'button' );
		}

		echo apply_filters(
			'wcboost_products_compare_open_link', // XSS: ok.
			sprintf(
				'<a href="%s" class="%s">%s</a>',
				esc_url( wc_get_page_permalink( 'compare' ) ),
				esc_attr( implode( ' ', $args['class'] ) ),
				esc_html( 'Compare now', 'wcboost-products-compare' )
			)
		);
	}

	/**
	 * Products compare popup.
	 * An empty container. The popup content will be populated by JS.
	 *
	 * @return void
	 */
	public function compare_popup() {
		$title = apply_filters( 'wcboost_products_compare_popup_title', __( 'Compare products', 'wcboost-products-compare' ) );
		?>
		<div id="wcboost-products-compare-popup" class="wcboost-products-compare-popup" aria-hidden="true">
			<div class="wcboost-products-compare-popup__backdrop"></div>
			<div class="wcboost-products-compare-popup__body">
				<div class="wcboost-products-compare-popup__header">
					<?php if ( $title ) : ?>
						<div class="wcboost-products-compare-popup__title"><?php echo esc_html( $title ); ?></div>
					<?php endif; ?>
					<a href="#" class="wcboost-products-compare-popup__close" role="button">
						<span class="wcboost-products-compare-popup__close-icon">
							<?php echo Helper::get_icon( 'close', 20 ); ?>
						</span>
						<span class="screen-reader-text"><?php esc_html_e( 'Close', 'wcboost-products-compare' ) ?></span>
					</a>
				</div>
				<div class="wcboost-products-compare-popup__content"></div>
			</div>
		</div>
		<?php
	}

	/**
	 * Products compare bar.
	 * The content wil be populated by JS.
	 *
	 * @return void
	 */
	public function compare_bar() {
		$position = get_option( 'wcboost_products_compare_bar' );

		if ( ! $position ) {
			return;
		}

		$behavior = get_option( 'wcboost_products_compare_bar_button_behavior', 'page' );
		$class    = [
			'wcboost-products-compare-bar',
			'wcboost-products-compare-bar--' . $position,
			'wcboost-products-compare-bar--trigger-' . $behavior,
		];
		?>
		<div id="wcboost-products-compare-bar" class="<?php echo esc_attr( implode( ' ', $class ) ); ?>" data-compare="<?php echo esc_attr( $behavior ); ?>" aria-hidden="true">
			<div class="wcboost-products-compare-bar__toggle">
				<span class="wcboost-products-compare-bar__toggle-button" role="button" aria-label="<?php esc_attr_e( 'View compared products', 'wcboost-products-compare' ); ?>">
					<?php echo Helper::get_icon( 'chevron-up' ); ?>
					<?php esc_html_e( 'Compare products', 'wcboost-products-compare' ); ?>
				</span>
			</div>

			<div class="wcboost-products-compare-bar__content">
				<?php Helper::widget_content(); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Fallback method for element class name.
	 *
	 * @param  string $element
	 * @return string
	 */
	public function get_element_class_name( $element ) {
		return function_exists( 'wp_theme_get_element_class_name' ) ? \wp_theme_get_element_class_name( $element ) : '';
	}

	/**
	 * Get fields for comparison
	 *
	 * @since 1.0.3
	 *
	 * @return array
	 */
	public function get_compare_fields() {
		$fields   = [
			'remove'      => '',
			'thumbnail'   => '',
			'name'        => '',
			'rating'      => '',
			'price'       => esc_html__( 'Price', 'wcboost-products-compare' ),
			'stock'       => esc_html__( 'Availability', 'wcboost-products-compare' ),
			'sku'         => esc_html__( 'SKU', 'wcboost-products-compare' ),
			'dimensions'  => esc_html__( 'Dimensions', 'wcboost-products-compare' ),
			'weight'      => esc_html__( 'Weight', 'wcboost-products-compare' ),
			'add-to-cart' => '',
		];

		if ( ! wc_review_ratings_enabled() ) {
			unset( $fields['rating'] );
		}

		if ( ! apply_filters( 'wc_product_enable_dimensions_display', true ) ) {
			unset( $fields['dimensions'] );
		}

		return apply_filters( 'wcboost_products_compare_fields', $fields );
	}
}
