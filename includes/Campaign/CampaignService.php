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
	 * @param array $data Campaign data.
	 *
	 * @return array
	 */
	public function save( array $data ): array {

		$data = $this->sanitize( $data );

		$validation = $this->validate( $data );

		if ( ! $validation['success'] ) {
			return $validation;
		}

		$id = (int) ( $data['id'] ?? 0 );

		if ( $id > 0 ) {

			if ( ! $this->repository->update( $id, $data ) ) {

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

		$new_id = $this->repository->create( $data );

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
			'id'      => $new_id,
			'message' => __( 'Campaign duplicated.', 'hsg-campaign-manager' ),
		);

	}

	/**
	 * Sanitize input.
	 *
	 * @param array $data Raw data.
	 *
	 * @return array
	 */
	private function sanitize( array $data ): array {

		return array(
			'id'     => absint( $data['id'] ?? 0 ),
			'title'  => sanitize_text_field( $data['title'] ?? '' ),
			'status' => in_array(
				$data['status'] ?? 'draft',
				array( 'draft', 'publish' ),
				true
			) ? $data['status'] : 'draft',
			'coupon' => sanitize_text_field( $data['coupon'] ?? '' ),
			'price'  => wc_format_decimal( $data['price'] ?? '' ),
			'start'  => sanitize_text_field( $data['start'] ?? '' ),
			'end'    => sanitize_text_field( $data['end'] ?? '' ),
		);

	}

	/**
	 * Validate campaign.
	 *
	 * @param array $data Campaign data.
	 *
	 * @return array
	 */
	private function validate( array $data ): array {

		if ( '' === trim( $data['title'] ) ) {

			return array(
				'success' => false,
				'message' => __( 'Campaign name is required.', 'hsg-campaign-manager' ),
			);

		}

		if (
			'' !== $data['start'] &&
			'' !== $data['end'] &&
			strtotime( $data['start'] ) > strtotime( $data['end'] )
		) {

			return array(
				'success' => false,
				'message' => __( 'Start date must be before end date.', 'hsg-campaign-manager' ),
			);

		}

		return array(
			'success' => true,
		);

	}

}
