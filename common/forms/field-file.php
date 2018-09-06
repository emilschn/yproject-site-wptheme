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
<div class="wdg-message">
	<?php echo $message_instead_of_field; ?>
</div>

<?php else: ?>
<?php if ( $display_refused_alert ): ?>
<div class="field-alert">
	<?php _e( "Le fichier a &eacute;t&eacute; refus&eacute; par notre prestataire de paiement.", 'yproject' ); ?>
</div>
<?php endif; ?>
<div class="field-description">
	<?php _e( "Le fichier doit avoir une taille inf&eacute;rieure à 10 Mo.", 'yproject' ); ?><br>
	<?php _e( "Les formats de documents autoris&eacute;s sont : PDF, JPG, JPEG, BMP, GIF, TIF, TIFF et PNG.", 'yproject' ); ?>
</div>
<input type="file" name="<?php echo $wdg_current_field[ 'name' ]; ?>" id="<?php echo $wdg_current_field[ 'name' ]; ?>">
<label for="<?php echo $wdg_current_field[ 'name' ]; ?>" class="file-label" data-input="<?php echo $wdg_current_field[ 'name' ]; ?>">
	<span class="hide-when-filled">
		<?php _e( "Je glisse mon fichier ici" ); ?><br>
		ou<br>
	</span>
	<span class="button blue"><?php _e( "J'importe mon fichier" ); ?></span>
</label>
<?php endif; ?>

<?php if ( !empty( $wdg_current_field[ 'value' ] ) ): ?>
	<br><br>
	<a id="<?php echo $wdg_current_field[ 'name' ]; ?>" class="button blue-pale download-file" target="_blank" href="<?php echo $wdg_current_field[ 'value' ]; ?>"><?php _e( "Aper&ccedil;u du fichier envoy&eacute; le", 'yproject' ); ?> <?php echo $date_upload; ?></a>
	<br>
<?php endif;