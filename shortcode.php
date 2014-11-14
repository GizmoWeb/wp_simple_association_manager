<?php

if (!class_exists('Shortcode')) {
	/**
	 * A PostTypeTemplate class that provides 3 additional meta fields
	 */
	class Shortcode {
		const SHORT_NAME = "wp-simpl-adm";

		/**
		 * The Constructor
		 */
		public function __construct() {
			add_action('init', array($this, 'register_shortcodes'));
		}// END public function __construct()

		/**
		 *
		 */
		public function register_shortcodes() {
			add_shortcode(Shortcode::SHORT_NAME, array($this, 'custom_shortcode'));
		}// END public function register_shortcodes()

		/**
		 *
		 */
		public function custom_shortcode($atts = array()) {
			wp_enqueue_style( 'wp_simple_association_manager_style-front', plugins_url( 'css/style-front.css' , __FILE__ ), true);
			//
			$attributes = shortcode_atts(
				array(
					'show_members'			=> 1,
					'show_groups'			=> 1,
					'show_subgroups'		=> 1,
					'show_groups_name' 		=> 1,
					'show_subgroups_name'	=> 1,
					'group'					=> null,
					'members_filters'		=> null,
					'members_fields'		=> null,
				)
			,$atts);

			if(isset($attributes['members_filters'])){
				$attributes['members_filters'] = explode("|", $attributes['members_filters']);
			}
			if(isset($attributes['members_fields'])){
				$attributes['members_fields'] = explode("|", $attributes['members_fields']);
			}
			$opts = array('taxonomy' => Association_Activity::POST_TYPE, "parent" => 0, 'hide_empty' => 0);
			$activities = get_categories($opts);

			foreach ($activities as $key=>$value) {
				$tmpvalue = (array) $value;
				$opts["parent"] = $value -> cat_ID;
				$subactivities = get_categories($opts);
				if(!isset($tmpvalue["sub_category"])) $tmpvalue["sub_category"] = array();
				foreach ($subactivities as $subactivity) {
					$tmpvalue["sub_category"][$subactivity -> slug] = (array) $subactivity;
				}
				$activities[$key] = $tmpvalue;
			}

			$member_args = array(
				'post_type'		=> Association_Member::POST_TYPE,
				'post_status'	=> 'publish',
				'orderby'		=> 'title',
				'order'			=> 'ASC',
				'tax_query' 	=> array(
					'relation'	=> 'AND',
					array(
						'taxonomy' => Association_Activity::POST_TYPE,
						//'terms' => array(),
					),
				)
			);
			$str = "";
			$newline = "\n";

			if($attributes["show_groups"]){
				foreach ($activities as $activity) {
					if($attributes["group"] == null || ( $attributes["group"] != null && ( $activity["slug"] == $attributes["group"] || isset($activity["sub_category"][$attributes["group"]]) ) ) ){
						$str .= "<div>";
						if($attributes["show_groups_name"] ){
							$str .= "<h2 class='wp_simple_association_manager_".Association_Activity::POST_TYPE."_group_name'>" . $activity["name"] . "</h2>";
						}
						$subactivities = $activity["sub_category"];
						if (count($subactivities) == 0 || !$attributes["show_subgroups"]) {
							if($attributes["show_members"]){
								$member_args["tax_query"][0]["terms"] = $activity["term_id"];
								$str .= $this -> get_queried($member_args,$attributes["members_filters"],$attributes["members_fields"]);
							}
						} else {
							foreach ($subactivities as $subactivity) {
								if($attributes["group"] == null || ( $attributes["group"] != null && $attributes["group"] == $subactivity["slug"] ) ){
									if($attributes["show_subgroups_name"]){
										$str .= "<h3 class='wp_simple_association_manager_".Association_Activity::POST_TYPE."_subgroup_name'>" . $subactivity["name"] . "</h3>";
									}
									if($attributes["show_members"]){
										$member_args["tax_query"][0]["terms"] = $subactivity["term_id"];
										$str .= $this -> get_queried($member_args,$attributes["members_filters"],$attributes["members_fields"]);
									}
								}
							}
						}
						$str .= "</div>";
					}
				}
			}else{
				$member_args["tax_query"] =  null;
				$str .= "<div>";
				$str .= $this -> get_queried($member_args,$attributes["members_filters"],$attributes["members_fields"]);
				$str .= "</div>";
			}
			return $str;
		}// END public function custom_shortcode()

		/**
		 *
		 */
		public function get_queried($args,$filters,$fields) {
			global $post;
			$newline = "\n";
			$str = "<ul class='wp_simple_association_manager_list_".Association_Member::POST_TYPE."'>" . $newline;
			$m_query = new WP_Query($args);
			if ($m_query -> have_posts()) {
				while ($m_query -> have_posts()) {
					$m_query -> the_post();
					$p_meta = get_post_meta($post->ID, 'wp_simple_association_manager', true);
					$show = true;
					if($filters != null){
						foreach ($p_meta as $k=>$m) {
							$field = preg_replace('/wp_simple_association_manager_/i','',$k);
							if( in_array($field, $filters) ){
								if($m == 0){
									$show = false;
									break;
								}
							}
						}
					}
					if($show){
						$str .= "<li>";
						$str .= "<p>";
						if($fields == null){
							foreach ($p_meta as $k=>$m) {
								if((!is_numeric($m) || (is_numeric($m) && $m > 1)) && $m != ""){
									$str .= "<span class='wp_simple_association_manager_".Association_Member::POST_TYPE." ".$k."'>";
									$str .= $m;
									$str .= "</span>" . $newline;
								}
							}
						}else{
							foreach ($fields as $i=>$f) {
								$str .= "<span class='wp_simple_association_manager_".Association_Member::POST_TYPE." wp_simple_association_manager_".$f."'>";
								$str .= $p_meta["wp_simple_association_manager_" . $f];
								$str .= "</span>" . $newline;
							}
						}
						$str .= "</p>" . $newline;
						$str .= "</li>" . $newline;
					}
				}
			}
			wp_reset_postdata();
			$str .= "</ul>" . $newline;
			return $str;
		}// END public function get_queried()

	} // END class Shortcode

} // END if(!class_exists('Shortcode'))
