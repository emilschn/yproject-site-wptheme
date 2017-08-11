<?php global $page_controler, $stylesheet_directory_uri; ?>

<main data-userid="<?php echo $page_controler->get_user_id(); ?>">
		
	<?php if ( $page_controler->get_wallet_to_bankaccount_result() != FALSE ): ?>
		<?php if ( $page_controler->get_wallet_to_bankaccount_result() == "success" ): ?>
			<div class="success">Transfert effectué</div>
		<?php else: ?>
			<div class="errors center"><?php echo $page_controler->get_wallet_to_bankaccount_result(); ?></div>
		<?php endif; ?>
	<?php endif; ?>

	<div>
		<div id="settings-img">
			<a href="<?php echo home_url('/modifier-mon-compte'); ?>"><img src="<?php echo get_stylesheet_directory_uri() . "/images/settings.png";?>"></a>
		</div>
	</div>

	<div>

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