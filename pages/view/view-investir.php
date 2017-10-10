<?php global $page_controler, $stylesheet_directory_uri; ?>


<div id="content">
	
	<?php locate_template( array( 'pages/view/investir/header.php'  ), true ); ?>
	
	<?php if ( $page_controler->get_current_step() == 1 ): ?>
	<?php locate_template( array( 'pages/view/investir/input.php'  ), true ); ?>
	<?php endif; ?>
	
</div><!-- #content -->