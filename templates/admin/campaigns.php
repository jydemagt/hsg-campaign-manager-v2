<?php
/**
 * Campaigns View
 *
 * @package HSGCampaignManager
 */

defined( 'ABSPATH' ) || exit;

$campaigns = $data['campaigns'] ?? array();
$total     = $data['total'] ?? 0;

$form = new \HSGCM\Admin\CampaignForm();
?>

<div class="hsgcm-header">

	<div>

		<h2><?php esc_html_e( 'Campaigns', 'hsg-campaign-manager' ); ?></h2>

		<p>

			<?php
			printf(
				esc_html__( '%d campaign(s)', 'hsg-campaign-manager' ),
				(int) $total
			);
			?>

		</p>

	</div>

	<div>

		<button
			type="button"
			class="button button-primary hsgcm-new-campaign">

			<?php esc_html_e( 'New Campaign', 'hsg-campaign-manager' ); ?>

		</button>

	</div>

</div>

<div class="hsgcm-layout">

	<div class="hsgcm-left">

		<?php if ( empty( $campaigns ) ) : ?>

			<div class="hsgcm-empty">

				<h2>

					<?php esc_html_e(
						'No campaigns found',
						'hsg-campaign-manager'
					); ?>

				</h2>

				<p>

					<?php esc_html_e(
						'Create your first campaign using the form.',
						'hsg-campaign-manager'
					); ?>

				</p>

			</div>

		<?php else : ?>

			<div class="hsgcm-campaign-grid">

				<?php foreach ( $campaigns as $campaign ) : ?>

					<?php

					$id = $campaign->ID;

					$coupon = get_post_meta(
						$id,
						'_hsgcm_coupon',
						true
					);

					$price = get_post_meta(
						$id,
						'_hsgcm_price',
						true
					);

					$start = get_post_meta(
						$id,
						'_hsgcm_start_date',
						true
					);

					$end = get_post_meta(
						$id,
						'_hsgcm_end_date',
						true
					);

					?>

					<div
						class="hsgcm-campaign-card"
						data-id="<?php echo esc_attr( $id ); ?>">

						<div class="hsgcm-card-header">

							<div>

								<h3>

									<?php
									echo esc_html(
										$campaign->post_title
									);
									?>

								</h3>

								<p>

									<?php
									echo 'publish' === $campaign->post_status
										? '🟢 Active'
										: '🟡 Draft';
									?>

								</p>

							</div>

						</div>

						<div class="hsgcm-card-body">

							<p>

								<strong>

									<?php esc_html_e(
										'Coupon',
										'hsg-campaign-manager'
									); ?>

								</strong>

								<br>

								<?php echo esc_html( $coupon ?: '—' ); ?>

							</p>

							<p>

								<strong>

									<?php esc_html_e(
										'Price',
										'hsg-campaign-manager'
									); ?>

								</strong>

								<br>

								<?php

								if ( '' !== $price ) {

									echo wp_kses_post(
										wc_price(
											(float) $price
										)
									);

								} else {

									echo '&mdash;';

								}

								?>

							</p>

							<p>

								<strong>

									<?php esc_html_e(
										'Period',
										'hsg-campaign-manager'
									); ?>

								</strong>

								<br>

								<?php
								echo esc_html(
									$start ?: '-'
								);
								?>

								→

								<?php
								echo esc_html(
									$end ?: '-'
								);
								?>

							</p>

						</div>

						<div class="hsgcm-card-footer">

							<button
								class="button button-secondary hsgcm-edit-campaign"
								data-id="<?php echo esc_attr( $id ); ?>">

								<?php esc_html_e(
									'Edit',
									'hsg-campaign-manager'
								); ?>

							</button>

							<button
								class="button hsgcm-duplicate-campaign"
								data-id="<?php echo esc_attr( $id ); ?>">

								<?php esc_html_e(
									'Duplicate',
									'hsg-campaign-manager'
								); ?>

							</button>

							<button
								class="button button-link-delete hsgcm-delete-campaign"
								data-id="<?php echo esc_attr( $id ); ?>">

								<?php esc_html_e(
									'Delete',
									'hsg-campaign-manager'
								); ?>

							</button>

						</div>

					</div>

				<?php endforeach; ?>

			</div>

		<?php endif; ?>

	</div>

	<div class="hsgcm-right">

		<?php $form->render(); ?>

	</div>

</div>
