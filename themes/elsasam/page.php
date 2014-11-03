<?php get_template_part('parts/header'); ?>

<div class="container">
  <div class="row">

    <div class="col-sm-8">
      <div id="content" role="main">
        <div id="content-inner">
          <?php if(have_posts()): while(have_posts()): the_post();?>
          <article role="article" id="post_<?php the_ID()?>" <?php post_class()?>>
            <header>
              <h1 class="page-title"><?php the_title()?></h1>
              <hr/>
            </header>
            <?php the_content()?>
          </article>
          <?php endwhile; ?> 
          <?php else: ?>
          <?php wp_redirect(get_bloginfo('siteurl').'/404', 404); exit; ?>
          <?php endif;?>
        </div>
      </div><!-- /#content -->
    </div>
    
    <div class="col-sm-3 col-sm-offset-1" id="sidebar" role="navigation">
      <?php get_template_part('parts/sidebar'); ?>
    </div>
    
  </div><!-- /.row -->
</div><!-- /.container -->

<?php get_template_part('parts/footer'); ?>
