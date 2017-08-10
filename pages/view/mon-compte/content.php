<?php global $page_controler, $stylesheet_directory_uri; ?>

<main>
		
	<?php if ( $page_controler->get_wallet_to_bankaccount_result() != FALSE ): ?>
		<?php if ( $page_controler->get_wallet_to_bankaccount_result() == "success" ): ?>
			<div class="success">Transfert effectué</div>
		<?php else: ?>
			<div class="errors center"><?php echo $page_controler->get_wallet_to_bankaccount_result(); ?></div>
		<?php endif; ?>
	<?php endif; ?>

	<header id="item-header">

		<?php if ($display_loggedin_user): ?>
		<div>
			<div id="settings-img">
				<a href="<?php echo home_url('/modifier-mon-compte'); ?>"><img src="<?php echo get_stylesheet_directory_uri() . "/images/settings.png";?>"></a>
			</div>
		</div>
		<?php endif; ?>

		<div id="item-header-container">
			<?php locate_template( array( 'members/single/member-header.php' ), true ); ?>
		</div>

	</header>

	<div>

		<ul id="item-submenu">
			<li id="item-submenu-projects" class="selected"><a href="#projects"><?php _e("Projets et investissements", "yproject"); ?></a></li>
			<li id="item-submenu-organizations"><a href="#organizations"><?php _e("Organisations", "yproject"); ?></a></li>
		</ul>

		<div id="item-body">
			<div id="item-body-projects" class="item-body-tab">
				<?php locate_template( array( 'members/single/projects.php'  ), true ); ?>
			</div>

			<div id="item-body-organizations" class="item-body-tab" style="display:none">
				<?php locate_template( array( 'members/single/community.php'  ), true ); ?>
			</div>
		</div>

	</div>
	
</main>