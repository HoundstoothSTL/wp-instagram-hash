<div class="wrap">
    <h2>WP Instagram Hash</h2>
    <form method="post" action="options.php"> 
        <?php @settings_fields('wp_instagram_hash-group'); ?>
        <?php @do_settings_fields('wp_instagram_hash-group'); ?>

        <?php do_settings_sections('wp_instagram_hash'); ?>

        <?php @submit_button(); ?>
    </form>
</div>