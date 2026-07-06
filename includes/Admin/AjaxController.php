<?php
/**
 * AJAX Controller
 *
 * @package HSGCampaignManager
 */

namespace HSGCM\Admin;

use HSGCM\Campaign\CampaignRepository;
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

		$data = $this->repository->get_campaign_data( $id );

		if ( empty( $data ) ) {

			wp_send_json_error(
				array(
					'message' => __( 'Campaign not found.', 'hsg-campaign-manager' ),
				)
			);

		}

		wp_send_json_success( $data );

	}

	/**
	 * Save campaign.
	 *
	 * @return void
	 */
	public function save_campaign(): void {

		$this->verify();

		$result = $this->service->save(
			array(
				'id'       => absint( $_POST['id'] ?? 0 ),
				'title'    => sanitize_text_field( wp_unslash( $_POST['title'] ?? '' ) ),
				'status'   => sanitize_text_field( wp_unslash( $_POST['status'] ?? 'draft' ) ),
				'coupon'   => sanitize_text_field( wp_unslash( $_POST['coupon'] ?? '' ) ),
				'price'    => wp_unslash( $_POST['price'] ?? '' ),
				'start'    => sanitize_text_field( wp_unslash( $_POST['start'] ?? '' ) ),
				'end'      => sanitize_text_field( wp_unslash( $_POST['end'] ?? '' ) ),
				'products' => array_map(
					'absint',
					(array) ( $_POST['products'] ?? array() )
				),
			)
		);

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
