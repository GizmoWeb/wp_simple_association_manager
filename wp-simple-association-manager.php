<?php
/**
 * Plugin Name: WP Simple association manager
 * Plugin URI: https://github.com/GizmoWeb/wp_simple_association_manager
 * Description: Manager for small association people data, role and status.
 * Version: 0.1
 * Author: Daniele Tardia
 * Author URI: http://www.oivavoi.it
 * License: GPL2
 */
/*
 Copyright 2014  Daniele Tardia  (email : daniele.tardia@oivavoi.it)

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License, version 2, as
 published by the Free Software Foundation.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
if (!class_exists('WP_Simple_association_manager')) {
	class WP_Simple_association_manager {
		/**
		 * Construct the plugin object
		 */
		public function __construct() {
			add_action('init', array($this, 'load_plugin_textdomain'));

			// Register custom post types
			require_once (sprintf("%s/post-types/association_member.php", dirname(__FILE__)));
			$Association_Member = new Association_Member();

			require_once (sprintf("%s/post-types/association_activity.php", dirname(__FILE__)));
			$Association_Activity = new Association_Activity();

			// Register shortcode
			require_once (sprintf("%s/shortcode.php", dirname(__FILE__)));
			$Shortcode = new Shortcode();

			$plugin = plugin_basename(__FILE__);
		}// END public function __construct

		/**
		 * Load plugin translations
		 */
		public function load_plugin_textdomain() {
			$loadLang = load_plugin_textdomain('wp-simpl-adm', false, dirname(plugin_basename(__FILE__)) . '/languages/');
		}// END public function load_plugin_textdomain

		/**
		 * Activate the plugin
		 */
		public static function activate() {
			// Do nothing
		}// END public static function activate

		/**
		 * Deactivate the plugin
		 */
		public static function deactivate() {
			// Do nothing
		} // END public static function deactivate

	} // END class WP_Simple_association_manager

}// END if(!class_exists('WP_Simple_association_manager'))

if (class_exists('WP_Simple_association_manager')) {
	// Installation and uninstallation hooks
	register_activation_hook(__FILE__, array('WP_Simple_association_manager', 'activate'));
	register_deactivation_hook(__FILE__, array('WP_Simple_association_manager', 'deactivate'));

	// instantiate the plugin class
	$wp_simple_association_manager = new WP_Simple_association_manager();

}
