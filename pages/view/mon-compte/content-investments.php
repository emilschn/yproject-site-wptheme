<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$WDGUser_displayed = $page_controler->get_current_user();
$list_current_organizations = $page_controler->get_current_user_organizations();
$list_intentions_to_confirm = $page_controler->get_intentions_to_confirm();
?>

<h2><?php _e( 'account.investments.INVESTMENTS_OF', 'yproject' ); ?> <?php echo $page_controler->get_user_name(); ?></h2>

<p>
	<?php _e( 'account.common.INFORMATION_BELOW_PERSONAL_ACCOUNT', 'yproject' ); ?><br>
	<?php if ( count( $list_current_organizations ) > 0 ): ?>
		<?php _e( 'account.common.IF_INVESTMENT_ORGA', 'yproject' ); ?>
	<?php endif; ?>
</p>


<div id="investment-synthesis-<?php echo $WDGUser_displayed->get_wpref(); ?>" class="investment-synthesis hidden">
	<span class="publish-count">0</span> <?php _e( 'account.investments.INVESTMENTS_VALIDATED', 'yproject' ); ?><span class="pending-str hidden">, <span class="pending-count">0</span> <?php _e( 'account.investments.INVESTMENTS_PENDING', 'yproject' ); ?></span>.
</div>

<div id="investment-synthesis-pictos-<?php echo $WDGUser_displayed->get_wpref(); ?>" class="investment-synthesis-pictos hidden">
	<div class="funded-projects">
		<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project-list/picto-balloon.png" alt="montgolfiere" width="80" height="80">
		<span class="data">0</span><br>
		<span class="txt"><?php _e( 'account.investments.PROJECTS_FUNDED', 'yproject' ); ?></span>
	</div>
	
	<div class="amount-invested">
		<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project-list/picto-arrows.png" alt="fleche" width="81" height="80">
		<span class="data">0 &euro;</span><br>
		<span class="txt"><?php _e( 'account.investments.INVESTED', 'yproject' ); ?></span>
	</div>
	
	<div class="royalties-received">
		<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project-list/picto-money.png" alt="monnaie" width="97" height="80">
		<span class="data">0 &euro;</span><br>
		<span class="txt"><?php _e( 'account.investments.ROYALTIES_RECEIVED', 'yproject' ); ?></span>
		
	</div>
</div>

<div id="vote-intentions-<?php echo $WDGUser_displayed->get_wpref(); ?>" class="vote-intentions hidden">
	
	<?php if ( count( $list_intentions_to_confirm ) > 0 ): ?>
		<h3><?php _e( 'account.investments.INVESTMENTS_TO_CONCLUDE', 'yproject' ); ?></h3>
	
		<?php foreach ( $list_intentions_to_confirm as $intention_item ): ?>
		
			<?php if ( $intention_item[ 'status' ] == ATCF_Campaign::$campaign_status_vote || $intention_item[ 'status' ] == ATCF_Campaign::$campaign_status_collecte ): ?>
				<?php $status_str = ( $intention_item[ 'status' ] == ATCF_Campaign::$campaign_status_vote ) ? __( 'account.investments.STATUS_VOTE', 'yproject' ) : __( 'account.investments.STATUS_INVESTMENT', 'yproject' ); ?>
				<?php $button_str = ( $intention_item[ 'status' ] == ATCF_Campaign::$campaign_status_vote ) ? __( 'common.PREINVEST', 'yproject' ) : __( 'common.INVEST', 'yproject' ); ?>
				<h4><?php echo YPUIHelpers::display_number( $intention_item[ 'vote_amount' ] ). ' &euro; - ' .$intention_item[ 'campaign_name' ]. ' (' .$status_str. ')'; ?></h4>
				<a href="<?php echo home_url( '/investir/?campaign_id=' .$intention_item[ 'campaign_id' ]. '&invest_start=1&init_invest=' .$intention_item[ 'vote_amount' ] ); ?>" class="button red"><?php echo $button_str; ?></a>
			<?php endif; ?>

		<?php endforeach; ?>
	
	<?php endif; ?>
	
</div>

	
<div id="ajax-loader-<?php echo $WDGUser_displayed->get_wpref(); ?>" class="center" style="text-align: center;"><img id="ajax-loader-img-<?php echo $WDGUser_displayed->get_wpref(); ?>" src="<?php echo get_stylesheet_directory_uri() ?>/images/loading.gif" alt="chargement" /></div>

