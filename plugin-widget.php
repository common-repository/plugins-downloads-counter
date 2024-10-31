<?php

class PluginsDownloadsCounterWidget extends WP_Widget {

	private $divid;

	/**
	 * Constructor
	 */
	public function __construct() {

		parent::__construct(
			'plugins-downloads-counter-id', __('Plugins Downloads Counter', 'plugins-downloads-counter-locale'), array(
		    'classname' => 'PluginsDownloadsCounterWidget',
		    'description' => __('This widget displays combined download counts for all your plugins on WordPress.org.', 'plugins-downloads-counter-locale')
			)
		);
	}

	/**
	 * Outputs the content of the widget.
	 *
	 * @args			The array of form elements
	 * @instance		The current instance of the widget
	 */
	public function widget($args, $instance) {

		extract($args, EXTR_SKIP);
		
		$username = ($instance['username'] != '') ? $instance['username'] : 'kouratoras';
		$prefix = ($instance['prefix'] != '') ? $instance['prefix'] : __('I have ','plugins-downloads-counter-locale');
		$suffix = ($instance['suffix'] != '') ? $instance['suffix'] : __(' total downloads in WordPress.org directory!','plugins-downloads-counter-locale');
		$thousands_sep = $instance['thousands_sep'];
		$plugins_info = ($instance['plugins_info'] != '') ? $instance['plugins_info'] : '0';

		echo $before_widget;
		if (strlen($instance['title']) > 0) {
			echo $before_title . $instance['title'] . $after_title;
		}

		$wptally = wp_remote_get( 'http://wptally.com/api/'.$username, $args );
		$data = json_decode(wp_remote_retrieve_body( $wptally ));
		
		if(!$data->error)
		{
			echo '<div class="pdc-desc">'.esc_html($prefix.number_format(intval($data->info->total_downloads), 0, ',', $thousands_sep).$suffix).'</div>';
			
			if($plugins_info == 1)
			{
				echo '<ul>';
				foreach($data->plugins as $current_plugin)
				{
					echo '<li>';
					echo '<span class="pdc-name"><a href="'.$current_plugin->url.'">'.$current_plugin->name.'</a></span>';
					echo '<ul>';
					
					foreach(get_object_vars($current_plugin) as $current_plugin_info_title=>$current_plugin_info_data)
					{
						if (in_array($current_plugin_info_title, array('name','url')))continue;
						echo '<li><span class="pdc-title">'.ucfirst($current_plugin_info_title).':</span> '.$current_plugin_info_data.'</li>';
					}
					echo '</ul>';
					echo '</li>';
				}
				echo '</ul>';
			}
		}
		else
		{
			echo __('No such username found.', 'plugins-downloads-counter-locale');
		}	
		
		echo $after_widget;
	}


	/**
	 * Processes the widget's options to be saved.
	 *
	 * @new_instance	The previous instance of values before the update.
	 * @old_instance	The new instance of values to be generated via the update.
	 */
	public function update($new_instance, $old_instance) {

		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);
		$instance['username'] = strip_tags($new_instance['username']);
		$instance['prefix'] = strip_tags($new_instance['prefix']);
		$instance['suffix'] = strip_tags($new_instance['suffix']);
		$instance['thousands_sep'] = strip_tags($new_instance['thousands_sep']);
		$instance['plugins_info'] = strip_tags($new_instance['plugins_info']);
		
		return $instance;
	}

	/**
	 * Generates the administration form for the widget.
	 *
	 * @instance	The array of keys and values for the widget.
	 */
	public function form($instance) {

		$instance = wp_parse_args(
			(array) $instance, array(
		    'title' => __('Plugins Downloads Counter', 'plugins-downloads-counter-locale'),
		    'username' => 'kouratoras',
			'prefix' => __('I have ','plugins-downloads-counter-locale'),
			'suffix' => __(' total downloads in WordPress.org directory!','plugins-downloads-counter-locale'),
			'thousands_sep' => '.',
		    'plugins_info' => '0',
			)
		);
		?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget title:', 'plugins-downloads-counter-locale') ?></label>
			<br/>
			<input type="text" class="widefat" value="<?php echo esc_attr($instance['title']); ?>" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('username'); ?>"><?php _e('WordPress.org Username:', 'plugins-downloads-counter-locale') ?></label>
			<br/>
			<input type="text" class="widefat" value="<?php echo esc_attr($instance['username']); ?>" id="<?php echo $this->get_field_id('username'); ?>" name="<?php echo $this->get_field_name('username'); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('prefix'); ?>"><?php _e('Prefix:', 'plugins-downloads-counter-locale') ?></label>
			<br/>
			<input type="text" class="widefat" value="<?php echo esc_attr($instance['prefix']); ?>" id="<?php echo $this->get_field_id('prefix'); ?>" name="<?php echo $this->get_field_name('prefix'); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('suffix'); ?>"><?php _e('Suffix', 'plugins-downloads-counter-locale') ?></label>
			<br/>
			<input type="text" class="widefat" value="<?php echo esc_attr($instance['suffix']); ?>" id="<?php echo $this->get_field_id('suffix'); ?>" name="<?php echo $this->get_field_name('suffix'); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('thousands_sep'); ?>"><?php _e('Thousands seperator:', 'plugins-downloads-counter-locale') ?></label>
			<select class="feature" id="<?php echo $this->get_field_id('thousands_sep'); ?>" name="<?php echo $this->get_field_name('thousands_sep'); ?>">
				<option <?php if (esc_attr($instance['thousands_sep']) == '') echo 'selected="selected"'; ?> value="">None</option>
				<option <?php if (esc_attr($instance['thousands_sep']) == ',') echo 'selected="selected"'; ?> value=",">,</option>
				<option <?php if (esc_attr($instance['thousands_sep']) == '.') echo 'selected="selected"'; ?> value=".">.</option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('plugins_info'); ?>"><?php _e('Display plugins information:', 'plugins-downloads-counter-locale') ?></label>
			<select class="feature" id="<?php echo $this->get_field_id('plugins_info'); ?>" name="<?php echo $this->get_field_name('plugins_info'); ?>">
				<option <?php if (esc_attr($instance['plugins_info']) == '1') echo 'selected="selected"'; ?> value="1">Yes</option>
				<option <?php if (esc_attr($instance['plugins_info']) == '0') echo 'selected="selected"'; ?> value="0">No</option>
			</select>
		</p>

		<?php
	}

}
?>