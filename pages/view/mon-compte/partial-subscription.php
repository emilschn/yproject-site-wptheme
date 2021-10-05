<?php global $subscription_item; ?>

<div class="subscription-item">
	<h3><?php echo $subscription_item->get_campaign_name(); ?></h3>

	<?php _e( 'account.subscriptions.item.I_INVEST', 'yproject' ); ?>
	<strong><?php echo $subscription_item->amount; ?>&nbsp;&euro;</strong>
	<?php echo $subscription_item->get_modality_str(); ?>
	<?php _e( 'account.subscriptions.item.IN_THE_THEMATIC', 'yproject' ); ?>
	<strong><?php echo $subscription_item->get_campaign_name(); ?></strong>
	<br><br>

	<i><?php _e( 'account.subscriptions.item.INVESTMENT_CONDITION', 'yproject' ); ?></i>
	<br><br>

	<?php _e( 'account.subscriptions.item.CONTRACT_RECEPTION', 'yproject' ); ?>
	<a href="<?php echo $subscription_item->get_model_contract_url(); ?>" target="_blank"><?php _e( 'account.subscriptions.item.ACCESS_CONTRACT_TYPE', 'yproject' ); ?></a>
	<br>

	<hr>

	<?php _e( 'account.subscriptions.item.NEXT_PAYMENT', 'yproject' ); ?>
	<strong><?php echo $subscription_item->get_next_payment_date_str(); ?></strong>
	<br>
</div>