<?php global $wdg_current_field; ?>
<input type="text" name="<?php echo $wdg_current_field[ 'name' ]; ?>" id="<?php echo $wdg_current_field[ 'name' ]; ?>" value="<?php echo $wdg_current_field[ 'value' ]; ?>" />
<span class="field-percent"><?php if ( isset( $wdg_current_field[ 'unit' ] ) ) { echo $wdg_current_field[ 'unit' ]; } else { echo '%'; } ?></span>