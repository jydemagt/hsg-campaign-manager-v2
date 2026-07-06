<?php
/**
 * Campaign Post Type
 *
 * @package HSGCampaignManager
 */

namespace HSGCM\Campaign;

defined( 'ABSPATH' ) || exit;

final class Campaign {

	/**
	 * Constructor.
	 */
	public function __construct() {

		add_action(
			'init',
			array( $this, 'register_post_type' )
		);

	}

	/**
	 * Register campaign post type.
	 *
	 * @return void
	 */
	public function register_post_type(): void {

		$labels = array(
			'name'               => __( 'Campaigns', 'hsg-campaign-manager' ),
			'singular_name'      => __( 'Campaign', 'hsg-campaign-manager' ),
			'menu_name'          => __( 'Campaigns', 'hsg-campaign-manager' ),
			'name_admin_bar'     => __( 'Campaign', 'hsg-campaign-manager' ),
			'add_new'            => __( 'Add New', 'hsg-campaign-manager' ),
			'add_new_item'       => __( 'Add New Campaign', 'hsg-campaign-manager' ),
			'edit_item'          => __( 'Edit Campaign', 'hsg-campaign-manager' ),
			'new_item'           => __( 'New Campaign', 'hsg-campaign-manager' ),
			'view_item'          => __( 'View Campaign', 'hsg-campaign-manager' ),
			'search_items'       => __( 'Search Campaigns', 'hsg-campaign-manager' ),
			'not_found'          => __( 'No campaigns found.', 'hsg-campaign-manager' ),
			'not_found_in_trash' => __( 'No campaigns found in trash.', 'hsg-campaign-manager' ),
		);

		$args = array(
			'labels'              => $labels,
			'public'              => false,
			'show_ui'             => false,
			'show_in_menu'        => false,
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'show_in_rest'        => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'has_archive'         => false,
			'hierarchical'        => false,
			'menu_position'       => null,
			'supports'            => array(
				'title',
			),
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
		);

		register_post_type(
			'hsg_campaign',
			$args
		);

	}

}
