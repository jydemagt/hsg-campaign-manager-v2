<?php
/**
 * Campaign Form Controller
 *
 * @package HSGCampaignManager
 */

namespace HSGCM\Admin;

use HSGCM\Campaign\CampaignRepository;

defined( 'ABSPATH' ) || exit;

final class CampaignForm {

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
	 * Render campaign form.
	 *
	 * @param int|null $campaign_id Campaign ID.
	 *
	 * @return void
	 */
	public function render( ?int $campaign_id = null ): void {

		$campaign = null;

		if ( null !== $campaign_id && $campaign_id > 0 ) {
			$campaign = $this->repository->find( $campaign_id );
		}

		$data = array(
			'campaign' => $campaign,
		);

		$template = HSGCM_PATH . 'templates/admin/campaign-form.php';

		if ( ! file_exists( $template ) ) {

			printf(
				'<div class="notice notice-error"><p>%s</p></div>',
				esc_html__(
					'Campaign form template could not be found.',
					'hsg-campaign-manager'
				)
			);

			return;

		}

		include $template;

	}

}
