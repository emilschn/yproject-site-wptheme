<header id="item-header" class="short">
	
	<div id="item-header-container" class="center">
		<div id="item-header-avatar" class="left">
			<?php $WDGUser_current = WDGUser::current(); ?>
			<a href="<?php echo home_url('/mon-compte'); ?>"><?php UIHelpers::print_user_avatar( $WDGUser_current->wp_user->ID ); ?></a>
		</div>

		<div id="item-header-content" class="left">
			<h1><?php the_title(); ?></h1>
		</div>

		<div class="clear"></div>
	</div>
	
</header>