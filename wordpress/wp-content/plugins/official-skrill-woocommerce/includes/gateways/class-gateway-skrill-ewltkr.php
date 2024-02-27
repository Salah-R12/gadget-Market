<?php
/**
 * Skrill Paysafecard
 *
 * This gateway is used for Skrill Paysafecard.
 * Copyright (c) Skrill
 *
 * @class   Gateway_Skrill_BLK
 * @extends Skrill_Payment_Gateway
 * @package Skrill/Classes
 * @located at  /includes/gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Gateway_Skrill_BLK
 */
class Gateway_Skrill_EWLTKR extends Skrill_Payment_Gateway {


	/**
	 * Id
	 *
	 * @var string
	 */
	public $id = 'skrill_ewltkr';

	/**
	 * Payment method logo
	 *
	 * @var string
	 */
	public $payment_method_logo = 'ewltkr.png';

	/**
	 * Payment method
	 *
	 * @var string
	 */
	public $payment_method = 'RER';

	/**
	 * Payment brand
	 *
	 * @var string
	 */
	public $payment_brand = 'RER';

	/**
	 * Allowed countries
	 *
	 * @var array
	 */
	protected $allowed_countries = array( 'KOR' );

	/**
	 * Payment method description
	 *
	 * @var string
	 */
	public $payment_method_description = 'South Korea';

	/**
	 * Get payment title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'E-wallet South Korea', 'wc-skrill' );
	}
}

$obj = new Gateway_Skrill_EWLTKR();
