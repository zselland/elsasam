<?php
/*
Template Name: Home Page
*/
?>

<?php get_template_part('parts/header'); ?>

<?php
$slide_args = array(
	'post_type' => 'slide',
	'posts_per_page' => -1,
	'orderby' => 'menu_order', 
	'order' => 'ASC'
);
$slide_query = new WP_Query( $slide_args );
?>

<div id="carousel-main-home" class="carousel slide feature-area" data-ride="carousel" data-interval="false">
	<?php if($slide_query->have_posts()) : 
	$i = 0; ?>
	<ol class="carousel-indicators">
		<?php while($slide_query->have_posts()) : $slide_query->the_post(); ?>
			<li data-target="#carousel-main-home" class="<?php if( $slide_query->current_post == 0 ): echo 'active'; endif; ?>" data-slide-to="<?php echo $i++; ?>"></li>
		<?php endwhile; ?>
	</ol>
<!-- Wrapper for slides -->
  <div class="carousel-inner">
	<?php
	$i = 0;
	while($slide_query->have_posts()) : $slide_query->the_post(); 
	$i++;
	if ( $i == 1 ) { 
		$active = 'active';
	}
	else {
		$active = '';
	}
	?>
	
	<?php 
		$cta_align_class = get_post_meta($post->ID, '_elsasam_slide_cta_position', true);
		$cta_color_class = get_post_meta( $post->ID, '_elsasam_slide_cta_color', true );
	?>

    <div class="item <?php echo $active; ?> post-ID-<?php echo $post->ID; ?>">
		<div class="container">
			<div class="row">
				<div class="col-sm-12" style="background-image:url(<?php echo get_post_meta($post->ID, '_elsasam_bg_image', true); ?>);">
					<div class="row">
						<div class="<?php echo $cta_align_class; ?>">
							<div class="slide-cta <?php echo $cta_color_class; ?>">
								<?php echo wpautop(get_post_meta($post->ID, '_elsasam_slide_cta', true)); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
    
    <?php endwhile; wp_reset_postdata(); endif; ?>
  </div>

  <!-- Controls -->
  <a class="left carousel-control" href="#carousel-main-home" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left"></span>
  </a>
  <a class="right carousel-control" href="#carousel-main-home" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right"></span>
  </a>

</div>


<div class="container">
  <div class="row">

    <div class="col-md-12">
      <div id="content" role="main">
        <?php if(have_posts()): while(have_posts()): the_post();?>
        <article role="article" id="post_<?php the_ID()?>" <?php post_class()?>>
	          <?php the_content()?>
        </article>
        <?php endwhile; ?> 
        <?php else: ?>
        <?php wp_redirect(get_bloginfo('siteurl').'/404', 404); exit; ?>
        <?php endif;?>
      </div><!-- /#content -->
    </div>
    
  </div><!-- /.row -->
</div><!-- /.container -->

<?php get_template_part('parts/footer'); ?>
