<?php
/**
 * Include and setup custom metaboxes and fields.
 *
 * @category YourThemeOrPlugin
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/webdevstudios/Custom-Metaboxes-and-Fields-for-WordPress
 */

add_filter( 'cmb_meta_boxes', 'elsasam_metaboxes' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function elsasam_metaboxes( array $meta_boxes ) {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_elsasam_';

	/**
	 * Sample metabox to demonstrate each field type included
	 */
	$meta_boxes['Slide Settings'] = array(
		'id'         => 'slide_settings',
		'title'      => __( 'Slide Settings', 'cmb' ),
		'pages'      => array( 'slide', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		// 'cmb_styles' => true, // Enqueue the CMB stylesheet on the frontend
		'fields'     => array(
			array(
				'name' => 'Background Image',
				'desc' => 'Upload an image or enter an URL.',
				'id' => $prefix . 'bg_image',
				'type' => 'file',
				'allow' => array( 'attachment' ) // limit to just attachments with array( 'attachment' )
			),			
			array(
				'name'    => __( 'CTA', 'cmb' ),
				'desc'    => __( 'Create the CTA for the slide', 'cmb' ),
				'id'      => $prefix . 'slide_cta',
				'type'    => 'wysiwyg',
				'options' => array( 'textarea_rows' => 5, ),
			),
			array(
			    'name' => 'CTA Color',
			    'id'   => $prefix . 'slide_cta_color',
			    'type' => 'select',
			    'options' => array(
					'light' => __( 'Light', 'cmb' ),
					'dark' => __( 'Dark', 'cmb' ),
				),
			    'default'  => 'Light',
			),
			array(
				'name'    => __( 'CTA Position', 'cmb' ),
				'desc'    => __( 'Select the horizontal position for the CTA', 'cmb' ),
				'id'      => $prefix . 'slide_cta_position',
				'type'    => 'select',
				'options' => array(
					'col-sm-7' => __( 'Left', 'cmb' ),
					'col-sm-6 col-sm-offset-3' => __( 'Center', 'cmb' ),
					'col-sm-7 col-sm-offset-5' => __( 'Right', 'cmb' ),
				),
				'default' => 'left',
			),
		),
	);

	return $meta_boxes;
}

add_action( 'init', 'cmb_initialize_cmb_meta_boxes', 9999 );
/**
 * Initialize the metabox class.
 */
function cmb_initialize_cmb_meta_boxes() {

	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once locate_template('/functions/cmb/init.php');

}

function cpt_posts($argument) {

    $post_args = array(
        'post_type' => $argument,
        'posts_per_page' => -1,
    );

    $options = array();
    $options[] = array(
    		'name' => 'None (use global setting)',
    		'value' => '',
    	);
    foreach ( get_posts( $post_args ) as $post ) {
        $title = get_the_title( $post->ID );
        $link = get_permalink($post->ID);

        $options[] = array(
            'name'  => $title,
            'value' => $link,
        );
    }

    return $options;
}

function cpt_posts_id($argument) {

    $post_args = array(
        'post_type' => $argument,
        'posts_per_page' => -1,
    );

    $options = array();

    foreach ( get_posts( $post_args ) as $post ) {
        $title = get_the_title( $post->ID );

        $options[] = array(
            'name'  => $title,
            'value' => $post->ID,
        );
    }

    return $options;
}

function cpt_posts_by_term($cpt, $tax, $term) {

    $post_args = array(
        'post_type' => $argument,
        'tax_query' => array(
			array(
				'taxonomy' => $tax,
				'field'    => 'slug',
				'terms'    => $term,
			),
		),
        'posts_per_page' => -1,
    );

    $options = array();
    $options[] = array(
    		'name' => 'None (use global setting)',
    		'value' => '',
    	);
    foreach ( get_posts( $post_args ) as $post ) {
        $title = get_the_title( $post->ID );
        $link = get_permalink($post->ID);

        $options[] = array(
            'name'  => $title,
            'value' => $link,
        );
    }

    return $options;
}