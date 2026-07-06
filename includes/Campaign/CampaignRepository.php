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
	 * Post type.
	 */
	private const POST_TYPE = 'hsg_campaign';

	/**
	 * Meta key.
	 */
	private const META_KEY = '_hsgcm_campaign';

	/**
	 * Get all campaigns.
	 *
	 * @return array
	 */
	public function all(): array {

		return get_posts(
			array(
				'post_type'      => self::POST_TYPE,
				'post_status'    => array( 'publish', 'draft' ),
				'posts_per_page' => -1,
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
			)
		);

	}

	/**
	 * Find campaign.
	 *
	 * @param int $id Campaign ID.
	 *
	 * @return array|null
	 */
	public function find( int $id ): ?array {

		$post = get_post( $id );

		if ( ! $post || self::POST_TYPE !== $post->post_type ) {
			return null;
		}

		$data = get_post_meta(
			$id,
			self::META_KEY,
			true
		);

		if ( ! is_array( $data ) ) {
			$data = array();
		}

		$data = CampaignSchema::normalize( $data );

		$data['id'] = $id;

		return $data;

	}

	/**
	 * Create campaign.
	 *
	 * @param array $campaign Campaign.
	 *
	 * @return int|\WP_Error
	 */
	public function create( array $campaign ) {

		$campaign = CampaignSchema::normalize( $campaign );

		$post_id = wp_insert_post(
			array(
				'post_type'   => self::POST_TYPE,
				'post_title'  => $campaign['general']['title'],
				'post_status' => $campaign['general']['status'],
			),
			true
		);

		if ( is_wp_error( $post_id ) ) {
			return $post_id;
		}

		update_post_meta(
			$post_id,
			self::META_KEY,
			$campaign
		);

		return (int) $post_id;

	}

	/**
	 * Update campaign.
	 *
	 * @param int   $id Campaign ID.
	 * @param array $campaign Campaign.
	 *
	 * @return bool
	 */
	public function update(
		int $id,
		array $campaign
	): bool {

		$campaign = CampaignSchema::normalize(
			$campaign
		);

		$result = wp_update_post(
			array(
				'ID'          => $id,
				'post_title'  => $campaign['general']['title'],
				'post_status' => $campaign['general']['status'],
			),
			true
		);

		if ( is_wp_error( $result ) ) {
			return false;
		}

		update_post_meta(
			$id,
			self::META_KEY,
			$campaign
		);

		return true;

	}

	/**
	 * Delete campaign.
	 *
	 * @param int $id Campaign ID.
	 *
	 * @return bool
	 */
	public function delete(
		int $id
	): bool {

		return false !== wp_delete_post(
			$id,
			true
		);

	}

	/**
	 * Duplicate campaign.
	 *
	 * @param int $id Campaign ID.
	 *
	 * @return int|false
	 */
	public function duplicate(
		int $id
	) {

		$campaign = $this->find(
			$id
		);

		if ( ! $campaign ) {
			return false;
		}

		$campaign['general']['title'] .= ' (Copy)';
		$campaign['general']['status'] = 'draft';

		return $this->create(
			$campaign
		);

	}

	/**
	 * Count campaigns.
	 *
	 * @return int
	 */
	public function count(): int {

		$count = wp_count_posts(
			self::POST_TYPE
		);

		return (int) (
			$count->publish ?? 0
		);

	}

}
