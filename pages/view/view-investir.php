<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>


<div id="content">
	
	<?php locate_template( array( 'pages/view/investir/header.php'  ), true ); ?>
	
	<?php
	$current_step = $page_controler->get_current_step();
	switch ( $current_step ) {
		case 2:
			locate_template( array( 'pages/view/investir/user-details.php'  ), true );
			break;
		case 3:
			locate_template( array( 'pages/view/investir/contract.php'  ), true );
			break;
		default:
			locate_template( array( 'pages/view/investir/input.php'  ), true );
			break;
	}
	?>
	
</div><!-- #content -->

<?php 
$custom_footer_code = $page_controler->get_current_campaign()->custom_footer_code();
if ( !empty( $custom_footer_code ) ) {
	echo $custom_footer_code;
}