<?php

if (!class_exists('Association_Activity')) {

	class Association_Activity {
		const POST_TYPE = "association-activity";

		/**
		 * The Constructor
		 */
		public function __construct() {
			// register actions
			add_action('init', array(&$this, 'init'));
			add_action('admin_init', array(&$this, 'admin_init'));
		}// END public function __construct()

		/**
		 * hook into WP's init action hook
		 */
		public function init() {
			// Initialize Post Type
			$this -> create_post_type();
		}// END public function init()

		/**
		 * Create the post type
		 */
		public function create_post_type() {
			$labels = array(
				'name' => __('Groups', 'wp-simpl-adm'),
				'singular_name' => __('Group', 'wp-simpl-adm'),
				'search_items' => __('Search Groups', 'wp-simpl-adm'),
				'popular_items' => __('Popular Groups', 'wp-simpl-adm'),
				'all_items' => __('All Groups', 'wp-simpl-adm'),
				'parent_item' => null, 'parent_item_colon' => null, 'edit_item' => __('Edit Group', 'wp-simpl-adm'), 'update_item' => __('Update Group', 'wp-simpl-adm'), 'add_new_item' => __('Add New Group', 'wp-simpl-adm'), 'new_item_name' => __('New Group Name', 'wp-simpl-adm'), 'separate_items_with_commas' => __('Separate Groups with commas', 'wp-simpl-adm'), 'add_or_remove_items' => __('Add or remove Groups', 'wp-simpl-adm'), 'choose_from_most_used' => __('Choose from the most used Groups', 'wp-simpl-adm'), 'not_found' => __('No Groups found.', 'wp-simpl-adm'), 'menu_name' => __('Team Groups', 'wp-simpl-adm'), );

			$args = array(
				'hierarchical' => true,
				'labels' => $labels,
				'show_ui' => true,
				'show_admin_column' => true,
				'query_var' => true,
				'rewrite' => array('
					slug' => self::POST_TYPE
				),
				'supports' => array('title'),
			);

			register_taxonomy(self::POST_TYPE, Association_Member::POST_TYPE, $args);
		}// END public function create_post_type()

		/**
		 * hook into WP's admin_init action hook
		 */
		public function admin_init() {
		}// END public function admin_init()

	} // END class Association_Activity

} // END if(!class_exists('Association_Activity'))
