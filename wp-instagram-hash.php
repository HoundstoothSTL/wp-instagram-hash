<?php
/*
Plugin Name: WP Instagram Hash
Plugin URI: https://github.com/HoundstoothSTL
Description: A very simple plugin to integrate Instragram images onto your site using a #hashtag
Version: 0.2.0
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

    /** Make the Functions **/	

    //Added curl for faster response
	function get_curl($url){
	    if(function_exists('curl_init')) {
	        $ch = curl_init();
	        curl_setopt($ch, CURLOPT_URL,$url);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	        curl_setopt($ch, CURLOPT_HEADER, 0);
	        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
	        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0); 
	        $output = curl_exec($ch);
	        echo curl_error($ch);
	        curl_close($ch);
	        return $output;

	    } else {
	        return file_get_contents($url);
	    }

	}

    function get_instagram() {
        // Get clientID for Instagram
	    $instagram_key = get_option('instagram_key');

	    // Set keyword for #hashtag
	    $tag = get_option('tag');

	    // Show results
	    $key = 'instagram_cached_json_object';
		$cache = get_transient($key);

		// Client ID for Instagram API
		$api = 'https://api.instagram.com/v1/tags/'.$tag.'/media/recent?client_id='.$instagram_key;

		if($cache !== false) {
			return json_decode($cache);
		} else {
			//If there is no cached version, we make the call
			$response = get_curl($api); // change request path to pull different photos

			$images = array(); // set the images array

			if(is_wp_error($response)) {
				// In case Instagram is not responding, return last sucessful cache
				return json_decode(get_option($key));
			} else {
				// If everything's okay, parse the body and json_decode it
				// Decode the response and build an array
		        foreach(json_decode($response)->data as $item){

		            $title = (isset($item->caption))?mb_substr($item->caption->text,0,70,"utf8"):null;

		            $src = $item->images->thumbnail->url; //Caches standard res img path to variable $src

		            //Location coords seemed empty in the results but you would need to check them as mostly be undefined
		            $lat = (isset($item->data->location->latitude))?$item->data->location->latitude:null; // Caches latitude as $lat
		            $lon = (isset($item->data->location->longtitude))?$item->data->location->longtitude:null; // Caches longitude as $lon

		            $images[] = array(
		            "title" => htmlspecialchars($title),
		            "src" => htmlspecialchars($src),
		            "lat" => htmlspecialchars($lat),
		            "lon" => htmlspecialchars($lon) // Consolidates variables to an array
		            );
		        }

		        // Store the result in a transient, expires after 1 day
				// Also store it as the last successful using update_option
				set_transient($key, json_encode($images), 60*60*24);
				update_option($key, json_encode($images));
				return $images;
			}
		}
    } // END public function get_instagram()

    function wp_instagram_hash_do() {
	    $instagram_images = get_instagram();

	    // Set number of photos to show
	    $limit = get_option('image_limit');

	    // Set height and width for photos
	    $size = get_option('image_size');

	    // Set a class on the images
	    $class = get_option('image_class');

		$count = 1;
		foreach($instagram_images as $key => $value) {
			echo '<img src="' . $value->src . '" alt="' . $value->title . '" width="' . $size . '" height="' . $size . '" class="' . $class . '">';
			if(++$count > $limit) break;
		}
	} // END public function wp_instagram_hash_do()
}