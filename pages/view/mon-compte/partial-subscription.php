<?php
global $subscription_item;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

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
	<br><br>

	<div class="button-options">
		<a
			href="<?php echo $page_controler->get_end_subscription_link() . $subscription_item->id; ?>"
			class="button transparent alert-confirm"
			data-alertconfirm="<?php _e( 'account.subscriptions.item.ARE_YOU_SURE_TO_DELETE', 'yproject' ); ?>">
			<?php _e( 'account.subscriptions.item.END_THE_SUBSCRIPTION', 'yproject' ); ?></a>
	</div>
</div>