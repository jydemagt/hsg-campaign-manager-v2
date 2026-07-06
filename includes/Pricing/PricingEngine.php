<?php
/**
 * Pricing Engine
 *
 * @package HSGCampaignManager
 */

namespace HSGCM\Pricing;

defined( 'ABSPATH' ) || exit;

final class PricingEngine {

	/**
	 * Resolver.
	 *
	 * @var CampaignResolver
	 */
	private CampaignResolver $resolver;

	/**
	 * Constructor.
	 */
	public function __construct() {

		$this->resolver = new CampaignResolver();

	}

	/**
	 * Calculate campaign price.
	 *
	 * @param int   $product_id Product ID.
	 * @param float $price      Original price.
	 *
	 * @return float
	 */
	public function calculate(
		int $product_id,
		float $price
	): float {

		$campaigns = $this->resolver->find_for_product(
			$product_id
		);

		if ( empty( $campaigns ) ) {
			return $price;
		}

		$new_price = $price;

		foreach ( $campaigns as $campaign ) {

			/*
			 * Fixed price.
			 */
			if (
				! empty( $campaign['price'] )
			) {

				$new_price = min(
					$new_price,
					(float) $campaign['price']
				);

			}

		}

		return $new_price;

	}

}
