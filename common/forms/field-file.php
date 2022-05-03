<?php global $wdg_current_field; ?>
<?php
	$WDGUser_current = WDGUser::current();
	$date_upload = '';
	$display_upload = TRUE;
	$message_instead_of_field = '';
	$display_refused_alert = FALSE;
	$keep_editing_for_admin = FALSE;
	if ( isset( $wdg_current_field[ 'options' ] ) ) {
		$date_upload = ( is_array( $wdg_current_field[ 'options' ] ) && isset( $wdg_current_field[ 'options' ][ 'date_upload' ] ) ) ? $wdg_current_field[ 'options' ][ 'date_upload' ] : $wdg_current_field[ 'options' ];
		$display_upload = ( is_array( $wdg_current_field[ 'options' ] ) && isset( $wdg_current_field[ 'options' ][ 'display_upload' ] ) ) ? $wdg_current_field[ 'options' ][ 'display_upload' ] : TRUE;
		$message_instead_of_field = ( is_array( $wdg_current_field[ 'options' ] ) && isset( $wdg_current_field[ 'options' ][ 'message_instead_of_field' ] ) ) ? $wdg_current_field[ 'options' ][ 'message_instead_of_field' ] : '';
		$display_refused_alert = ( is_array( $wdg_current_field[ 'options' ] ) && isset( $wdg_current_field[ 'options' ][ 'display_refused_alert' ] ) ) ? $wdg_current_field[ 'options' ][ 'display_refused_alert' ] : FALSE;
		$keep_editing_for_admin = ( is_array( $wdg_current_field[ 'options' ] ) && isset( $wdg_current_field[ 'options' ][ 'keep_editing_for_admin' ] ) ) ? $wdg_current_field[ 'options' ][ 'keep_editing_for_admin' ] : FALSE;
		$list_select = ( is_array( $wdg_current_field[ 'options' ] ) && isset( $wdg_current_field[ 'options' ][ 'list_select' ] ) ) ? $wdg_current_field[ 'options' ][ 'list_select' ] : FALSE;
		$api_type = ( is_array( $wdg_current_field[ 'options' ] ) && isset( $wdg_current_field[ 'options' ][ 'api_type' ] ) ) ? $wdg_current_field[ 'options' ][ 'api_type' ] : 'undefined';
		$kycfile_id = ( is_array( $wdg_current_field[ 'options' ] ) && isset( $wdg_current_field[ 'options' ][ 'kycfile_id' ] ) ) ? $wdg_current_field[ 'options' ][ 'kycfile_id' ] : FALSE;
		$is_api_file = ( is_array( $wdg_current_field[ 'options' ] ) && isset( $wdg_current_field[ 'options' ][ 'is_api_file' ] ) ) ? $wdg_current_field[ 'options' ][ 'is_api_file' ] : FALSE;
		$string_type = ( is_array( $wdg_current_field[ 'options' ] ) && isset( $wdg_current_field[ 'options' ][ 'string_type' ] ) ) ? $wdg_current_field[ 'options' ][ 'string_type' ] : FALSE;
	}
?>


<?php if ( !empty( $message_instead_of_field ) && ( !$keep_editing_for_admin || !$WDGUser_current->is_admin() ) ): ?>
	<div class="wdg-message confirm">
		<?php echo $message_instead_of_field; ?>
	</div>
<?php endif; ?>

<?php if ( $display_refused_alert ): ?>
	<div class="wdg-message error">
		<?php if ( $display_refused_alert === TRUE ): ?>
			<?php _e( 'forms.file.REFUSED_BY_PAYMENT_PROVIDER', 'yproject' ); ?>
		<?php else: ?>
			<?php echo $display_refused_alert; ?>
		<?php endif; ?>
	</div>
<?php endif; ?>
	
<?php if ( !empty( $wdg_current_field[ 'value' ] ) ): ?>
	<a id="preview-<?php echo $wdg_current_field[ 'name' ]; ?>" class="button blue-pale download-file" target="_blank" href="<?php echo $wdg_current_field[ 'value' ]; ?>">
		<?php if ( !empty( $date_upload ) && !is_array( $date_upload ) ): ?>
			<?php _e( 'forms.file.PREVIEW_SENT', 'yproject' ); ?> <?php echo $date_upload; ?>
		<?php else: ?>
			<?php _e( 'forms.file.FILE_PREVIEW', 'yproject' ); ?>
		<?php endif; ?>
		
		<?php if ( $api_type != 'undefined' && $string_type != FALSE ): ?>
			( <?php echo $string_type; ?> )
		<?php endif; ?>

	</a>
	<input type="hidden" name="hidden-preview-<?php echo $wdg_current_field[ 'name' ]; ?>" id="hidden-preview-<?php echo $wdg_current_field[ 'name' ]; ?>"  value="<?php echo $wdg_current_field[ 'value' ]; ?>" />
	<input type="hidden" name="hidden-id-<?php echo $wdg_current_field[ 'name' ]; ?>" id="hidden-id-<?php echo $wdg_current_field[ 'name' ]; ?>"  value="<?php echo $kycfile_id; ?>" />
	<input type="hidden" name="hidden-api-file-<?php echo $wdg_current_field[ 'name' ]; ?>" id="hidden-api-file-<?php echo $wdg_current_field[ 'name' ]; ?>"  value="<?php echo $is_api_file; ?>" />
	<br>
<?php endif; ?>
<?php if ( empty( $wdg_current_field[ 'value' ]) && !empty( $list_select) ): ?>
	<select id="select-<?php echo $wdg_current_field[ 'name' ]; ?>" name="select-<?php echo $wdg_current_field[ 'name' ]; ?>">
		<?php foreach ( $list_select as $option_value => $option_label ): ?>
			<option value="<?php echo $option_value; ?>"><?php echo $option_label; ?></option>
		<?php endforeach; ?>
	</select>
	<br><br>
<?php endif; ?>

<?php if ( $display_upload ): ?>
	<div class="field-description" >
		<?php 
			if ( ( $display_refused_alert || !empty( $message_instead_of_field ) &&  empty( $wdg_current_field[ 'value' ])) ) {
				_e( 'forms.file.REPLACE_SENT_FILE', 'yproject' ); 
				echo '<br>';
			}
		?>
		<?php if ( !empty( $wdg_current_field[ 'value' ]) && !empty( $list_select) ): ?>
			<?php _e( 'forms.file.REPLACE_SENT_FILE', 'yproject' ); ?><br>
			<select id="select-<?php echo $wdg_current_field[ 'name' ]; ?>" name="select-<?php echo $wdg_current_field[ 'name' ]; ?>">
				<option value="undefined" <?php selected( "undefined", $api_type ); ?>><?php _e( 'lemonway.document.type.UNDEFINED', 'yproject' ); ?></option>
				<?php foreach ( $list_select as $option_value => $option_label ): ?>
					<option value="<?php echo $option_value; ?>" <?php selected( $option_value, $api_type ); ?>><?php echo $option_label; ?></option>
				<?php endforeach; ?>
			</select>
		<?php endif; ?>
	</div>

	<div id="add-file" class="field-description">
		<?php _e( 'forms.file.FILE_SIZE_INFO', 'yproject' ); ?><br>
		<?php _e( 'forms.file.FILE_TYPE_INFO', 'yproject' ); ?><br>
		<input type="file" name="<?php echo $wdg_current_field[ 'name' ]; ?>" id="<?php echo $wdg_current_field[ 'name' ]; ?>">
		<label for="<?php echo $wdg_current_field[ 'name' ]; ?>" class="file-label hidden-responsive" data-input="<?php echo $wdg_current_field[ 'name' ]; ?>">
			<span class="hide-when-filled">
				<?php _e( 'forms.file.DRAG_FILE_HERE', 'yproject' ); ?><br>
				<?php _e( 'common.OR', 'yproject' ); ?><br>
			</span>
			<span class="button blue">
				<?php 
					if ( $display_refused_alert || !empty( $message_instead_of_field ) ) {
						_e( 'forms.file.REPLACE_FILE', 'yproject' ); 
					}else{
						_e( 'forms.file.IMPORT_FILE', 'yproject' ); 
					}
				?>
			</span>
		</label>
		<div class="hidden displayed-responsive">
			<button type="button" class="button blue wdg-button-lightbox-open" data-lightbox="lightbox-<?php echo $wdg_current_field[ 'name' ]; ?>"><?php _e( 'forms.file.TAKE_PICTURE', 'yproject' ); ?></button>
			<br>
			<?php _e( 'common.OR', 'yproject' ); ?><br>
			<button type="button" class="button transparent wdg-button-lightbox-open" data-lightbox="lightbox-<?php echo $wdg_current_field[ 'name' ]; ?>"><?php _e( 'forms.file.IMPORT_A_FILE', 'yproject' ); ?></button>
		</div>
	</div>
	<br><br>
<?php endif; ?>

<?php ob_start(); ?>
<div id="lightbox-<?php echo $wdg_current_field[ 'name' ]; ?>" class="align-left">
	<strong><?php echo $wdg_current_field[ 'label' ]; ?></strong><br><br>
	<?php _e( 'forms.file.FILE_SIZE_INFO', 'yproject' ); ?><br>
	<?php _e( 'forms.file.FILE_TYPE_INFO', 'yproject' ); ?><br><br>
	<?php echo $wdg_current_field[ 'description' ]; ?><br><br>
	
	<div class="align-center">
		<button type="button" class="button blue take-picture" data-input-id="<?php echo $wdg_current_field[ 'name' ]; ?>"><?php _e( 'forms.file.TAKE_PICTURE', 'yproject' ); ?></button>
		<br>
	<?php _e( 'common.OR', 'yproject' ); ?><br>
		<button type="button" class="button transparent import-file" data-input-id="<?php echo $wdg_current_field[ 'name' ]; ?>"><?php _e( 'forms.file.I_IMPORT_A_FILE', 'yproject' ); ?></button>
	</div>
</div>

<?php
	$lightbox_content = ob_get_contents();
	ob_end_clean();
	echo do_shortcode('[yproject_lightbox_cornered id="lightbox-'. $wdg_current_field[ 'name' ]. '" title="'.__( 'forms.file.IMPORT_A_FILE', 'yproject' ).'"]' . $lightbox_content . '[/yproject_lightbox_cornered]');
?>


