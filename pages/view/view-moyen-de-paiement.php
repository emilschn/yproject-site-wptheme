<?php global $page_controler, $stylesheet_directory_uri; ?>


<div id="content">
	
	<?php locate_template( array( 'pages/view/investir/header.php'  ), true ); ?>
	
	<div class="padder">
		<?php if ( $page_controler->get_display_error() != "" ): ?>
		<span class="error"><?php echo $page_controler->get_display_error(); ?></span><br><br>
		<?php endif; ?>
		
		<?php if ( $page_controler->is_list_displayed() ): ?>
			<?php locate_template( array( 'pages/view/moyen-de-paiement/list.php'  ), true ); ?>
		
		<?php else: ?>
			<?php locate_template( array( 'pages/view/moyen-de-paiement/' .$page_controler->get_current_view(). '.php'  ), true ); ?>
		
		<?php endif; ?>
	</div>
	
</div><!-- #content -->