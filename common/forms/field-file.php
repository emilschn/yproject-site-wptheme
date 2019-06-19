<?php global $wdg_current_field; ?>
<?php
$date_upload = '';
$message_instead_of_field = '';
$display_refused_alert = FALSE;
if ( isset( $wdg_current_field[ 'options' ] ) ) {
	$date_upload = ( is_array( $wdg_current_field[ 'options' ] ) ) ? $wdg_current_field[ 'options' ][ 'date_upload' ] : $wdg_current_field[ 'options' ];
	$message_instead_of_field = ( is_array( $wdg_current_field[ 'options' ] ) ) ? $wdg_current_field[ 'options' ][ 'message_instead_of_field' ] : '';
	$display_refused_alert = ( is_array( $wdg_current_field[ 'options' ] ) ) ? $wdg_current_field[ 'options' ][ 'display_refused_alert' ] : FALSE;
}
?>

<?php if ( !empty( $message_instead_of_field ) ): ?>
<div class="wdg-message confirm">
	<?php echo $message_instead_of_field; ?>
</div>


<?php else: ?>

<?php if ( $display_refused_alert ): ?>
<div class="wdg-message error">
	<?php _e( "Le fichier a &eacute;t&eacute; refus&eacute; par notre prestataire de paiement.", 'yproject' ); ?>
</div>
<?php endif; ?>

<div class="field-description">
	<?php _e( "Le fichier doit avoir une taille inf&eacute;rieure à 10 Mo.", 'yproject' ); ?><br>
	<?php _e( "Les formats de documents autoris&eacute;s sont : PDF, JPG, JPEG, BMP, GIF, TIF, TIFF et PNG.", 'yproject' ); ?>
</div>
<input type="file" name="<?php echo $wdg_current_field[ 'name' ]; ?>" id="<?php echo $wdg_current_field[ 'name' ]; ?>">
<label for="<?php echo $wdg_current_field[ 'name' ]; ?>" class="file-label hidden-responsive" data-input="<?php echo $wdg_current_field[ 'name' ]; ?>">
	<span class="hide-when-filled">
		<?php _e( "Glisser mon fichier ici" ); ?><br>
		ou<br>
	</span>
	<span class="button blue"><?php _e( "Importer mon fichier" ); ?></span>
</label>
<div class="hidden displayed-responsive">
	<button type="button" class="button blue wdg-button-lightbox-open" data-lightbox="lightbox-<?php echo $wdg_current_field[ 'name' ]; ?>"><?php _e( "Je prends une photo" ); ?></button>
	<br>
	ou<br>
	<button type="button" class="button transparent wdg-button-lightbox-open" data-lightbox="lightbox-<?php echo $wdg_current_field[ 'name' ]; ?>"><?php _e( "J'importe un fichier" ); ?></button>
</div>
<br><br>

<?php ob_start(); ?>
<div id="lightbox-<?php echo $wdg_current_field[ 'name' ]; ?>" class="align-left">
	<strong><?php echo $wdg_current_field[ 'label' ]; ?></strong><br><br>
	<?php _e( "Le fichier doit avoir une taille inf&eacute;rieure à 10 Mo.", 'yproject' ); ?><br>
	<?php _e( "Les formats de documents autoris&eacute;s sont : PDF, JPG, JPEG, BMP, GIF, TIF, TIFF et PNG.", 'yproject' ); ?><br><br>
	<?php echo $wdg_current_field[ 'description' ]; ?><br><br>
	
	<div class="align-center">
		<button type="button" class="button blue take-picture" data-input-id="<?php echo $wdg_current_field[ 'name' ]; ?>"><?php _e( "Je prends une photo" ); ?></button>
		<br>
		ou<br>
		<button type="button" class="button transparent import-file" data-input-id="<?php echo $wdg_current_field[ 'name' ]; ?>"><?php _e( "J'importe un fichier" ); ?></button>
	</div>
</div>

<?php
$lightbox_content = ob_get_contents();
ob_end_clean();
echo do_shortcode('[yproject_lightbox_cornered id="lightbox-'. $wdg_current_field[ 'name' ]. '" title="'.__( "Importer un fichier", 'yproject' ).'"]' . $lightbox_content . '[/yproject_lightbox_cornered]');
?>
<?php endif; ?>

<?php if ( !empty( $wdg_current_field[ 'value' ] ) ): ?>
	<a id="<?php echo $wdg_current_field[ 'name' ]; ?>" class="button blue-pale download-file" target="_blank" href="<?php echo $wdg_current_field[ 'value' ]; ?>"><?php _e( "Aper&ccedil;u du fichier envoy&eacute; le", 'yproject' ); ?> <?php echo $date_upload; ?></a>
	<br>
<?php endif;