<?php
/**
 * AJAX Controller
 *
 * @package HSGCampaignManager
 */

namespace HSGCM\Admin;

use HSGCM\Campaign\CampaignRepository;
use HSGCM\Campaign\CampaignSchema;
use HSGCM\Campaign\CampaignService;

defined( 'ABSPATH' ) || exit;

final class AjaxController {

	/**
	 * Repository.
	 *
	 * @var CampaignRepository
	 */
	private CampaignRepository $repository;

	/**
	 * Service.
	 *
	 * @var CampaignService
	 */
	private CampaignService $service;

	/**
	 * Constructor.
	 */
	public function __construct() {

		$this->repository = new CampaignRepository();
		$this->service    = new CampaignService();

		add_action( 'wp_ajax_hsgcm_get_campaign', array( $this, 'get_campaign' ) );
		add_action( 'wp_ajax_hsgcm_save_campaign', array( $this, 'save_campaign' ) );
		add_action( 'wp_ajax_hsgcm_delete_campaign', array( $this, 'delete_campaign' ) );
		add_action( 'wp_ajax_hsgcm_duplicate_campaign', array( $this, 'duplicate_campaign' ) );

	}

	/**
	 * Verify request.
	 *
	 * @return void
	 */
	private function verify(): void {

		check_ajax_referer( 'hsgcm_admin', 'nonce' );

		if ( ! current_user_can( 'manage_woocommerce' ) ) {

			wp_send_json_error(
				array(
					'message' => __( 'Permission denied.', 'hsg-campaign-manager' ),
				),
				403
			);

		}

	}

	/**
	 * Get campaign.
	 *
	 * @return void
	 */
	public function get_campaign(): void {

		$this->verify();

		$id = absint( $_POST['id'] ?? 0 );

		$campaign = $this->repository->find( $id );

		if ( null === $campaign ) {

			wp_send_json_error(
				array(
					'message' => __( 'Campaign not found.', 'hsg-campaign-manager' ),
				)
			);

		}

		wp_send_json_success(
			array(
				'campaign' => $campaign,
			)
		);

	}

	/**
	 * Save campaign.
	 *
	 * @return void
	 */
	public function save_campaign(): void {

		$this->verify();

		$campaign = $_POST['campaign'] ?? array();

		if ( ! is_array( $campaign ) ) {
			$campaign = array();
		}

		$campaign = CampaignSchema::normalize( wp_unslash( $campaign ) );

		$result = $this->service->save( $campaign );

		if ( ! $result['success'] ) {
			wp_send_json_error( $result );
		}

		wp_send_json_success( $result );

	}

	/**
	 * Delete campaign.
	 *
	 * @return void
	 */
	public function delete_campaign(): void {

		$this->verify();

		$result = $this->service->delete(
			absint( $_POST['id'] ?? 0 )
		);

		if ( ! $result['success'] ) {
			wp_send_json_error( $result );
		}

		wp_send_json_success( $result );

	}

	/**
	 * Duplicate campaign.
	 *
	 * @return void
	 */
	public function duplicate_campaign(): void {

		$this->verify();

		$result = $this->service->duplicate(
			absint( $_POST['id'] ?? 0 )
		);

		if ( ! $result['success'] ) {
			wp_send_json_error( $result );
		}

		wp_send_json_success( $result );

	}

}
