<?php
namespace WCBoost\ProductsCompare;

/**
 * Plugin main class
 */
final class Plugin {

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	public $version = '1.0.3';

	/**
	 * The product list to compare
	 *
	 * @var Compare_List
	 */
	public $list;

	/**
	 * The single instance of the class.
	 *
	 * @var \WCBoost\ProductsCompare\Plugin
	 */
	protected static $_instance = null;

	/**
	 * Main instance. Ensures only one instance of the plugin class is loaded or can be loaded.
	 *
	 * @static
	 * @return \WCBoost\ProductsCompare\Plugin
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Cloning is forbidden.
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Foul!', 'wcboost-products-compare' ), '1.0.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Foul!', 'wcboost-products-compare' ), '1.0.0' );
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->includes();
		$this->init();
	}

	/**
	 * Plugin URL getter.
	 *
	 * @return string
	 */
	public function plugin_url( $path = '/' ) {
		return untrailingslashit( plugins_url( $path, dirname( __FILE__ ) ) );

	}

	/**
	 * Plugin path getter.
	 *
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( dirname( __FILE__ ) ) );
	}

	/**
	 * Plugin base name
	 *
	 * @return string
	 */
	public function plugin_basename() {
		return dirname( plugin_basename( __FILE__ ), 2 ) . '/wcboost-products-compare.php';
	}

	/**
	 * Load files
	 *
	 * @return void
	 */
	protected function includes() {
		include_once 'admin/settings.php';
		include_once 'helper.php';
		include_once 'form-handler.php';
		include_once 'ajax-handler.php';
		include_once 'compare-list.php';
		include_once 'frontend.php';
		include_once 'shortcodes.php';
		include_once 'compatibility.php';
		include_once 'customizer.php';
		include_once 'widgets/products-compare.php';
	}

	/**
	 * Initialize the plugin
	 *
	 * @return void
	 */
	protected function init() {
		$this->init_hooks();

		Install::init();
		Form_Handler::init();
		Ajax_Handler::init();
		Shortcodes::init();

		Frontend::instance();
	}

	/**
	 * Core hooks to run the plugin
	 */
	protected function init_hooks() {
		add_action( 'init', [ $this, 'load_translation' ] );
		add_action( 'init', [ $this, 'initialize_list' ] );

		add_action( 'widgets_init', [ $this, 'register_widgets' ] );

		add_filter( 'woocommerce_get_compare_page_id', [ $this, 'compare_page_id' ] );
	}

	/**
	 * Load textdomain.
	 */
	public function load_translation() {
		load_plugin_textdomain( 'wcboost-products-compare', false, dirname( plugin_basename( __FILE__ ), 2 ) . '/languages/' );
	}

	/**
	 * Initialize the list of compare products
	 *
	 * @return void
	 */
	public function initialize_list() {
		$this->list = new Compare_List();
	}

	/**
	 * Empty product list.
	 * Initialize a new list of compare products.
	 *
	 * @param  bool $reset_db
	 * @return void
	 */
	public function empty_list( $reset_db = false ) {
		$this->list->empty( $reset_db );

		if ( $reset_db ) {
			$this->initialize_list();
		}
	}

	/**
	 * Register widgets
	 *
	 * @return void
	 */
	public function register_widgets() {
		register_widget( '\WCBoost\ProductsCompare\Widget\Products_Compare_Widget' );
	}

	/**
	 * Get the compare page id
	 *
	 * @return int
	 */
	public function compare_page_id() {
		$page_id = get_option( 'wcboost_products_compare_page_id' );
		$page_id = apply_filters( 'wpml_object_id', $page_id, 'page', false, null );

		return $page_id;
	}
}
