<?php
/**
 * PSR-4 Autoloader
 *
 * @package HSGCampaignManager
 */

namespace HSGCM\Core;

defined( 'ABSPATH' ) || exit;

final class Loader {

	/**
	 * Register autoloader.
	 *
	 * @return void
	 */
	public static function register(): void {

		spl_autoload_register(
			array( self::class, 'autoload' )
		);

	}

	/**
	 * Autoload plugin classes.
	 *
	 * @param string $class Class name.
	 *
	 * @return void
	 */
	private static function autoload( string $class ): void {

		$prefix = 'HSGCM\\';

		if ( strpos( $class, $prefix ) !== 0 ) {
			return;
		}

		$relative = substr(
			$class,
			strlen( $prefix )
		);

		$file = HSGCM_PATH .
			'includes/' .
			str_replace(
				'\\',
				DIRECTORY_SEPARATOR,
				$relative
			) .
			'.php';

		if ( is_readable( $file ) ) {
			require_once $file;
		}

	}

}
