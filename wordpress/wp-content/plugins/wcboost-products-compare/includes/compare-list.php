<?php
namespace WCBoost\ProductsCompare;

/**
 * Compare products list
 */
class Compare_List {

	const SESSION_KEY = 'wcboost_products_compare_list';

	/**
	 * The list id
	 *
	 * @var string
	 */
	protected $id;

	/**
	 * The list of product ids
	 *
	 * @var array
	 */
	protected $items = [];

	/**
	 * Class constructor
	 */
	public function __construct( $product_ids = [] ) {
		if ( ! empty( $product_ids ) ) {
			$this->load_products_manually( $product_ids );
		} else {
			$this->id = wc_rand_hash();
			$this->load_products_from_session();
		}
	}

	/**
	 * Get the list id
	 *
	 * @return string
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Get product list from WC()->session
	 *
	 * @return void
	 */
	protected function load_products_from_session() {
		if ( ! WC()->session ) {
			return;
		}

		$data = WC()->session->get( self::SESSION_KEY, [ 'id' => '', 'items' => [] ] );

		foreach ( $data['items'] as $product_id ) {
			$key = Helper::generate_item_key( $product_id );
			$this->items[ $key ] = $product_id;
		}

		if ( ! empty( $data['id'] ) ) {
			$this->id = $data['id'];
		}
	}

	/**
	 * Set the list data manually.
	 * Manully add products to the list.
	 *
	 * @param  array $product_ids
	 * @return void
	 */
	protected function load_products_manually( $product_ids ) {
		foreach ( $product_ids as $product_id ) {
			$product = wc_get_product( $product_id );

			if ( ! $product ) {
				continue;
			}

			$key = Helper::generate_item_key( $product_id );
			$this->items[ $key ] = $product_id;
		}
	}

	/**
	 * Get the list items.
	 *
	 * @return array
	 */
	public function get_items() {
		return $this->items;
	}

	/**
	 * Count the number of items
	 *
	 * @return int
	 */
	public function count_items() {
		return count( $this->items );
	}

	/**
	 * Add a new product to the list and update the session
	 *
	 * @param  int | WC_Product $product
	 * @return int | bool TRUE if successful, FALSE otherwise
	 */
	public function add_item( $product ) {
		$product_id = is_a( $product, 'WC_Product' ) ? $product->get_id() : $product;
		$key        = Helper::generate_item_key( $product_id );

		if ( ! $this->has_item( $product ) ) {
			$this->items[ $key ] = $product_id;

			// Update the session.
			$this->update();

			do_action( 'wcboost_products_compare_product_added', $product_id );

			return true;
		}

		return false;
	}

	/**
	 * Remove a product from the list.
	 *
	 * @param string $key
	 *
	 * @return int|bool The removed product ID if successful, FALSE otherwise.
	 */
	public function remove_item( $key ) {
		if ( array_key_exists( $key, $this->items ) ) {
			$product_id = $this->items[ $key ];
			unset( $this->items[ $key ] );

			$this->update();

			do_action( 'wcboost_products_compare_product_removed' );

			return $product_id;
		}

		return false;
	}

	/**
	 * Empty the list.
	 * Also reset the ID to create a new list.
	 *
	 * @param  bool $reset_db Reset data in the database
	 * @return void
	 */
	public function empty( $reset_db = false ) {
		$this->items = [];

		if ( $reset_db ) {
			$this->id = '';
			$this->delete();
		}
	}

	/**
	 * Check if a product exist in the list
	 *
	 * @param  int | \WC_Product $product
	 * @return bool
	 */
	public function has_item( $product ) {
		$product_id = is_a( $product, 'WC_Product' ) ? $product->get_id() : $product;

		return in_array( $product_id, $this->items );
	}

	/**
	 * Check if the list is empty
	 *
	 * @return bool
	 */
	public function is_empty() {
		return $this->count_items() ? false : true;
	}

	/**
	 * Update the session.
	 * Just update the product ids to the session.
	 *
	 * @return void
	 */
	private function update() {
		// Initialize the customer session if it is not already initialized.
		if ( WC()->session && ! WC()->session->has_session() ) {
			WC()->session->set_customer_session_cookie( true );
		}

		if ( $this->id ) {
			WC()->session->set( self::SESSION_KEY, [
				'id'    => $this->id,
				'items' => array_values( $this->items ),
			] );
		}
	}

	/**
	 * Delete the list data from the database.
	 *
	 * @return void
	 */
	private function delete() {
		// Initialize the customer session if it is not already initialized.
		if ( WC()->session && WC()->session->has_session() ) {
			WC()->session->set( self::SESSION_KEY, null );
		}
	}
}
