<?php global $wdg_current_field; ?>
<div class="field-description">
	<?php _e( "Le fichier doit avoir une taille inf&eacute;rieure à 10 Mo.", 'yproject' ); ?><br>
	<?php _e( "Les formats de documents autoris&eacute;s sont : PDF, JPG, JPEG, BMP, GIF, TIF, TIFF et PNG.", 'yproject' ); ?>
</div>
<input type="file" name="<?php echo $wdg_current_field[ 'name' ]; ?>" id="input-file-<?php echo $wdg_current_field[ 'name' ]; ?>">
<label for="<?php echo $wdg_current_field[ 'name' ]; ?>" class="file-label" data-input="input-file-<?php echo $wdg_current_field[ 'name' ]; ?>">
	<span class="hide-when-filled">
		<?php _e( "Je glisse mon fichier ici" ); ?><br>
		ou<br>
	</span>
	<span class="button blue"><?php _e( "J'importe mon fichier" ); ?></span>
</label>
<?php if ( !empty( $wdg_current_field[ 'value' ] ) ): ?>
	<br><br>
	<a id="<?php echo $wdg_current_field[ 'name' ]; ?>" class="button blue-pale download-file" target="_blank" href="<?php echo $wdg_current_field[ 'value' ]; ?>"><?php _e( "Aper&ccedil;u du fichier envoy&eacute; le", 'yproject' ); ?> <?php echo $wdg_current_field[ 'options' ]; ?></a>
	<br>
<?php endif;