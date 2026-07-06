<?php
/**
 * Campaign Repository
 *
 * @package HSGCampaignManager
 */

namespace HSGCM\Campaign;

defined( 'ABSPATH' ) || exit;

final class CampaignRepository {

	/**
	 * Campaign post type.
	 *
	 * @var string
	 */
	private const POST_TYPE = 'hsg_campaign';

	/**
	 * Return all campaigns.
	 *
	 * @return array
	 */
	public function all(): array {

		return get_posts(
			array(
				'post_type'      => self::POST_TYPE,
				'post_status'    => array( 'publish', 'draft' ),
				'posts_per_page' => -1,
				'orderby'        => 'title',
				'order'          => 'ASC',
			)
		);

	}

	/**
	 * Find campaign.
	 *
	 * @param int $id Campaign ID.
	 *
	 * @return \WP_Post|null
	 */
	public function find( int $id ): ?\WP_Post {

		$post = get_post( $id );

		if ( ! $post ) {
			return null;
		}

		if ( self::POST_TYPE !== $post->post_type ) {
			return null;
		}

		return $post;

	}

	/**
	 * Count campaigns.
	 *
	 * @return int
	 */
	public function count(): int {

		$count = wp_count_posts( self::POST_TYPE );

		return (int) ( $count->publish ?? 0 );

	}

	/**
	 * Create campaign.
	 *
	 * @param array $data Campaign data.
	 *
	 * @return int|\WP_Error
	 */
	public function create( array $data ) {

		$post_id = wp_insert_post(
			array(
				'post_type'   => self::POST_TYPE,
				'post_title'  => $data['title'],
				'post_status' => $data['status'],
			),
			true
		);

		if ( is_wp_error( $post_id ) ) {
			return $post_id;
		}

		$this->save_meta( $post_id, $data );

		return (int) $post_id;

	}

	/**
	 * Update campaign.
	 *
	 * @param int   $id Campaign ID.
	 * @param array $data Campaign data.
	 *
	 * @return bool
	 */
	public function update( int $id, array $data ): bool {

		$result = wp_update_post(
			array(
				'ID'          => $id,
				'post_title'  => $data['title'],
				'post_status' => $data['status'],
			),
			true
		);

		if ( is_wp_error( $result ) ) {
			return false;
		}

		$this->save_meta( $id, $data );

		return true;

	}

	/**
	 * Delete campaign.
	 *
	 * @param int $id Campaign ID.
	 *
	 * @return bool
	 */
	public function delete( int $id ): bool {

		return false !== wp_delete_post( $id, true );

	}

	/**
	 * Duplicate campaign.
	 *
	 * @param int $id Campaign ID.
	 *
	 * @return int|false
	 */
	public function duplicate( int $id ) {

		$post = $this->find( $id );

		if ( ! $post ) {
			return false;
		}

		$data = array(
			'title'  => $post->post_title . ' (Copy)',
			'status' => 'draft',
			'coupon' => get_post_meta( $id, '_hsgcm_coupon', true ),
			'price'  => get_post_meta( $id, '_hsgcm_price', true ),
			'start'  => get_post_meta( $id, '_hsgcm_start_date', true ),
			'end'    => get_post_meta( $id, '_hsgcm_end_date', true ),
		);

		$new_id = $this->create( $data );

		if ( is_wp_error( $new_id ) ) {
			return false;
		}

		return (int) $new_id;

	}

	/**
	 * Save campaign meta.
	 *
	 * @param int   $post_id Campaign ID.
	 * @param array $data Campaign data.
	 *
	 * @return void
	 */
	private function save_meta( int $post_id, array $data ): void {

		$meta = array(
			'_hsgcm_coupon'    => sanitize_text_field( $data['coupon'] ?? '' ),
			'_hsgcm_price'     => wc_format_decimal( $data['price'] ?? '' ),
			'_hsgcm_start_date'=> sanitize_text_field( $data['start'] ?? '' ),
			'_hsgcm_end_date'  => sanitize_text_field( $data['end'] ?? '' ),
		);

		foreach ( $meta as $key => $value ) {

			update_post_meta(
				$post_id,
				$key,
				$value
			);

		}

	}

}
