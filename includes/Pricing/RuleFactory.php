<?php
/**
 * Rule Factory
 *
 * @package HSGCampaignManager
 */

namespace HSGCM\Pricing;

defined( 'ABSPATH' ) || exit;

final class RuleFactory {

	/**
	 * Supported rule types.
	 *
	 * @var string[]
	 */
	private const SUPPORTED = array(
		'fixed_price',
		'fixed_discount',
		'percentage',
	);

	/**
	 * Create a pricing rule.
	 *
	 * @param string $type  Rule type.
	 * @param float  $value Rule value.
	 *
	 * @return Rule
	 *
	 * @throws \InvalidArgumentException Invalid rule type.
	 */
	public static function create(
		string $type,
		float $value
	): Rule {

		if ( ! in_array( $type, self::SUPPORTED, true ) ) {

			throw new \InvalidArgumentException(
				sprintf(
					'Unknown pricing rule "%s".',
					$type
				)
			);

		}

		return new Rule(
			$type,
			$value
		);

	}

	/**
	 * Return supported rule types.
	 *
	 * @return array
	 */
	public static function supported(): array {

		return self::SUPPORTED;

	}

}
