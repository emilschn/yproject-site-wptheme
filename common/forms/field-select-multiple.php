<?php global $wdg_current_field; ?>
<div class="select-multiple-items-retracted" data-name="<?php echo $wdg_current_field[ 'name' ]; ?>">
	<span class="select-multiple-items-retracted-values">a
		<?php
		$i = 0;
		$init = FALSE;
		foreach ( $wdg_current_field[ 'options' ] as $option_value => $option_label ) {
			if ( isset( $wdg_current_field[ 'values' ][ $i ] ) ) {
				if ( $init ) {
					echo ', ';
				}
				echo $option_label;
				$init = TRUE;
			}
			$i++;
		}
		?>
	</span>
	<button type="button" class="select-multiple-items-retracted-button"></button>
</div>
<div id="select-multiple-items-<?php echo $wdg_current_field[ 'name' ]; ?>" class="select-multiple-items hidden">
	<?php $i = 0; foreach ( $wdg_current_field[ 'options' ] as $option_value => $option_label ): ?>
	<label for="<?php echo $wdg_current_field[ 'name' ] .'-'. $option_value; ?>" class="radio-label">
		<input type="checkbox" id="<?php echo $wdg_current_field[ 'name' ] .'-'. $option_value; ?>" name="<?php echo $option_value; ?>" value="<?php echo $option_value; ?>" <?php checked( isset( $wdg_current_field[ 'values' ][ $i ] ) ? $wdg_current_field[ 'values' ][ $i ] : $wdg_current_field[ 'value' ][ $i ] ); ?>><span></span>
		<?php echo $option_label; ?>
	</label>
	<?php $i++; endforeach; ?>
</div>