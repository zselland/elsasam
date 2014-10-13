<?php

/*  CPT Setup
/* ------------------------------------ */ 

// required
$slide_args['name'] = 'slide';
// optional
$slide_args['labels'] = array(
  'singular' => 'Slide',
  'plural'   => 'Slides',
  'menu'     => 'Slides'
);
$slide_args['options'] = array(
  'public'         => true,
  'hierarchical'   => false,
  'supports'       => array('title', 'thumbnail', 'custom-fields', 'page-attributes'),
  'has_archive'    => true,
  'menu_position'  => 5,
);
$Slides = new Bamboo_Custom_Post_Type($slide_args);
