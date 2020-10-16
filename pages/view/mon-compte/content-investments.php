<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$WDGUser_displayed = $page_controler->get_current_user();
$list_current_organizations = $page_controler->get_current_user_organizations();
$list_intentions_to_confirm = $page_controler->get_intentions_to_confirm();
?>

<h2><?php _e( "Investissements de", 'yproject' ); ?> <?php echo $page_controler->get_user_name(); ?></h2>

<p>
	<?php _e( "Les informations ci-dessous sont celles de votre compte personnel.", 'yproject' ); ?><br>
	<?php if ( count( $list_current_organizations ) > 0 ): ?>
	<?php _e( "Retrouvez celles de vos organisations en utilisant le menu.", 'yproject' ); ?>
	<?php endif; ?>
</p>


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

