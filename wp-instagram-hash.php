<?php
/*
Plugin Name: WP Instagram Hash
Plugin URI: https://github.com/HoundstoothSTL
Description: A very simple plugin to integrate Instragram images onto your site using a #hashtag
Version: 1.0
Author: Rob Bennet
Author URI: http://www.robbennet.com
License: None
*/
/*
Copyright 2013  Rob Bennet  (email : rob@madebyhoundstooth.com)
Use it however you want.  Sell it, whatever bro.

*/

if(!class_exists('WP_Instagram_Hash'))
{
	class WP_Instagram_Hash
	{
		/**
		 * Construct the plugin object
		 */
		public function __construct()
		{
        	// Initialize Settings
            require_once(sprintf("%s/settings.php", dirname(__FILE__)));
            $WP_Instagram_Hash_Settings = new WP_Instagram_Hash_Settings();
        	
		} // END public function __construct
	    
		/**
		 * Activate the plugin
		 */
		public static function activate()
		{
			// Do nothing
		} // END public static function activate
	
		/**
		 * Deactivate the plugin
		 */		
		public static function deactivate()
		{
			// Do nothing
		} // END public static function deactivate
	} // END class WP_Instagram_Hash
} // END if(!class_exists('WP_Instagram_Hash'))

if(class_exists('WP_Instagram_Hash')) {
	// Installation and uninstallation hooks
	register_activation_hook(__FILE__, array('WP_Instagram_Hash', 'activate'));
	register_deactivation_hook(__FILE__, array('WP_Instagram_Hash', 'deactivate'));

	// instantiate the plugin class
	$wp_instagram_hash = new WP_Instagram_Hash();
	
    // Add a link to the settings page onto the plugin page
    if(isset($wp_instagram_hash))
    {
        // Add the settings link to the plugins page
        function plugin_settings_link($links)
        { 
            $settings_link = '<a href="options-general.php?page=wp_instagram_hash">Settings</a>'; 
            array_unshift($links, $settings_link); 
            return $links; 
        }

        $plugin = plugin_basename(__FILE__); 
        add_filter("plugin_action_links_$plugin", 'plugin_settings_link');
    }

    /**
     * Make the Function
     */		
    function wp_instagram_hash_do() {
        // Get class for Instagram
	    require_once(sprintf("%s/instagram.class.php", dirname(__FILE__)));
	    $instagram_key = get_option('instagram_key');

	    // Initialize class with client_id
	    $instagram = new Instagram($instagram_key);

	    // Set keyword for #hashtag
	    $tag = get_option('tag');

	    // Get recently tagged media
		$media = $instagram->getTagMedia($tag);

		// Set number of photos to show
	    $limit = get_option('image_limit');

	    // Set height and width for photos
	    $size = get_option('image_size');

	    // Set a class on the images
	    $class = get_option('image_class');

	    // Show results
	    // Using for loop will cause error if there are less photos than the limit
	    foreach(array_slice($media->data, 0, $limit) as $data)
	    {
	        // Show photo
	        echo '<img class="'.$class.'" src="'.$data->images->thumbnail->url.'" height="'.$size.'" width="'.$size.'" alt="Instagram '.$tag.' Image">';
	    }
    } // END public function add_menu()
}