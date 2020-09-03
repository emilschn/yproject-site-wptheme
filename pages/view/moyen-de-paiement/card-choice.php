<?php
global $stylesheet_directory_uri, $mean_of_payment;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>


<?php if ( $page_controler->has_registered_cards() ): ?>

	<?php $first_registered_card = $page_controler->get_first_registered_card(); ?>

	<p id="deploy-on-card-choice-<?php echo $mean_of_payment; ?>" class="registered-card-preview hidden expand-on-card-choice" data-default-card-type="<?php echo $first_registered_card[ 'id' ]; ?>">
		<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-account/check-checked.png" alt="check" width="80" height="80">
		<span>
			<?php _e( 'invest.mean-payment.list.CARD_SAVED', 'yproject' ); ?><br>
			<?php echo $first_registered_card[ 'number' ]; ?><br>
			<?php _e( "Exp", 'yproject' ); ?> <?php echo $first_registered_card[ 'expiration' ]; ?>
		</span>
		<br>
		<button class="button transparent edit-card" data-type="<?php echo $mean_of_payment; ?>"><?php _e( 'common.MODIFY', 'yproject' ); ?></button>
	</p>

	<?php $list_registered_cards = $page_controler->get_registered_cards_list(); ?>

	<div id="card-options-list-<?php echo $mean_of_payment; ?>" class="card-options-list hidden db-form v3 full bg-white">
		<?php foreach ( $list_registered_cards as $item_registered_card ): ?>
			<div class="field field-radio">
				<div class="field-container">
					<span class="field-value">
						<label for="card-option-<?php echo $mean_of_payment; ?>-<?php echo $item_registered_card[ 'id' ]; ?>" class="radio-label">
							<input type="radio" id="card-option-<?php echo $mean_of_payment; ?>-<?php echo $item_registered_card[ 'id' ]; ?>" name="card-option-<?php echo $mean_of_payment; ?>" value="<?php echo $item_registered_card[ 'id' ]; ?>"><span></span>
							<?php echo $item_registered_card[ 'label' ]; ?>
							<?php if ( !empty( $item_registered_card[ 'number' ] ) ): ?>
								<br><?php echo $item_registered_card[ 'number' ]; ?>
							<?php endif; ?>
							<?php if ( !empty( $item_registered_card[ 'expiration' ] ) ): ?>
							<br><?php _e( "Exp", 'yproject' ); ?> <?php echo $item_registered_card[ 'expiration' ]; ?>
							<?php endif; ?>
						</label>
					</span>
				</div>
			</div>
		<?php endforeach; ?>
	</div>

<?php endif; ?>

<div id="save-card-zone-<?php echo $mean_of_payment; ?>" class="save-card-zone hidden db-form v3 full bg-white">
	<div class="field field-checkboxes">
		<div class="field-container">
			<span class="field-value">
				<label for="save_card_<?php echo $mean_of_payment; ?>" class="radio-label">
					<input type="checkbox" id="save_card_<?php echo $mean_of_payment; ?>" name="save_card_<?php echo $mean_of_payment; ?>" value="1"><span></span>
					<?php _e( 'invest.mean-payment.SAVE_CARD', 'yproject' ); ?>
				</label>
			</span>
		</div>
	</div>
</div>