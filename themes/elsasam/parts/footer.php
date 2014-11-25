<div class="container footer">
  <hr/>
	<div class="row">
    <?php dynamic_sidebar('footer-widget-area'); ?>
  </div>
  <div class="row">
    <?php 
      global $redux_es;
      $fb_url = $redux_es['opt-text-url-facebook'];
      $pin_url = $redux_es['opt-text-url-pinterest'];
      $inst_url = $redux_es['opt-text-url-instagram'];
    ?>
    <ul class="social pull-right list-inline">
      <?php 
        if ($fb_url != '') { 
          echo '<li><a class="fb" href="'.$fb_url.'"><i class="fa fa-facebook-square fa-2x"></i></a></li>';
        }
        if ($pin_url != '') { 
          echo '<li><a class="pin" href="'.$pin_url.'"><i class="fa fa-pinterest-square fa-2x"></i></a></li>';
        }
        if ($inst_url != '') { 
          echo '<li><a class="inst" href="'.$inst_url.'"><i class="fa fa-instagram fa-2x"></i></a></li>';
        }
      ?>
    </ul>
    <?php        
      $args = array(
        'theme_location' => 'footer-nav',
        'depth' => 1,
        'container'  => false,
        'fallback_cb' => false,
        'menu_class' => 'nav nav-pills pull-left',
        'walker' => new BootstrapNavMenuWalker()
      );
      wp_nav_menu($args);
    ?>
  </div>

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
