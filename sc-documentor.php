<?php

/*
Plugin Name: SiteCreate Documentor - A Powerful Widget for Table of Content and Document Downloads
Plugin URI: https://www.sitecreate.io/wordpress-plugins/sitecreate-documentor-powerful-widget-table-content-document-downloads/
Description: SiteCreate Documentor
Version: 1.0
Author: SiteCreate
Author URI: https://www.sitecreate.io
Text Domain: sc-documentor
Domain Path: /languages
Requires at least: wordpress 4.5.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/	

function sitecreate_documentor_widget_load_scripts() {
	if(is_active_widget(false,false,'sc_documentor_widget') && is_single() || is_active_widget(false,false,'sc_documentor_widget') && is_page()) {
		wp_enqueue_script('sc-documentor-js', plugin_dir_url( __FILE__ ) . 'assets/js/sc-documentor.js',array( 'jquery' ), true);
		wp_enqueue_style('sc-documentor-css', plugin_dir_url( __FILE__ ) . 'assets/css/sc-documentor.css' );

		$generator_dir = plugin_dir_url( __FILE__ ) . 'inc/generate.php';
		wp_localize_script('sc-documentor-js', 'documentor_data', array(
			'generator_dir' => $generator_dir
		));
	}    
}

if(!( class_exists('sitecreate_documentor_widget') )){
	class sitecreate_documentor_widget extends WP_Widget {
		
		public function __construct(){
			parent::__construct(
				'sc_documentor_widget', // Base ID
				__('SiteCreate Documentor', 'sc-documentor'), // Name
				array( 'description' => __( 'A ToC and Download Generator for Simple Documentation', 'sc-documentor' ), ) // Args
			);

			add_action('wp_enqueue_scripts', 'sitecreate_documentor_widget_load_scripts');
		}
		
		public function widget($args, $instance) {
			extract($args);
			$title = apply_filters('widget_title', $instance['title']);

			if(is_single() || is_page()) {

				echo $before_widget;

				if($title) {
					echo  $before_title.$title.$after_title;
				} ?>
			
				<div id="sc-documentor" data-content-area-selector="<?php echo esc_attr($instance['content-area-selector']); ?>" data-headings-selector="<?php echo esc_attr($instance['headings-selector']); ?>" data-scroll-offset="<?php echo esc_attr($instance['scroll-offset']); ?>" data-download-title="<?php echo esc_attr($instance['download-document-title']); ?>">	
			    	<div id="sc-documentor-toc"></div>	
				    <div id="sc-documentor-buttons">		
				    <button type="button" class="<?php echo esc_attr($instance['button-css-class']); ?>" name="sc-documentor-build-docs" id="sc-documentor-build-docs"><?php echo esc_attr($instance['download-button-text']); ?></button>
				    </div>		
			    </div>	 
				
				<?php echo $after_widget;
			}
		
		}
		
		public function update($new_instance, $old_instance)
		{
			$instance = $old_instance;

			$instance['content-area-selector'] = $new_instance['content-area-selector'];
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['headings-selector'] = $new_instance['headings-selector'];
			$instance['download-button-text'] = $new_instance['download-button-text'];
			$instance['scroll-offset'] = $new_instance['scroll-offset'];
			$instance['button-css-class'] = $new_instance['button-css-class'];
			$instance['download-document-title'] = $new_instance['download-document-title'];
	
			return $instance;
		}
	
		public function form($instance)
		{
			$defaults = array('title' => 'Browse Documentation', 'content-area-selector' => '.entry-content', 'headings-selector' => 'h1, h2, h3', 'download-button-text' => 'Download Docs', 'scroll-offset' => '30', 'button-css-class' => 'btn', 'download-document-title' => '.entry-title');
			$instance = wp_parse_args((array) $instance, $defaults); ?>
			
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label><br>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('content-area-selector'); ?>">Content Area Selector <br><small>Where shall we find the content? Needs to be an ID or CSS class.</small></label><br><br>
				<input class="widefat" id="<?php echo $this->get_field_id('content-area-selector'); ?>" name="<?php echo $this->get_field_name('content-area-selector'); ?>" value="<?php echo $instance['content-area-selector']; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('headings-selector'); ?>">Headings To Use:</label> <br><small>Comma seperated (eg h1, h2, h3)</small></label><br><br>
				<input class="widefat" id="<?php echo $this->get_field_id('headings-selector'); ?>" name="<?php echo $this->get_field_name('headings-selector'); ?>" value="<?php echo $instance['headings-selector']; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('scroll-offset'); ?>">Scroll Offset:</label> <br><small>Leave blank to disable</small></label><br><br>
				<input class="widefat" id="<?php echo $this->get_field_id('scroll-offset'); ?>" name="<?php echo $this->get_field_name('scroll-offset'); ?>" value="<?php echo $instance['scroll-offset']; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('download-button-text'); ?>">Download Button Text:</label> <br><small>Leave blank to disable</small></label><br><br>
				<input class="widefat" id="<?php echo $this->get_field_id('download-button-text'); ?>" name="<?php echo $this->get_field_name('download-button-text'); ?>" value="<?php echo $instance['download-button-text']; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('download-document-title'); ?>">Download Document Title Selector:</label> <br><small>Where shall we find the Download Document Title? Needs to be an ID or CSS class.</small></label><br><br>
				<input class="widefat" id="<?php echo $this->get_field_id('download-document-title'); ?>" name="<?php echo $this->get_field_name('download-document-title'); ?>" value="<?php echo $instance['download-document-title']; ?>" />
			</p>			
			<p>
				<label for="<?php echo $this->get_field_id('button-css-class'); ?>">Button CSS Class:</label> <br><small>usually 'btn' or such.</small></label><br><br>
				<input class="widefat" id="<?php echo $this->get_field_id('button-css-class'); ?>" name="<?php echo $this->get_field_name('button-css-class'); ?>" value="<?php echo $instance['button-css-class']; ?>" />
			</p>


		<?php
		}
	}
	function sitecreate_core_register_latest_tweets_widget(){
	     register_widget( 'sitecreate_documentor_widget' );
	}
	add_action( 'widgets_init', 'sitecreate_core_register_latest_tweets_widget');
	
}


?>