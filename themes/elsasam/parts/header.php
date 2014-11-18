<!DOCTYPE html>
<html>
<head>
	<title><?php is_front_page() ? bloginfo('name') : wp_title('•', true, ''); ?></title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<!--[if lt IE 8]>
<div class="alert alert-warning">
	You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.
</div>
<![endif]-->    


<?php
/*
Lower menubar (main menubar, below site title)
==============================================
Delete this whole <nav>...</nav> block if you don't want it, and delete the line in func/navbar.php that looks like this:
register_nav_menu('lower-bar', __('Main menu (below site title)'));
*/
?>
<header id="header">
  <div class="container main-nav">
    <nav class="navbar navbar-default navbar-static" role="navigation">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".lower-navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand text-hide" href="<?php echo home_url('/'); ?>"><?php bloginfo('name'); ?></a>
      </div><!-- /.navbar-header -->
      <div class="collapse navbar-collapse lower-navbar">    
        <?php global $woocommerce; ?>
        <ul id="woo-cart" class="nav navbar-nav navbar-right">
          <li>
            <a class="cart-contents" href="<?php echo $woocommerce->cart->get_cart_url(); ?>" title="<?php _e('View your shopping cart', 'woothemes'); ?>"><?php echo sprintf(_n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count);?> - <?php echo $woocommerce->cart->get_cart_total(); ?></a>
          </li>
        </ul>
        <?php        
            $args = array(
              'theme_location' => 'lower-bar',
              'depth' => 0,
              'container'  => false,
              'fallback_cb' => false,
              'menu_class' => 'nav navbar-nav navbar-right',
              'walker' => new BootstrapNavMenuWalker()
            );
            wp_nav_menu($args);
        ?>
      </div><!-- /.navbar-collapse -->
    </nav>
  </div>
</header>