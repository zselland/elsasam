<?php
/*
All the functions are in the PHP pages in the functions/ folder.
*/

require_once locate_template('/functions/cleanup.php');
require_once locate_template('/functions/setup.php');
require_once locate_template('/functions/enqueues.php');
require_once locate_template('/functions/navbar.php');
require_once locate_template('/functions/widgets.php');
require_once locate_template('/functions/search.php');
require_once locate_template('/functions/feedback.php');
require_once locate_template('/functions/custom-post-type.php');
require_once locate_template('/functions/custom-taxonomy.php');
require_once locate_template('/functions/metaboxes.php');
require_once locate_template('/functions/cpt-setup.php');


add_action('after_setup_theme', 'true_load_theme_textdomain');

function true_load_theme_textdomain(){
    load_theme_textdomain( 'elsasam', get_template_directory() . '/languages' );
}

?>
