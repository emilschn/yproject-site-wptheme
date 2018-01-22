<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<main data-userid="<?php echo $page_controler->get_user_id(); ?>">
		
	<?php if ( $page_controler->get_wallet_to_bankaccount_result() != FALSE ): ?>
		<?php if ( $page_controler->get_wallet_to_bankaccount_result() == "success" ): ?>
			<div class="success">Transfert effectué</div>
		<?php else: ?>
			<div class="errors center"><?php echo $page_controler->get_wallet_to_bankaccount_result(); ?></div>
		<?php endif; ?>
	<?php endif; ?>

	<div>

		<div id="item-body">
			<div id="item-body-projects" class="item-body-tab">
				<?php locate_template( array( 'members/single/projects.php'  ), true ); ?>
			</div>

			<div id="item-body-organizations" class="item-body-tab" style="display:none">
				<?php locate_template( array( 'members/single/community.php'  ), true ); ?>
			</div>

			<div id="item-body-parameters" class="item-body-tab" style="display:none">
				<?php locate_template( array( 'pages/view/mon-compte/content-parameters.php'  ), true ); ?>
			</div>
		</div>

	</div>
	
</main>