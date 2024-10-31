<?php

/*
  Plugin Name: Plugins Downloads Counter
  Plugin URI: http://wordpress.org/extend/plugins/plugins-downloads-counter
  Description: Get combined download counts for all your plugins on WordPress.org, using http://wptally.com/ API.
  Version: 0.2
  Author: Konstantinos Kouratoras
  Author URI: http://www.kouratoras.gr
  Author Email: kouratoras@gmail.com
  License: GPL v2

  Copyright 2012 Konstantinos Kouratoras (kouratoras@gmail.com)

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

define('PDC_PLUGIN_DIR_NAME', 'plugins-downloads-counter');

class PluginsDownloadsCounter {

	/* -------------------------------------------------- */
	/* Constructor
	  /*-------------------------------------------------- */

	public function __construct() {

		load_plugin_textdomain('plugins-downloads-counter-locale', false, plugin_dir_path(__FILE__) . '/languages/');
		
		//Register styles
		add_action('wp_enqueue_scripts', array(&$this, 'register_plugin_styles'));
		
		//Shortcode
		require_once( plugin_dir_path(__FILE__) . '/plugin-shortcode.php' );
		new PluginsDownloadsCounterShortcode();
		
		//Widget
		require_once( plugin_dir_path(__FILE__) . '/plugin-widget.php' );
		add_action('widgets_init', create_function('', 'register_widget("PluginsDownloadsCounterWidget");'));
	}
	
	/* -------------------------------------------------- */
	/* Registers and enqueues styles.
	  /* -------------------------------------------------- */

	public function register_plugin_styles() {

		wp_register_style('plugins-downloads-counter-style', plugins_url(PDC_PLUGIN_DIR_NAME . '/css/style.css'));
		wp_enqueue_style('plugins-downloads-counter-style');
	}
	
}

new PluginsDownloadsCounter();