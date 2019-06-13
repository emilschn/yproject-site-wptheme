<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>


<div id="content">
	
	<?php locate_template( array( 'pages/view/declarer-chiffre-daffaires/header.php'  ), true ); ?>
	
	<?php
	$current_step = $page_controler->get_current_step();
	switch ( $current_step ) {
		case WDGROIDeclaration::$status_declaration:
			locate_template( array( 'pages/view/declarer-chiffre-daffaires/declaration.php'  ), true );
			break;
		case WDGROIDeclaration::$status_payment:
			locate_template( array( 'pages/view/declarer-chiffre-daffaires/summary.php'  ), true );
			break;
		case WDGROIDeclaration::$status_payment . '2':
			locate_template( array( 'pages/view/declarer-chiffre-daffaires/meanofpayment.php'  ), true );
			break;
		default:
			locate_template( array( 'pages/view/declarer-chiffre-daffaires/confirmation.php'  ), true );
			break;
	}
	?>
	
</div><!-- #content -->