<?php global $wdg_current_field; ?>
<?php $current_rate = !empty( $wdg_current_field[ 'value' ] ) ? $wdg_current_field[ 'value' ] : 3; ?>
<?php $rate = 0; ?>
<?php foreach ( $wdg_current_field[ 'options' ] as $option_label ): $rate++; ?>
	<?php $str_checked = ( $rate <= $current_rate ) ? 'checked' : ''; ?>
<input type="checkbox" id="<?php echo $wdg_current_field[ 'name' ] .'-'. $rate; ?>" name="<?php echo $wdg_current_field[ 'name' ] .'-'. $rate; ?>" value="<?php echo $rate; ?>" class="rate <?php echo $wdg_current_field[ 'name' ]; ?>" data-description="<?php echo $option_label; ?>" <?php echo $str_checked; ?> /><span data-rate="<?php echo $wdg_current_field[ 'name' ]; ?>" data-value="<?php echo $rate; ?>"></span>
<?php endforeach; ?><br />
<span id="<?php echo $wdg_current_field[ 'name' ]; ?>-description" class="rate-description <?php echo $wdg_current_field[ 'name' ]; ?>"><?php echo $wdg_current_field[ 'options' ][ $current_rate - 1 ]; ?></span>