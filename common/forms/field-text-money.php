<?php
global $wdg_current_field;
if ( empty( $wdg_current_field[ 'complementary_class' ] ) ) {
	$wdg_current_field[ 'complementary_class' ] = '';
}
?>
<input type="text" name="<?php echo $wdg_current_field[ 'name' ]; ?>" id="<?php echo $wdg_current_field[ 'name' ]; ?>" value="<?php echo $wdg_current_field[ 'value' ]; ?>" class="format-number <?php echo $wdg_current_field[ 'complementary_class' ]; ?>">
<span class="field-money">&euro;</span>