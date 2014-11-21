<div class="container footer">
  <hr/>
	<div class="row">
    <?php dynamic_sidebar('footer-widget-area'); ?>
  </div>
          <?php        
            $args = array(
              'theme_location' => 'footer-nav',
              'depth' => 1,
              'container'  => false,
              'fallback_cb' => false,
              'menu_class' => 'nav nav-pills',
              'walker' => new BootstrapNavMenuWalker()
            );
            wp_nav_menu($args);
        ?>

  <hr/>
  <div class="row">
    <div class="col-lg-12">
      <p>&copy; <?php echo date('Y'); ?> <a href="<?php echo home_url('/'); ?>"><?php bloginfo('name'); ?></a></p>
    </div>
  </div>
</div>
<!-- Go to www.addthis.com/dashboard to customize your tools -->
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-546ed4bf70987c08" async="async"></script>
<?php wp_footer(); ?>
</body>
</html>
