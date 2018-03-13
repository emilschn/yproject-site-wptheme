<?php global $wdg_current_field; $i = 0; ?>
<?php foreach ( $wdg_current_field[ 'options' ] as $option_value => $option_label ): ?>
<label for="<?php echo $wdg_current_field[ 'name' ] .'-'. $option_value; ?>" class="radio-label">
	<input type="checkbox" id="<?php echo $wdg_current_field[ 'name' ] .'-'. $option_value; ?>" name="<?php echo $option_value; ?>" value="<?php echo $option_value; ?>" <?php checked( $wdg_current_field[ 'values' ][ $i ] ); ?>><span></span>
	<?php echo $option_label; ?>
</label>
<?php $i++; endforeach;