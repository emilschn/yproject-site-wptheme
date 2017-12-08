<?php global $wdg_current_field; ?>

<div id="field-<?php echo $wdg_current_field[ 'name' ]; ?>" class="field field-<?php echo $wdg_current_field[ 'type' ]; ?>">
	<?php if ( !empty( $wdg_current_field[ 'label' ] ) ): ?>
	<label for="<?php echo $wdg_current_field[ 'name' ]; ?>"><?php echo $wdg_current_field[ 'label' ]; ?></label>
	<?php endif; ?>
	<?php if ( !empty( $wdg_current_field[ 'description' ] ) ): ?>
	<div class="field-description"><?php echo $wdg_current_field[ 'description' ]; ?></div>
	<?php endif; ?>
	<span class="field-error"></span><br>
	<div class="field-container">
		<span class="field-value">
			<?php locate_template( array( "common/forms/field-".$wdg_current_field[ 'type' ].".php" ), true, false );  ?>
		</span>
	</div>
</div>