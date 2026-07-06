<?php
/**
 * Campaign Form View
 *
 * @package HSGCampaignManager
 */

defined( 'ABSPATH' ) || exit;

$campaign = $data['campaign'] ?? null;

$id      = $campaign ? $campaign->ID : 0;
$title   = $campaign ? $campaign->post_title : '';
$status  = $campaign ? $campaign->post_status : 'draft';

$coupon = $campaign
	? get_post_meta( $campaign->ID, '_hsgcm_coupon', true )
	: '';

$price = $campaign
	? get_post_meta( $campaign->ID, '_hsgcm_price', true )
	: '';

$start = $campaign
	? get_post_meta( $campaign->ID, '_hsgcm_start_date', true )
	: '';

$end = $campaign
	? get_post_meta( $campaign->ID, '_hsgcm_end_date', true )
	: '';

?>

<div class="hsgcm-form">

	<h2 id="hsgcm-form-title">

		<?php
		echo $id > 0
			? esc_html__( 'Edit Campaign', 'hsg-campaign-manager' )
			: esc_html__( 'New Campaign', 'hsg-campaign-manager' );
		?>

	</h2>

	<form id="hsgcm-campaign-form">

		<input
			type="hidden"
			id="hsgcm-id"
			name="id"
			value="<?php echo esc_attr( $id ); ?>">

		<?php wp_nonce_field( 'hsgcm_admin', 'hsgcm_nonce' ); ?>

		<div class="hsgcm-field">

			<label for="hsgcm-title">

				<?php esc_html_e( 'Campaign Name', 'hsg-campaign-manager' ); ?>

			</label>

			<input
				type="text"
				id="hsgcm-title"
				name="title"
				class="regular-text"
				value="<?php echo esc_attr( $title ); ?>"
				required>

		</div>

		<div class="hsgcm-field">

			<label for="hsgcm-status">

				<?php esc_html_e( 'Status', 'hsg-campaign-manager' ); ?>

			</label>

			<select
				id="hsgcm-status"
				name="status">

				<option
					value="draft"
					<?php selected( $status, 'draft' ); ?>>

					<?php esc_html_e( 'Draft', 'hsg-campaign-manager' ); ?>

				</option>

				<option
					value="publish"
					<?php selected( $status, 'publish' ); ?>>

					<?php esc_html_e( 'Active', 'hsg-campaign-manager' ); ?>

				</option>

			</select>

		</div>

		<div class="hsgcm-field">

			<label for="hsgcm-coupon">

				<?php esc_html_e( 'Coupon', 'hsg-campaign-manager' ); ?>

			</label>

			<input
				type="text"
				id="hsgcm-coupon"
				name="coupon"
				value="<?php echo esc_attr( $coupon ); ?>">

		</div>

		<div class="hsgcm-field">

			<label for="hsgcm-price">

				<?php esc_html_e( 'Campaign Price', 'hsg-campaign-manager' ); ?>

			</label>

			<input
				type="number"
				step="0.01"
				id="hsgcm-price"
				name="price"
				value="<?php echo esc_attr( $price ); ?>">

		</div>

		<div class="hsgcm-field">

			<label for="hsgcm-start">

				<?php esc_html_e( 'Start Date', 'hsg-campaign-manager' ); ?>

			</label>

			<input
				type="date"
				id="hsgcm-start"
				name="start"
				value="<?php echo esc_attr( $start ); ?>">

		</div>

		<div class="hsgcm-field">

			<label for="hsgcm-end">

				<?php esc_html_e( 'End Date', 'hsg-campaign-manager' ); ?>

			</label>

			<input
				type="date"
				id="hsgcm-end"
				name="end"
				value="<?php echo esc_attr( $end ); ?>">

		</div>

		<hr>

		<h3>

			<?php esc_html_e( 'Products', 'hsg-campaign-manager' ); ?>

		</h3>

		<p class="description">

			<?php esc_html_e(
				'Product selection will be added in the next sprint.',
				'hsg-campaign-manager'
			); ?>

		</p>

		<hr>

		<h3>

			<?php esc_html_e( 'Pricing Rules', 'hsg-campaign-manager' ); ?>

		</h3>

		<p class="description">

			<?php esc_html_e(
				'Pricing engine will be added in the next sprint.',
				'hsg-campaign-manager'
			); ?>

		</p>

		<p class="submit">

			<button
				type="submit"
				class="button button-primary button-large"
				id="hsgcm-save">

				<?php esc_html_e(
					'Save Campaign',
					'hsg-campaign-manager'
				); ?>

			</button>

			<button
				type="button"
				class="button"
				id="hsgcm-reset">

				<?php esc_html_e(
					'New Campaign',
					'hsg-campaign-manager'
				); ?>

			</button>

		</p>

	</form>

</div>
