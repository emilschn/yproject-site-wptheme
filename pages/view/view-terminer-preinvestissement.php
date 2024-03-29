<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>


<div id="content">

	<?php if ( !$page_controler->get_current_investment() ): ?>
		ERROR - WDGVTP1

	<?php else: ?>
	<header>

		<div class="center">
			<h1><?php _e( 'invest.finish-preinvestment.CHANGE_TERMS', 'yproject' ); ?> <?php echo $page_controler->get_current_campaign()->data->post_title; ?></h1>
		</div>

		<div class="center align-justify">
			<?php echo sprintf( __( 'invest.finish-preinvestment.YOU_HAD_PREINVESTED', 'yproject' ), $page_controler->get_current_investment()->get_saved_amount(), $page_controler->get_current_campaign()->data->post_title ); ?>
			<br><br>
			<?php _e( 'invest.finish-preinvestment.AFTER_THE_VOTE', 'yproject' ); ?>
			<br>
			<?php echo $page_controler->get_current_campaign()->contract_modifications(); ?>
			<br><br>
		</div>

	</header>
	
	<div class="view-share center">
		<br><br>
		
		<?php
		$current_step = $page_controler->get_current_step();
		switch ( $current_step ) {
			case WDG_Page_Controler_PreinvestmentFinish::$step_validation:
				locate_template( array( 'pages/view/investir/contract-preinvestment.php'  ), true );
				break;
			
			case WDG_Page_Controler_PreinvestmentFinish::$step_cancel:
				locate_template( array( 'pages/view/investir/cancel.php'  ), true );
				break;
		}
		?>
		
		<br><br>
	</div>
	<?php endif; ?>
	
</div><!-- #content -->