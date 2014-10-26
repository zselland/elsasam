<form role="search" method="get" id="searchform" class="form-inline" action="<?php echo esc_url( home_url( '/'  ) ); ?>">
	<div>
		<label class="sr-only" for="s"><?php _e( 'Search for:', 'woocommerce' ); ?></label>
		<div class="input-group">
			<input type="text" value="<?php echo get_search_query(); ?>" name="s" id="s" placeholder="<?php _e( 'Search for products', 'woocommerce' ); ?>" />
			<span class="input-group-btn">
	        	<input class="fa-btn-text" type="submit" id="searchsubmit" class="fa-btn-text" value="&#xf002;" />
	      	</span>
		</div>
		<input type="hidden" name="post_type" value="product" />
	</div>
</form>