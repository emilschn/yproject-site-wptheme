<?php global $wdg_current_field; ?>
<?php $input_type = empty( $wdg_current_field[ 'options' ] ) ? 'text' : $wdg_current_field[ 'options' ]; ?>
<input type="<?php echo $input_type; ?>" name="<?php echo $wdg_current_field[ 'name' ]; ?>" id="<?php echo $wdg_current_field[ 'name' ]; ?>" value="<?php echo $wdg_current_field[ 'value' ]; ?>" />