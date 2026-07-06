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
	 */
	private const POST_TYPE = 'hsg_campaign';

	/**
	 * Get all campaigns.
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
	 */
	public function find( int $id ): ?\WP_Post {

		$post = get_post( $id );

		if ( ! $post || self::POST_TYPE !== $post->post_type ) {
			return null;
		}

		return $post;

	}

	/**
	 * Count campaigns.
	 */
	public function count(): int {

		$count = wp_count_posts( self::POST_TYPE );

		return (int) ( $count->publish ?? 0 );

	}

	/**
	 * Create campaign.
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
	 */
	public function delete( int $id ): bool {

		return false !== wp_delete_post( $id, true );

	}

	/**
	 * Duplicate campaign.
	 */
	public function duplicate( int $id ) {

		$post = $this->find( $id );

		if ( ! $post ) {
			return false;
		}

		$data = $this->get_campaign_data( $id );

		$data['title']  = $post->post_title . ' (Copy)';
		$data['status'] = 'draft';

		return $this->create( $data );

	}

	/**
	 * Return campaign as array.
	 */
	public function get_campaign_data( int $id ): array {

		$post = $this->find( $id );

		if ( ! $post ) {
			return array();
		}

		return array(
			'id'       => $post->ID,
			'title'    => $post->post_title,
			'status'   => $post->post_status,
			'coupon'   => get_post_meta( $id, '_hsgcm_coupon', true ),
			'price'    => get_post_meta( $id, '_hsgcm_price', true ),
			'start'    => get_post_meta( $id, '_hsgcm_start_date', true ),
			'end'      => get_post_meta( $id, '_hsgcm_end_date', true ),
			'products' => (array) get_post_meta(
				$id,
				'_hsgcm_products',
				true
			),
		);

	}

	/**
	 * Save campaign meta.
	 */
	private function save_meta( int $post_id, array $data ): void {

		update_post_meta(
			$post_id,
			'_hsgcm_coupon',
			sanitize_text_field( $data['coupon'] ?? '' )
		);

		update_post_meta(
			$post_id,
			'_hsgcm_price',
			wc_format_decimal( $data['price'] ?? '' )
		);

		update_post_meta(
			$post_id,
			'_hsgcm_start_date',
			sanitize_text_field( $data['start'] ?? '' )
		);

		update_post_meta(
			$post_id,
			'_hsgcm_end_date',
			sanitize_text_field( $data['end'] ?? '' )
		);

		/*
		 * Products
		 */

		$products = array_map(
			'absint',
			(array) ( $data['products'] ?? array() )
		);

		update_post_meta(
			$post_id,
			'_hsgcm_products',
			$products
		);

	}

}
