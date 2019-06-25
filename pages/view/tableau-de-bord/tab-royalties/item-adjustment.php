<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
global $adjustment_obj, $declaration_obj;
?>

<div class="adjustment-item">
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