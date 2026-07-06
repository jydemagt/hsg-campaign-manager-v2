<?php
/**
 * Plugin Bootstrap
 *
 * @package HSGCampaignManager
 */

namespace HSGCM\Core;

use HSGCM\Admin\Admin;
use HSGCM\Admin\AjaxController;
use HSGCM\Campaign\Campaign;
use HSGCM\Campaign\CampaignRepository;
use HSGCM\Campaign\CampaignService;
use HSGCM\Pricing\Pricing;
use HSGCM\Coupons\Coupons;

defined( 'ABSPATH' ) || exit;

/**
 * Main plugin bootstrap.
 */
final class Plugin {

	/**
	 * Singleton instance.
	 *
	 * @var Plugin|null
	 */
	private static ?Plugin $instance = null;

	/**
	 * Get plugin instance.
	 *
	 * @return Plugin
	 */
	public static function instance(): Plugin {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Constructor.
	 */
	private function __construct() {

		add_action(
			'plugins_loaded',
			array( $this, 'boot' ),
			20
		);

	}

	/**
	 * Boot plugin.
	 *
	 * @return void
	 */
	public function boot(): void {

		// WooCommerce must be active.
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		$this->load_modules();

	}

	/**
	 * Load plugin modules.
	 *
	 * @return void
	 */
	private function load_modules(): void {

		/*
		 * Campaign Core
		 */
		new Campaign();
		new CampaignRepository();
		new CampaignService();

		/*
		 * Admin
		 */
		new Admin();
		new AjaxController();

		/*
		 * Pricing
		 */
		new Pricing();

		/*
		 * Coupons
		 */
		new Coupons();

	}

}
