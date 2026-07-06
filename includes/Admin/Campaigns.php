<?php
/**
 * Campaigns Controller
 *
 * @package HSGCampaignManager
 */

namespace HSGCM\Admin;

use HSGCM\Campaign\CampaignRepository;

defined( 'ABSPATH' ) || exit;

final class Campaigns {

	/**
	 * Campaign repository.
	 *
	 * @var CampaignRepository
	 */
	private CampaignRepository $repository;

	/**
	 * Constructor.
	 */
	public function __construct() {

		$this->repository = new CampaignRepository();

	}

	/**
	 * Render campaigns page.
	 *
	 * @return void
	 */
	public function render(): void {

		$data = array(
			'campaigns' => $this->repository->all(),
			'total'     => $this->repository->count(),
		);

		$template = HSGCM_PATH . 'templates/admin/campaigns.php';

		if ( ! file_exists( $template ) ) {

			printf(
				'<div class="notice notice-error"><p>%s</p></div>',
				esc_html__(
					'Campaign template could not be found.',
					'hsg-campaign-manager'
				)
			);

			return;

		}

		include $template;

	}

}
