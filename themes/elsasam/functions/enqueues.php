<?php

/*
Google CDN jQuery with a local fallback
See http://www.wpcoke.com/load-jquery-from-cdn-with-local-fallback-for-wordpress/
*/
if( !is_admin()){ 
    $url = '//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'; 
    $test_url = @fopen($url,'r'); 
    if($test_url !== false) { 
        function load_external_jQuery() {
            wp_deregister_script('jquery'); 
            wp_register_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'); 
            wp_enqueue_script('jquery'); 
        }
        add_action('wp_enqueue_scripts', 'load_external_jQuery'); 
    } else {
        function load_local_jQuery() {
            wp_deregister_script('jquery'); 
            wp_register_script('jquery', get_bloginfo('template_url').'/js/jquery-1.11.1.min.js', __FILE__, false, '1.11.1', true); 
            wp_enqueue_script('jquery'); 
        }
    add_action('wp_enqueue_scripts', 'load_local_jQuery'); 
    }
}

function elsasam_enqueues()
{
	wp_register_style('bootstrap-css', get_template_directory_uri() . '/css/bootstrap.min.css', false, null);
	wp_enqueue_style('bootstrap-css');

  	wp_register_style('elsasam-css', get_template_directory_uri() . '/css/elsasam.css', false, '1.00', null);
	wp_enqueue_style('elsasam-css');

    wp_register_style('fontawesome-css', '//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css', false, null);
    wp_enqueue_style('fontawesome-css');

  	wp_register_script('modernizr', get_template_directory_uri() . '/js/modernizr-2.6.2.min.js', false, null, true);
    wp_enqueue_script('modernizr');

	wp_register_script('html5shiv.js', get_template_directory_uri() . '/js/html5shiv.js', false, null, true);
    wp_enqueue_script('html5shiv.js');

  	wp_register_script('respond', get_template_directory_uri() . '/js/respond.min.js', false, null, true);
    wp_enqueue_script('respond');

  	wp_register_script('bootstrap-js', get_template_directory_uri() . '/js/bootstrap.js', false, null, true);
    wp_enqueue_script('bootstrap-js');

    wp_register_script('elsasam-js', get_template_directory_uri() . '/js/elsasam.js', false, null, true);
    wp_enqueue_script('elsasam-js');

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
      }
}
add_action('wp_enqueue_scripts', 'elsasam_enqueues', 100);

?>
