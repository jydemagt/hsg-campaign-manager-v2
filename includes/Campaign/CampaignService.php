<?php
/**
 * Campaign Service
 *
 * @package HSGCampaignManager
 */

namespace HSGCM\Campaign;

defined( 'ABSPATH' ) || exit;

final class CampaignService {

	/**
	 * Repository.
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
	 * Save campaign.
	 *
	 * @param array $campaign Campaign data.
	 *
	 * @return array
	 */
	public function save( array $campaign ): array {

		$campaign = CampaignSchema::normalize( $campaign );

		$validation = $this->validate( $campaign );

		if ( ! $validation['success'] ) {
			return $validation;
		}

		$id = absint( $campaign['id'] ?? 0 );

		if ( $id > 0 ) {

			if ( ! $this->repository->update( $id, $campaign ) ) {

				return array(
					'success' => false,
					'message' => __( 'Unable to update campaign.', 'hsg-campaign-manager' ),
				);

			}

			return array(
				'success' => true,
				'id'      => $id,
				'message' => __( 'Campaign updated.', 'hsg-campaign-manager' ),
			);

		}

		$new_id = $this->repository->create( $campaign );

		if ( is_wp_error( $new_id ) ) {

			return array(
				'success' => false,
				'message' => $new_id->get_error_message(),
			);

		}

		return array(
			'success' => true,
			'id'      => (int) $new_id,
			'message' => __( 'Campaign created.', 'hsg-campaign-manager' ),
		);

	}

	/**
	 * Delete campaign.
	 *
	 * @param int $id Campaign ID.
	 *
	 * @return array
	 */
	public function delete( int $id ): array {

		if ( ! $this->repository->delete( $id ) ) {

			return array(
				'success' => false,
				'message' => __( 'Unable to delete campaign.', 'hsg-campaign-manager' ),
			);

		}

		return array(
			'success' => true,
			'message' => __( 'Campaign deleted.', 'hsg-campaign-manager' ),
		);

	}

	/**
	 * Duplicate campaign.
	 *
	 * @param int $id Campaign ID.
	 *
	 * @return array
	 */
	public function duplicate( int $id ): array {

		$new_id = $this->repository->duplicate( $id );

		if ( ! $new_id ) {

			return array(
				'success' => false,
				'message' => __( 'Unable to duplicate campaign.', 'hsg-campaign-manager' ),
			);

		}

		return array(
			'success' => true,
			'id'      => (int) $new_id,
			'message' => __( 'Campaign duplicated.', 'hsg-campaign-manager' ),
		);

	}

	/**
	 * Validate campaign.
	 *
	 * @param array $campaign Campaign.
	 *
	 * @return array
	 */
	private function validate( array $campaign ): array {

		if ( empty( $campaign['general']['title'] ) ) {

			return array(
				'success' => false,
				'message' => __( 'Campaign name is required.', 'hsg-campaign-manager' ),
			);

		}

		$start = $campaign['general']['start_date'];
		$end   = $campaign['general']['end_date'];

		if (
			! empty( $start ) &&
			! empty( $end ) &&
			strtotime( $start ) > strtotime( $end )
		) {

			return array(
				'success' => false,
				'message' => __( 'Start date must be before end date.', 'hsg-campaign-manager' ),
			);

		}

		foreach ( $campaign['conditions']['products'] as $product_id ) {

			if ( 'product' !== get_post_type( $product_id ) ) {

				return array(
					'success' => false,
					'message' => sprintf(
						__( 'Invalid product ID: %d', 'hsg-campaign-manager' ),
						$product_id
					),
				);

			}

		}

		return array(
			'success' => true,
		);

	}

}
