<?php

if (!class_exists('Association_Member')) {
	/**
	 * A PostTypeTemplate class that provides 3 additional meta fields
	 */
	class Association_Member {
		const POST_TYPE = "association-member";
		private $_meta = array(
			'name' => 'string',
			'surname' => 'string',
			'email' => 'string',
			'phone' => 'string',
			'mobile' => 'string',
			'fax' => 'string',
			'in_activity' => 'bool',
			'in_study' => 'bool',
		);

		/**
		 * The Constructor
		 */
		public function __construct() {
			// register actions
			add_action('init', array(&$this, 'init'));
			add_action('admin_init', array(&$this, 'admin_init'));
			add_action('admin_print_styles-post.php', array(&$this, 'add_admin_styles'));
			add_action('admin_print_styles-post-new.php', array(&$this, 'add_admin_styles'));
			add_action('admin_print_scripts-post.php', array(&$this, 'add_admin_scripts'));
			add_action('admin_print_scripts-post-new.php', array(&$this, 'add_admin_scripts'));
			//add_action('admin_footer-edit.php',  array(&$this, "add_javascript_handler")); // Fired on the page with the posts table
			add_action('admin_footer-post.php', array(&$this, "add_javascript_handler"));
			// Fired on post edit page
			add_action('admin_footer-post-new.php', array(&$this, "add_javascript_handler"));
			// Fired on add new post page
		}// END public function __construct()

		/**
		 * hook into WP's init action hook
		 */
		public function init() {
			// Initialize Post Type
			$this -> create_post_type();
			add_action('save_post', array(&$this, 'save_post'));
		}// END public function init()

		/**
		 * Create the post type
		 */
		public function create_post_type() {
			$labels = array('name' => __('Association', 'wp-simpl-adm'), 'singular_name' => __('Association Member', 'wp-simpl-adm'), 'add_new' => __('Add New Member', 'wp-simpl-adm'), 'add_new_item' => __('Add New ', 'wp-simpl-adm'), 'edit_item' => __('Edit Association Member ', 'wp-simpl-adm'), 'new_item' => __('New Association Member', 'wp-simpl-adm'), 'view_item' => __('View Association Members', 'wp-simpl-adm'), 'search_items' => __('Search Association Members', 'wp-simpl-adm'), 'not_found' => __('Not found any Association Member', 'wp-simpl-adm'), 'not_found_in_trash' => __('No Association Member found in Trash', 'wp-simpl-adm'), 'parent_item_colon' => __('Parent Association Member:', 'wp-simpl-adm'), 'menu_name' => __('Association manager', 'wp-simpl-adm'), );
			register_post_type(self::POST_TYPE, array('labels' => $labels, 'description' => __('This post type contains info for Association Member', 'wp-simpl-adm'), 'hierarchical' => false, 'supports' => false, //array( 'title'),
			'public' => false, 'show_ui' => true, 'show_in_menu' => true, 'show_in_nav_menus' => true, 'publicly_queryable' => true, 'exclude_from_search' => true, 'has_archive' => true, 'query_var' => true, 'can_export' => true, 'rewrite' => true, 'capability_type' => 'post', 'menu_icon' => 'dashicons-groups', 'rewrite' => array('slug' => self::POST_TYPE), ));
		}// END public function create_post_type()

		/**
		 * Save the metaboxes for this custom post type
		 */
		public function save_post($post_id) {
			// verify this came from our screen and with proper authorization.
			if (!isset($_POST['wp_simple_association_manager_groups_box_noncename']) || !wp_verify_nonce( $_POST['wp_simple_association_manager_groups_box_noncename'], 'wp_simple_association_manager_groups_box_'.$post_id )) {
				return $post_id;
			}
			// verify if this is an auto save routine.
			// If it is our form has not been submitted, so we dont want to do anything
			if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
				return;
			}

			if (isset($_POST['post_type']) && $_POST['post_type'] == self::POST_TYPE && current_user_can('edit_post', $post_id)) {
				$data = array();
				foreach ($this->_meta as $key => $field_name) {
					$data["wp_simple_association_manager_" . $key] = $field_name == 'bool' ? (isset($_POST["wp_simple_association_manager_" . $key]) ? $_POST["wp_simple_association_manager_" . $key] : 0) : $_POST["wp_simple_association_manager_" . $key];
				}
				update_post_meta($post_id, "wp_simple_association_manager", $data);
			} else {
				return;
			}
		}// END public function save_post($post_id)

		/**
		 * hook into WP's admin_init action hook
		 */
		public function admin_init($hook) {
			// Add metaboxes
			add_action('add_meta_boxes', array(&$this, 'add_meta_boxes'));
		}// END public function admin_init()

		/**
		 * hook into WP's
		 */
		public function add_admin_styles($hook) {
			global $post;
			if (is_admin() && isset($post) && $post -> post_type == self::POST_TYPE) {
				echo "<!-- add_admin_styles " . $post -> post_type . " -->";
				echo "<!-- add_admin_styles " . $hook . " -->";
				wp_enqueue_style('wp_simple_association_manager_admin-style', plugins_url('../css/style-admin.css', plugin_basename(__FILE__)), true);
			}
		}// END public function add_admin_styles()
		/**
		 * hook into WP's
		 */
		public function add_admin_scripts($hook) {
			global $post;
			if (is_admin() && isset($post) && $post -> post_type == self::POST_TYPE) {
				echo "<!-- add_admin_scripts " . $post -> post_type . " -->";
				echo "<!-- add_admin_scripts " . $hook . " -->";
				wp_enqueue_script( 'wp_simple_association_manager_admin-script', plugins_url( '../js/admin.js', __FILE__ ), array( 'jquery' ) );
			}
		}// END public function add_admin_scripts()

		/**
		 * hook into WP's add_meta_boxes action hook
		 */
		public function add_meta_boxes() {
			// Add this metabox to every selected post
			add_meta_box(sprintf('wp_simple_association_manager_%s_section', self::POST_TYPE), __('Association Member Informations', 'wp-simpl-adm'), array(&$this, 'add_inner_meta_boxes'), self::POST_TYPE);

			//REMOVE DEFAULT AND ADD THE NEW ONE
			remove_meta_box( Association_Activity::POST_TYPE . 'div', self::POST_TYPE, 'side' );
			add_meta_box('wp_simple_association_manager_groups_box_ID', __('Groups', 'wp-simpl-adm'), array(&$this, 'add_side_meta_boxes'),  self::POST_TYPE, 'side', 'core');
		}// END public function add_meta_boxes()

		/**
		 * called off of the add meta box
		 */
		public function add_inner_meta_boxes($post) {
			// Render the job order metabox
			include (sprintf("%s/../templates/%s_metabox.php", dirname(__FILE__), self::POST_TYPE));
		}// END public function add_inner_meta_boxes($post)
		/**
		 *
		 */
		public function add_side_meta_boxes($post) {
			include (sprintf("%s/../templates/%s_side_metabox.php", dirname(__FILE__), Association_Activity::POST_TYPE));
		}// END public function add_side_meta_boxes($post)

		public function add_javascript_handler($hook) {
			global $post;
			if (!isset($post) || $post -> post_type != self::POST_TYPE)
				return;
			echo "<!-- POSTTYPE:::" . $post -> post_type . " -->";
			$str = "<script type=\"text/javascript\">
	(function($){
		$(function(){
			$('#post').submit(function(){
				var title = $('#wp_simple_association_manager_surname').val()+' '+$('#wp_simple_association_manager_name').val()
				$('#title').val(title);
			});
		});
	})(jQuery);
</script>";
			echo $str;
		}// END public function add_javascript_handler($hook)

	} // END class Association_Member

} // END if(!class_exists('Association_Member'))
