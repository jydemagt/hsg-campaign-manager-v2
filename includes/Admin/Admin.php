<?php
/**
 * Product Selector
 *
 * @package HSGCampaignManager
 */

namespace HSGCM\Admin;

defined( 'ABSPATH' ) || exit;

final class ProductSelector {

	/**
	 * Render selector.
	 *
	 * @param array $selected Selected product IDs.
	 *
	 * @return void
	 */
	public function render( array $selected = array() ): void {

		?>

		<div class="hsgcm-field">

			<label for="hsgcm-products">

				<?php esc_html_e(
					'Products',
					'hsg-campaign-manager'
				); ?>

			</label>

			<select
				id="hsgcm-products"
				name="products[]"
				class="wc-product-search"
				multiple="multiple"
				style="width:100%;"
				data-placeholder="<?php esc_attr_e(
					'Search products...',
					'hsg-campaign-manager'
				); ?>"
				data-action="woocommerce_json_search_products_and_variations">

				<?php

				foreach ( $selected as $product_id ) {

					$product = wc_get_product( $product_id );

					if ( ! $product ) {
						continue;
					}

					printf(
						'<option value="%d" selected="selected">%s</option>',
						$product->get_id(),
						esc_html( $product->get_formatted_name() )
					);

				}

				?>

			</select>

			<p class="description">

				<?php esc_html_e(
					'Choose one or more products.',
					'hsg-campaign-manager'
				); ?>

			</p>

		</div>

		<?php

	}

}
