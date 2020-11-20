<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$WDGUser_displayed = $page_controler->get_current_user();
$WDGUser_current = WDGUser::current();

$list_current_organizations = $page_controler->get_current_user_organizations();
$list_intentions_to_confirm = $page_controler->get_intentions_to_confirm();

$has_pending_wire_investments = $WDGUser_displayed->has_pending_wire_investments();
$pending_wire_investments = $WDGUser_displayed->get_pending_wire_investments();

?>

<h2><?php _e( "Investissements de", 'yproject' ); ?> <?php echo $page_controler->get_user_name(); ?></h2>

<p>
	<?php _e( "Les informations ci-dessous sont celles de votre compte personnel.", 'yproject' ); ?><br>
	<?php if ( count( $list_current_organizations ) > 0 ): ?>
		<?php _e( "Retrouvez celles de vos organisations en utilisant le menu.", 'yproject' ); ?>
	<?php endif; ?>
</p>

<?php if ( $WDGUser_current->is_admin() && $has_pending_wire_investments ): ?>		
	<div class="admin-theme">
		<strong><?php _e( "Virements en attente: ", 'yproject' ); ?></strong><br>
		<?php foreach ( $pending_wire_investments as $wire_investment ): ?>
			<br>
			<?php 
				$WDGInvestment = new WDGInvestment( $wire_investment->ID ); 	
				$post_campaign = atcf_get_campaign_post_by_payment_id($wire_investment->ID);
				$campaign = atcf_get_campaign($post_campaign);				
			?>
			<strong><?php _e( "Identifiants du virement :", 'yproject' ); ?></strong><br>
			<?php echo $campaign->get_name(); ?><br>
			<?php echo $WDGInvestment->get_saved_date(); ?><br>
			<?php $lw_wallet_amount = intval($WDGInvestment->get_saved_amount()) ?>
			<?php if ( $lw_wallet_amount == 0 ): ?>
				<form action="" method="POST" enctype="multipart/form-data" class="db-form align-left">
					<input type="hidden" name="action" value="change_wire_value">
					<input type="hidden" name="user_id" value="<?php echo $WDGUser_displayed->get_wpref(); ?>">
					<input type="hidden" name="investment_id" value="<?php echo $wire_investment->ID; ?>">
					<input type="hidden" name="payment_key" value="<?php echo $WDGInvestment->get_payment_key(); ?>">
					<input type="hidden" name="campaign_id" value="<?php echo $campaign->ID; ?>">
					<!-- TODO : essayer de récupérer dans les logs (?) le vrai montant du virement  -->
					<label for="amount_of_wire"><?php echo sprintf( __( "Montant du virement :", 'yproject' ) ); ?></label>
					<span class="field-value">
						<input type="text" name="amount_to_wire" id="amount_to_wire" value="<?php echo $lw_wallet_amount; ?>" class="format-number">
						<span class="field-money">&euro;</span>
					</span>
					<button type="submit" class="button blue"><?php _e( "Modifier le montant du virement", 'yproject' ); ?></button>
				</form>

			<?php else: ?>				
				<?php echo "Montant du virement : " . $lw_wallet_amount; ?><span class="field-money">&euro;</span>
			<?php endif; ?>

		<?php endforeach; ?>
	</div>
<?php endif; ?>


<div id="investment-synthesis-<?php echo $WDGUser_displayed->get_wpref(); ?>" class="investment-synthesis hidden">
	<span class="publish-count">0</span> <?php _e( "investissements valid&eacute;s", 'yproject' ); ?><span class="pending-str hidden">, <span class="pending-count">0</span> en attente</span>.
</div>

<div id="investment-synthesis-pictos-<?php echo $WDGUser_displayed->get_wpref(); ?>" class="investment-synthesis-pictos hidden">
	<div class="funded-projects">
		<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project-list/picto-balloon.png" alt="montgolfiere" width="80" height="80">
		<span class="data">0</span><br>
		<span class="txt"><?php _e( "projets financ&eacute;s", 'yproject' ); ?></span>
	</div>
	
	<div class="amount-invested">
		<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project-list/picto-arrows.png" alt="fleche" width="81" height="80">
		<span class="data">0 &euro;</span><br>
		<span class="txt"><?php _e( "investis", 'yproject' ); ?></span>
	</div>
	
	<div class="royalties-received">
		<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project-list/picto-money.png" alt="monnaie" width="97" height="80">
		<span class="data">0 &euro;</span><br>
		<span class="txt"><?php _e( "royalties re&ccedil;ues", 'yproject' ); ?></span>
		
	</div>
</div>

<div id="vote-intentions-<?php echo $WDGUser_displayed->get_wpref(); ?>" class="vote-intentions hidden">
	
	<?php if ( count( $list_intentions_to_confirm ) > 0 ): ?>
		<h3><?php _e( "Mes intentions d'investissement &agrave; concr&eacute;tiser", 'yproject' ); ?></h3>
	
		<?php foreach ( $list_intentions_to_confirm as $intention_item ): ?>
		
			<?php if ( $intention_item[ 'status' ] == ATCF_Campaign::$campaign_status_vote || $intention_item[ 'status' ] == ATCF_Campaign::$campaign_status_collecte ): ?>
				<?php $status_str = ( $intention_item[ 'status' ] == ATCF_Campaign::$campaign_status_vote ) ? "en &eacute;valuation" : "en investissement"; ?>
				<?php $button_str = ( $intention_item[ 'status' ] == ATCF_Campaign::$campaign_status_vote ) ? "Pr&eacute;-investir" : "Investir"; ?>
				<h4><?php echo YPUIHelpers::display_number( $intention_item[ 'vote_amount' ], TRUE, 0 ). ' &euro; sur ' .$intention_item[ 'campaign_name' ]. ' (' .$status_str. ')'; ?></h4>
				<a href="<?php echo home_url( '/investir/?campaign_id=' .$intention_item[ 'campaign_id' ]. '&invest_start=1&init_invest=' .$intention_item[ 'vote_amount' ] ); ?>" class="button red"><?php echo $button_str; ?></a>
			<?php endif; ?>

		<?php endforeach; ?>
	
	<?php endif; ?>
	
</div>

	
<div id="ajax-loader-<?php echo $WDGUser_displayed->get_wpref(); ?>" class="center" style="text-align: center;"><img id="ajax-loader-img-<?php echo $WDGUser_displayed->get_wpref(); ?>" src="<?php echo get_stylesheet_directory_uri() ?>/images/loading.gif" alt="chargement" /></div>

