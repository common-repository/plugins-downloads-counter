<?php

class PluginsDownloadsCounterShortcode {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action('init', array(&$this, 'register_shortcode'));
	}

	/**
	 * Registers the shortcode
	 */
	function register_shortcode() {
		add_shortcode('plugins_downloads_counter', array(&$this, 'shortcode'));
	}

	/**
	 * Creates the shortcode
	 */
	function shortcode($atts) {

		$username = (isset($atts['username'])) ? sanitize_text_field($atts['username']) : 'kouratoras';
		
		$args = array(
			'timeout' => 5
		);
		
		$wptally = wp_remote_get( 'http://wptally.com/api/'.$username, $args );
		$data = json_decode(wp_remote_retrieve_body( $wptally ));
		
		$return_str = '';
		
		if(!$data->error)
			$return_str = esc_html($data->info->total_downloads);
		else
			$return_str = __('No such username found.', 'plugins-downloads-counter-locale');
			
		return $return_str;
	}

}