<?php global $wdg_current_field; ?>
<input type="text" pattern="\d*" name="<?php echo $wdg_current_field[ 'name' ]; ?>" id="<?php echo $wdg_current_field[ 'name' ]; ?>" value="<?php echo $wdg_current_field[ 'value' ]; ?>" class="format-number <?php echo $wdg_current_field[ 'complementary_class' ]; ?>">
<span class="field-money">&euro;</span>