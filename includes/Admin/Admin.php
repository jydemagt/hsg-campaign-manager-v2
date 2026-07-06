<?php
/**
 * Admin Controller
 *
 * @package HSGCampaignManager
 */

namespace HSGCM\Admin;

defined( 'ABSPATH' ) || exit;

final class Admin {

	/**
	 * Dashboard page.
	 *
	 * @var Dashboard
	 */
	private Dashboard $dashboard;

	/**
	 * Campaigns page.
	 *
	 * @var Campaigns
	 */
	private Campaigns $campaigns;

	/**
	 * Settings page.
	 *
	 * @var Settings
	 */
	private Settings $settings;

	/**
	 * Statistics page.
	 *
	 * @var Statistics
	 */
	private Statistics $statistics;

	/**
	 * Constructor.
	 */
	public function __construct() {

		$this->dashboard  = new Dashboard();
		$this->campaigns  = new Campaigns();
		$this->settings   = new Settings();
		$this->statistics = new Statistics();

		add_action(
			'admin_menu',
			array( $this, 'register_menu' )
		);

		add_action(
			'admin_enqueue_scripts',
			array( $this, 'enqueue_assets' )
		);

	}

	/**
	 * Register admin menu.
	 *
	 * @return void
	 */
	public function register_menu(): void {

		add_submenu_page(
			'woocommerce',
			__( 'Campaign Manager', 'hsg-campaign-manager' ),
			__( 'Campaign Manager', 'hsg-campaign-manager' ),
			'manage_woocommerce',
			'hsg-campaign-manager',
			array( $this, 'render' )
		);

	}

	/**
	 * Load CSS & JavaScript.
	 *
	 * @param string $hook Current admin page.
	 *
	 * @return void
	 */
	public function enqueue_assets( string $hook ): void {

		if ( false === strpos( $hook, 'hsg-campaign-manager' ) ) {
			return;
		}

		wp_enqueue_style(
			'hsgcm-admin',
			HSGCM_URL . 'assets/css/admin.css',
			array(),
			HSGCM_VERSION
		);

		wp_enqueue_script(
			'hsgcm-admin',
			HSGCM_URL . 'assets/js/admin.js',
			array( 'jquery' ),
			HSGCM_VERSION,
			true
		);

		wp_localize_script(
			'hsgcm-admin',
			'hsgcmAdmin',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'hsgcm_admin' ),
			)
		);

	}

	/**
	 * Render admin page.
	 *
	 * @return void
	 */
	public function render(): void {

		$tab = isset( $_GET['tab'] )
			? sanitize_key( wp_unslash( $_GET['tab'] ) )
			: 'dashboard';

		?>

		<div class="wrap hsgcm-admin">

			<h1 class="wp-heading-inline">

				HSG Campaign Manager

			</h1>

			<hr class="wp-header-end">

			<nav class="nav-tab-wrapper">

				<a
					href="?page=hsg-campaign-manager&tab=dashboard"
					class="nav-tab <?php echo 'dashboard' === $tab ? 'nav-tab-active' : ''; ?>">

					<?php esc_html_e( 'Dashboard', 'hsg-campaign-manager' ); ?>

				</a>

				<a
					href="?page=hsg-campaign-manager&tab=campaigns"
					class="nav-tab <?php echo 'campaigns' === $tab ? 'nav-tab-active' : ''; ?>">

					<?php esc_html_e( 'Campaigns', 'hsg-campaign-manager' ); ?>

				</a>

				<a
					href="?page=hsg-campaign-manager&tab=statistics"
					class="nav-tab <?php echo 'statistics' === $tab ? 'nav-tab-active' : ''; ?>">

					<?php esc_html_e( 'Statistics', 'hsg-campaign-manager' ); ?>

				</a>

				<a
					href="?page=hsg-campaign-manager&tab=settings"
					class="nav-tab <?php echo 'settings' === $tab ? 'nav-tab-active' : ''; ?>">

					<?php esc_html_e( 'Settings', 'hsg-campaign-manager' ); ?>

				</a>

			</nav>

			<div class="hsgcm-content">

				<?php

				switch ( $tab ) {

					case 'campaigns':
						$this->campaigns->render();
						break;

					case 'statistics':
						$this->statistics->render();
						break;

					case 'settings':
						$this->settings->render();
						break;

					default:
						$this->dashboard->render();
						break;

				}

				?>

			</div>

		</div>

		<?php

	}

}
