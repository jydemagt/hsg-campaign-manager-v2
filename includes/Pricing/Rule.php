<?php
/**
 * Campaign Rule
 *
 * @package HSGCampaignManager
 */

namespace HSGCM\Pricing;

defined( 'ABSPATH' ) || exit;

final class Rule {

	/**
	 * Rule type.
	 *
	 * @var string
	 */
	private string $type;

	/**
	 * Rule value.
	 *
	 * @var float
	 */
	private float $value;

	/**
	 * Constructor.
	 *
	 * @param string $type  Rule type.
	 * @param float  $value Rule value.
	 */
	public function __construct(
		string $type,
		float $value
	) {

		$this->type  = $type;
		$this->value = $value;

	}

	/**
	 * Return rule type.
	 *
	 * @return string
	 */
	public function get_type(): string {

		return $this->type;

	}

	/**
	 * Return value.
	 *
	 * @return float
	 */
	public function get_value(): float {

		return $this->value;

	}

	/**
	 * Calculate new price.
	 *
	 * @param float $price Product price.
	 *
	 * @return float
	 */
	public function apply(
		float $price
	): float {

		switch ( $this->type ) {

			case 'fixed_price':

				return $this->value;

			case 'fixed_discount':

				return max(
					0,
					$price - $this->value
				);

			case 'percentage':

				return max(
					0,
					$price * (
						1 - ( $this->value / 100 )
					)
				);

			default:

				return $price;

		}

	}

}
