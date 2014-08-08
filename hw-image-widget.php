<?php
/*
Plugin Name: HW Image Widget
Plugin URI: http://wordpress.org/extend/plugins/hw-image-widget/
Description: Image widget that will allow you to choose responsive or fixed sized behavior. Includes TinyMCE rich text editing of the text description. A custom HTML-template for the widget can be created in the active theme folder (a default template will be used if this custom template does not exist). Carrington Build is supported.
Author: H&aring;kan Wennerberg
Version: 3.0
Author URI: http://webartisan.se/
License: LGPLv3 - http://www.gnu.org/licenses/lgpl-3.0.html
*/

$hwim_setup = new HWIM_Setup();

class HWIM_Setup {
	
	protected $carrington_post_types = array();
	protected $load_hwim_backend = false;
	
	public function __construct() {
		
		add_filter('cfct-admin-edit-post-type', array($this, 'filter_carrington_edit_post_type'), 99999);
		add_filter('cfct-build-enabled-post-types', array($this, 'filter_carrington_enabled_post_types'), 99999);
		add_filter('cfct-module-cfct-widget-module-hwim-admin-form', array($this, 'filter_carrington_admin_form'), 10, 2);
		
		add_action('admin_enqueue_scripts', array($this, 'action_admin_enqueue_scripts'));
		add_action('admin_footer', array($this, 'action_admin_footer'));
		add_action('plugins_loaded', array($this, 'action_plugins_loaded'));
		add_action('widgets_init', array($this, 'action_widgets_init'));
		
		if (stristr($_SERVER['REQUEST_URI'], 'widgets.php')) {
			$this->load_hwim_backend = true;
		}
	}
	
	public function action_admin_enqueue_scripts() {
		if ($this->load_hwim_backend) {
			wp_enqueue_media();
			wp_enqueue_script(
				'hwim-be',
				plugins_url( 'js/back-end.js', __FILE__ ),
				array( 'jquery' ),
				'4.0'
			);
			wp_localize_script( 'hwim-be', 'objectL10n', array(
				'insertIntoWidget'  => __( 'Insert into widget', 'hwim' ),
				'insertMedia' => __( 'Insert Media', 'hwim' ),
				'returnToLibrary' => __( 'Return to Library', 'hwim' ),
				'selectImage' => __( 'Select Image', 'hwim' ),
				'insertImage' => __( 'Insert Image', 'hwim' )
			) );
		}
	}
	
	function action_admin_footer() {
		if ($this->load_hwim_backend) {
			$tinymce_settings = array(
				'editor_height' => 300,
				'media_buttons' => '',
				'default_editor' => 'visual' // Always load visual editor (else it will fail).
			);
			
			include 'html/text-editor.php';
		}
	}
	
	function action_plugins_loaded() {
		load_plugin_textdomain('hwim', false, dirname(plugin_basename(__FILE__)) . '/languages/');
	}

	function action_widgets_init() {
		register_widget( 'HW_Image_Widget' );
	}

	public function filter_carrington_edit_post_type($post_type) {
		if (in_array($post_type, $this->carrington_post_types) === true) {
			$this->load_hwim_backend = true;
		}
		return $post_type;
	}
	
	public function filter_carrington_enabled_post_types($post_types) {
		$this->carrington_post_types = $post_types;
		return $post_types;
	}
	
	public function filter_carrington_admin_form($module_form, $data) {
		return str_replace('<p>hwim</p>', '', $module_form);
	}
}


class HW_Image_Widget extends \WP_Widget {

	protected $widget_id = 'hwim';

	/**
	 * Default constructor.
	 */
	function __construct() {
		$widget_ops = array('description' => __('Image widget to display, describe and possibly link an image.', 'hwim'));
		parent::__construct($this->widget_id, 'HW Image Widget', $widget_ops);
	}

	/**
	* \see WP_Widget::form
	*/
	function form($instance) {
		$widget_id = (isset($this->id) ? $this->id : '0');
		$div_id = $widget_id;

		// Load widget defaults and ovveride them with user defined settings.
		$instance = $this->merge_arrays($this->get_defaults(), $instance);

		// Remove SCRIPT tags for preview area.
		$doc = new DOMDocument();
		$doc->loadHTML('<?xml encoding="UTF-8">' . $instance['text']);
		$script_tags = $doc->getElementsByTagName('script');

		$length = $script_tags->length;

		for ($i = 0; $i < $length; $i++) {
			$script_tags->item($i)->parentNode->removeChild($script_tags->item($i));
		}

		$content_cleaned = $doc->saveHTML();
		
		// Display form.
		include 'html/back-end.php';
	}

	/**
	 * Returns the default values for this widget.
	 *
	 * \return array Default values for this widget.
	 */
	protected function get_defaults() {
		return apply_filters($this->widget_id . '_get_defaults', array(
			'title' => '',
			'text' => '',
			'src' => '',
			'display_size' => 'responsive',
			'display_width' => '',
			'display_height' => '',
			'original_width' => '',
			'original_height' => '',
			'keep_aspect_ratio' => true,
			'alt' => '',
			'url' => '',
			'target_option' => '',
			'target_name' => ''
		));
	}

	/**
	 * Returns the image display size options for this widget.
	 *
	 * \return array
	 */
	protected function get_display_sizes() {
		return apply_filters($this->widget_id . '_display_sizes', array(
			'responsive' => __('Responsive', 'hwim'),
			'fixed' => __('Fixed', 'hwim')
		));
	}

	/**
	 * Returns a list of possible link targets.
	 *
	 * \return array
	 */
	protected function get_targets() {
		return apply_filters($this->widget_id . '_targets', array(
			'' => '',
			'_blank' => '_blank',
			'_self' => '_self',
			'_parent' => '_parent',
			'_top' => '_top',
			'other' => __('Other', 'hwim')
		));
	}

	/**
	 * Merges two arrays withour reindexing, with overwriting (not the same as
	 * PHP array_merge()).
	 *
	 * \param array $array1 First array to merge.
	 * \param array $array2 Second array to merge and overwrite values with.
	 * \return array Merged arrays.
	 */
	protected function merge_arrays($array1, $array2) {
		return array_diff_key($array1, $array2) + $array2;
	}

	/**
	 * \see WP_Widget::widget
	 */
	function widget($args, $instance) {
		extract( $args );

		$instance['title'] = \apply_filters('widget_title', \esc_attr(@$instance['title']));
		$instance['keep_aspect_ratio'] = (isset($instance['keep_aspect_ratio'])) ? true : false;

		// Output widget to front-end.
		echo $before_widget;

		if ('' != trim($instance['title'])) {
			echo apply_filters('hwim_title', $before_title . $instance['title'] . $after_title);
		}

		// Allow theme to supply a non-standard template.
		$template = locate_template('hwim-template.php');
		if ( $template ) {
			include $template;
		} else {
			include 'html/hwim-template.php';
		}

		echo $after_widget;
	}

	/**
	* \see WP_Widget::update
	*/
	public function update($new_instance, $old_instance) {
		$new_instance['keep_aspect_ratio'] = isset($new_instance['keep_aspect_ratio']);
		return $new_instance;
	}
}
