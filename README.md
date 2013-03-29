WP Instagram Hash
==================

This is a really simple plugin to integrate an instagram feed into your WordPress site based on a hashtag.

##Usage
To use, just activate it, go to the settings and input your Instagram Developer Key and your settings then place the function in your template like so `<?php wp_instagram_hash_do(); ?>`

##Changelog
- 0.2.0 | Updated to include caching using wp_transients and rewritten public function as an echo, getting the json data is now it's own function.