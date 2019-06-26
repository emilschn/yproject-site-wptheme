<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
global $adjustment_obj, $declaration_obj;
?>

<div id="adjustment-item-<?php echo $adjustment_obj->id; ?>" class="adjustment-item">
	<div>
		<?php echo $declaration_obj->get_formatted_date( 'due' ); ?>
	</div>
	<div class="strong">
		<?php echo $adjustment_obj->amount; ?> &euro;
	</div>
	<div>
		<?php _e( "Etat :", 'yproject' ); ?>
		<?php if ( $adjustment_obj->get_status() == WDGAdjustment::$status_upcoming ): ?>
			<?php _e( "&Agrave; venir", 'yproject' ); ?>
		<?php endif; ?>
		<?php if ( $adjustment_obj->get_status() == WDGAdjustment::$status_done ): ?>
			<?php _e( "Appliqu&eacute;", 'yproject' ); ?>
		<?php endif; ?>
	</div>
</div>

<div id="adjustment-item-more-<?php echo $adjustment_obj->id; ?>" class="adjustment-item-more hidden">
	<hr>
	
	<div class="db-form v3 center align-left">
		<strong><?php _e( "Versement au moment duquel l'ajustement s'applique", 'yproject' ); ?></strong><br>
		<?php echo $declaration_obj->get_formatted_date( 'due' ); ?><br><br>
		
		<strong><?php _e( "Type d'ajustement", 'yproject' ); ?></strong><br>
		<?php echo WDGAdjustment::$types_str_by_id[ $adjustment_obj->type ]; ?><br><br>
		
		<strong><?php _e( "Diff&eacute;rentiel de CA", 'yproject' ); ?></strong><br>
		<?php echo $adjustment_obj->turnover_difference; ?> &euro;<br><br>
		
		<strong><?php _e( "Montant de l'ajustement", 'yproject' ); ?></strong><br>
		<?php echo $adjustment_obj->amount; ?> &euro;<br><br>
		
		<strong><?php _e( "Documents justificatifs li&eacute;s", 'yproject' ); ?></strong><br>
		<?php $document_list = $adjustment_obj->get_documents(); ?>
		<?php foreach( $document_list as $document_item ): ?>
			<?php $document_metadata = json_decode( $document_item->metadata ); ?>
			- <a href="<?php echo $document_item->url; ?>" target="_blank"><?php echo $document_metadata->name; ?></a><br>
		<?php endforeach; ?>
		<br>
		
		<strong><?php _e( "Versements &agrave; marquer comme v&eacute;rifi&eacute;s", 'yproject' ); ?></strong><br>
		<?php $declaration_list = $adjustment_obj->get_declarations_checked(); ?>
		<?php foreach( $declaration_list as $declaration_item ): ?>
			<?php $declaration = new WDGROIDeclaration( $declaration_item->id, FALSE, $declaration_item ); ?>
			- <?php echo $declaration->get_formatted_date(); ?><br>
		<?php endforeach; ?>
		<br>
		
		<strong><?php _e( "Message pour l'entrepreneur", 'yproject' ); ?></strong><br>
		<?php echo $adjustment_obj->message_organization; ?><br><br>
		
		<strong><?php _e( "Message pour les investisseurs", 'yproject' ); ?></strong><br>
		<?php echo $adjustment_obj->message_investors; ?><br><br>
		
	</div>
</div>

<div id="adjustment-item-more-btn-<?php echo $adjustment_obj->id; ?>" class="adjustment-item-more-btn align-center">
	<button class="button transparent" data-adjustment="<?php echo $adjustment_obj->id; ?>">+</button>
</div>