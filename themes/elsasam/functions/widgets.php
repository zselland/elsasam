<?php

function elsasam_widgets_init() {

  	/*
    Sidebar (one widget area)
     */
    register_sidebar( array(
        'name' => __( 'Sidebar', 'elsasam' ),
        'id' => 'sidebar-widget-area',
        'description' => __( 'The sidebar widget area', 'elsasam' ),
        'before_widget' => '<section class="%1$s %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ) );

    /*
    Blog Sidebar (one widget area)
     */
    register_sidebar( array(
        'name' => __( 'Blog Sidebar', 'elsasam' ),
        'id' => 'blog-sidebar-widget-area',
        'description' => __( 'The sidebar widget area for the blog section', 'elsasam' ),
        'before_widget' => '<section class="%1$s %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ) );

  	/*
    Footer (three widget areas)
     */
    register_sidebar( array(
        'name' => __( 'Footer', 'elsasam' ),
        'id' => 'footer-widget-area',
        'description' => __( 'The footer widget area', 'elsasam' ),
        'before_widget' => '<div class="%1$s %2$s col-sm-4">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ) );

}
add_action( 'widgets_init', 'elsasam_widgets_init' );

?>
