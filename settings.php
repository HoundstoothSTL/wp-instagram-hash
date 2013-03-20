<?php
if(!class_exists('WP_Instagram_Hash_Settings'))
{
	class WP_Instagram_Hash_Settings
	{
		/**
		 * Construct the plugin object
		 */
		public function __construct()
		{
			// register actions
            add_action('admin_init', array(&$this, 'admin_init'));
        	add_action('admin_menu', array(&$this, 'add_menu'));
		} // END public function __construct
		
        /**
         * hook into WP's admin_init action hook
         */
        public function admin_init()
        {
        	// register your plugin's settings
        	register_setting('wp_instagram_hash-group', 'instagram_key');
            register_setting('wp_instagram_hash-group', 'tag');
            register_setting('wp_instagram_hash-group', 'image_limit');
            register_setting('wp_instagram_hash-group', 'image_size');
            register_setting('wp_instagram_hash-group', 'image_class');

        	// add your settings section
        	add_settings_section(
        	    'wp_instagram_hash-section', 
        	    'WP Instagram Hash Settings', 
        	    array(&$this, 'settings_section_wp_instagram_hash'), 
        	    'wp_instagram_hash'
        	);
        	
        	// add your setting's fields
            add_settings_field(
                'wp_instagram_hash-instagram_key', 
                'Instagram Developer Key', 
                array(&$this, 'settings_field_input_text'), 
                'wp_instagram_hash', 
                'wp_instagram_hash-section',
                array(
                    'field' => 'instagram_key'
                )
            );
            add_settings_field(
                'wp_instagram_hash-tag', 
                'Hash Tag', 
                array(&$this, 'settings_field_input_text'), 
                'wp_instagram_hash', 
                'wp_instagram_hash-section',
                array(
                    'field' => 'tag'
                )
            );
            add_settings_field(
                'wp_instagram_hash-image_limit', 
                'Image Limit', 
                array(&$this, 'settings_field_input_text'), 
                'wp_instagram_hash', 
                'wp_instagram_hash-section',
                array(
                    'field' => 'image_limit'
                )
            );
            add_settings_field(
                'wp_instagram_hash-image_size', 
                'Image Size', 
                array(&$this, 'settings_field_input_text'), 
                'wp_instagram_hash', 
                'wp_instagram_hash-section',
                array(
                    'field' => 'image_size'
                )
            );
            add_settings_field(
                'wp_instagram_hash-image_class', 
                'Add Image Class', 
                array(&$this, 'settings_field_input_text'), 
                'wp_instagram_hash', 
                'wp_instagram_hash-section',
                array(
                    'field' => 'image_class'
                )
            );
            // Possibly do additional admin_init tasks
        } // END public static function activate
        
        public function settings_section_wp_instagram_hash()
        {
            // Think of this as help text for the section.
            echo 'Set your developer API key and options for your needs';
        }
        
        /**
         * This function provides text inputs for settings fields
         */
        public function settings_field_input_text($args)
        {
            // Get the field name from the $args array
            $field = $args['field'];
            // Get the value of this setting
            $value = get_option($field);
            // echo a proper input type="text"
            echo sprintf('<input type="text" name="%s" id="%s" value="%s" />', $field, $field, $value);
        } // END public function settings_field_input_text($args)
        
        /**
         * add a menu
         */		
        public function add_menu()
        {
            // Add a page to manage this plugin's settings
        	add_options_page(
        	    'WP Instagram Hash Settings', 
        	    'WP Instagram Hash', 
        	    'manage_options', 
        	    'wp_instagram_hash', 
        	    array(&$this, 'plugin_settings_page')
        	);
        } // END public function add_menu()
    
        /**
         * Menu Callback
         */		
        public function plugin_settings_page()
        {
        	if(!current_user_can('manage_options'))
        	{
        		wp_die(__('You do not have sufficient permissions to access this page.'));
        	}
	
        	// Render the settings template
        	include(sprintf("%s/templates/settings.php", dirname(__FILE__)));
        } // END public function plugin_settings_page()
    } // END class WP_Instagram_Hash_Settings
} // END if(!class_exists('WP_Instagram_Hash_Settings'))
