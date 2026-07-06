<?php
/**
 * Campaign Resolver
 *
 * @package HSGCampaignManager
 */

namespace HSGCM\Pricing;

use HSGCM\Campaign\CampaignRepository;

defined( 'ABSPATH' ) || exit;

final class CampaignResolver {

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
	 * Find campaigns for product.
	 *
	 * @param int $product_id Product ID.
	 *
	 * @return array
	 */
	public function find_for_product( int $product_id ): array {

		$result = array();

		$campaigns = $this->repository->all();

		foreach ( $campaigns as $campaign ) {

			$data = $this->repository->get_campaign_data(
				$campaign->ID
			);

			/*
			 * Status
			 */

			if ( 'publish' !== $data['status'] ) {
				continue;
			}

			/*
			 * Date
			 */

			$today = current_time( 'Y-m-d' );

			if (
				! empty( $data['start'] ) &&
				$data['start'] > $today
			) {
				continue;
			}

			if (
				! empty( $data['end'] ) &&
				$data['end'] < $today
			) {
				continue;
			}

			/*
			 * Products
			 */

			if (
				! empty( $data['products'] ) &&
				! in_array(
					$product_id,
					$data['products'],
					true
				)
			) {
				continue;
			}

			$result[] = $data;

		}

		return $result;

	}

}
