<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>


<header>
	
	<div class="center">
		<h1><?php _e( "Royalties", 'yproject' ); ?></h1>

		<div id="invest-breadcrumb">
			<span class="<?php if ( $page_controler->get_current_step() == WDGROIDeclaration::$status_declaration ): ?>selected<?php endif; ?>"><?php _e( "D&eacute;claration", 'yproject' ); ?></span>
			<span class="<?php if ( $page_controler->get_current_step() == WDGROIDeclaration::$status_payment ): ?>selected<?php endif; ?>"><?php _e( "Paiement", 'yproject' ); ?></span>
			<span class="<?php if ( $page_controler->get_current_step() == WDGROIDeclaration::$status_transfer ): ?>selected<?php endif; ?>"><?php _e( "Confirmation", 'yproject' ); ?></span>
		</div>
	</div>

</header>