<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$declaration_list = WDGROIDeclaration::get_list_by_campaign_id( $page_controler->get_campaign_id() );
global $declaration;
?>

<?php if ( count( $declaration_list ) == 0 ): ?>
	<?php _e( "Retrouvez prochainement ici le suivi de vos paiements de royalties.", 'yproject' ); ?>

<?php else: ?>

	<h3><?php _e( "Ech&eacute;ances pass&eacute;es", 'yproject' ); ?></h3>
	
	<div class="db-form v3 full center">
		<button id="display-list-declarations" class="button transparent"><?php _e( "Afficher les &eacute;ch&eacute;ances pass&eacute;es", 'yproject' ); ?></button>
	</div>
	
	<div id="list-declarations" class="hidden">
		<?php foreach ( $declaration_list as $declaration ): ?>
			<?php if ( $declaration->get_status() == WDGROIDeclaration::$status_finished ): ?>
				<?php locate_template( array( 'pages/view/tableau-de-bord/tab-royalties/item-declaration-past.php' ), TRUE, FALSE ); ?>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
	<br>
	
	<h3><?php _e( "Ech&eacute;ances en cours et &agrave; venir", 'yproject' ); ?></h3>
	
	<?php foreach ( $declaration_list as $declaration ): ?>
		<?php if ( $declaration->get_status() != WDGROIDeclaration::$status_finished ): ?>
			<?php locate_template( array( 'pages/view/tableau-de-bord/tab-royalties/item-declaration-current.php' ), TRUE, FALSE ); ?>
		<?php endif; ?>
	<?php endforeach; ?>
	

<?php endif;