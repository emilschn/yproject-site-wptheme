<?php global $wdg_current_field; ?>
<?php $current_rate = 3; ?>
<?php $rate = 0; ?>
<?php foreach ( $wdg_current_field[ 'options' ] as $option_label ): $rate++; ?>
	<?php $str_checked = ( $rate <= $current_rate ) ? 'checked' : ''; ?>
	<input type="checkbox" id="<?php echo $wdg_current_field[ 'name' ] .'-'. $rate; ?>" name="<?php echo $wdg_current_field[ 'name' ] .'-'. $rate; ?>" value="<?php echo $rate; ?>" class="rate <?php echo $wdg_current_field[ 'name' ]; ?>" data-rate="<?php echo $wdg_current_field[ 'name' ]; ?>" <?php echo $str_checked; ?> />
<?php endforeach; ?>