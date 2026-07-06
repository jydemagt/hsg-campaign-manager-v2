<?php
/**
 * Campaign Schema
 *
 * @package HSGCampaignManager
 */

namespace HSGCM\Campaign;

defined( 'ABSPATH' ) || exit;

final class CampaignSchema {

	/**
	 * Return empty campaign.
	 *
	 * @return array
	 */
	public static function defaults(): array {

		return array(

			'general' => array(

				'title'       => '',
				'status'      => 'draft',
				'priority'    => 10,
				'start_date'  => '',
				'end_date'    => '',

			),

			'conditions' => array(

				'products'       => array(),
				'categories'     => array(),
				'product_tags'   => array(),
				'customer_roles' => array(),

			),

			'rule' => array(

				'type'  => 'fixed_price',
				'value' => '',

			),

			'coupon' => array(

				'enabled' => false,
				'code'    => '',

			),

			'advanced' => array(

				'stop_processing' => false,
				'usage_limit'     => '',
				'notes'           => '',

			),

		);

	}

	/**
	 * Merge campaign with defaults.
	 *
	 * @param array $campaign Campaign data.
	 *
	 * @return array
	 */
	public static function normalize( array $campaign ): array {

		return array_replace_recursive(
			self::defaults(),
			$campaign
		);

	}

}
