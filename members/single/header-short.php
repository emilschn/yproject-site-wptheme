<?php
/**
 * Affichage header de User simple
 */
bp_user_link()
?>

<header id="item-header" class="short">
	
	<div id="item-header-container" class="center">
		<div id="item-header-avatar" class="left">
			<a href="<?php bp_loggedin_user_link(); ?>"><?php UIHelpers::print_user_avatar(bp_displayed_user_id()); ?></a>
		</div>

		<div id="item-header-content" class="left">
			<h1><?php the_title(); ?></h1>
		</div>

		<div class="clear"></div>
	</div>
	
</header>