<?php global $stylesheet_directory_uri, $post; ?>

<?php
// *****************************************************************************
// Formulaire de vote lui-même
// *****************************************************************************
$WDGVoteForm = new WDG_Form_Vote( $post->ID );
$fields_hidden = $WDGVoteForm->getFields( WDG_Form_Vote::$field_group_hidden );
$fields_impact = $WDGVoteForm->getFields( WDG_Form_Vote::$field_group_impacts );
$fields_validate = $WDGVoteForm->getFields( WDG_Form_Vote::$field_group_validate );
$fields_risk = $WDGVoteForm->getFields( WDG_Form_Vote::$field_group_risk );
$fields_info = $WDGVoteForm->getFields( WDG_Form_Vote::$field_group_info );
$field_invest = $WDGVoteForm->getFields( WDG_Form_Vote::$field_group_invest );
$field_advice = $WDGVoteForm->getFields( WDG_Form_Vote::$field_group_advice );
$core = ATCF_CrowdFunding::instance();
$core->include_form( 'invest-poll' );
$WDGCurrent_User = WDGUser::current();
$WDGPollForm = new WDG_Form_Invest_Poll( $post->ID, $WDGCurrent_User->wp_user->ID, 'vote' );
$fields_poll_source = $WDGPollForm->getFields( WDG_Form_Invest_Poll::$field_group_poll_source );
?>

<?php ob_start(); ?>
<div id="vote-form" class="wdg-lightbox-ref">
	
	<form method="post" class="sidebar-login-form db-form v3 full form-register ajax-form" novalidate>
		
		<?php foreach ( $fields_hidden as $field ): ?>
			<?php global $wdg_current_field; $wdg_current_field = $field; ?>
			<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
		<?php endforeach; ?>
		
		<span class="form-error-general"></span>
		
		<div id="vote-form-slide0" class="vote-form-slide align-justify">
			
			<br /><br /><br />
			<?php _e( "L'&eacute;valuation est l'&eacute;tape pr&eacute;alable à l'investissement et ne prend que 2 petites minutes !", 'yproject' ); ?>
			<br /><br />
			<?php _e( "Les entrepreneurs ont besoin de votre avis sur le projet pour mesurer l'int&eacute;r&ecirc;t qu'il suscite et bien lancer leur lev&eacute;e de fonds.", 'yproject' ); ?>
			<br /><br /><br /><br />
			
		</div>
		
		<div id="vote-form-slide1" class="vote-form-slide align-justify hidden">
			
			<div class="vote-progress-bar align-center">
				<span class="selected"><?php _e( "Etape", 'yproject' ); ?> 1</span>
				<span><?php _e( "Etape", 'yproject' ); ?> 2</span>
				<span><?php _e( "Etape", 'yproject' ); ?> 3</span>
				<span><?php _e( "Etape", 'yproject' ); ?> 4</span>
			</div>
			
			<?php _e( "WE DO GOOD reconnecte la finance avec le bien commun.", 'yproject' ); ?>
			<?php _e( "En finan&ccedil;ant l'&eacute;conomie r&eacute;elle, vous pouvez avoir un impact positif sur l'environnement et la soci&eacute;t&eacute;.", 'yproject' ); ?>
			<br /><br />
			
			
			<h4><?php _e( "Je pense que ce projet a un impact positif...", 'yproject' ); ?></h4>
			<?php foreach ( $fields_impact as $field ): ?>
				<?php global $wdg_current_field; $wdg_current_field = $field; ?>
				<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
			<?php endforeach; ?>
			
			
			<?php foreach ( $fields_validate as $field ): ?>
				<?php global $wdg_current_field; $wdg_current_field = $field; ?>
				<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
			<?php endforeach; ?>
			
		</div>
		
		<div id="vote-form-slide2" class="vote-form-slide align-left hidden">
			
			<div class="vote-progress-bar align-center">
				<span><?php _e( "Etape", 'yproject' ); ?> 1</span>
				<span class="selected"><?php _e( "Etape", 'yproject' ); ?> 2</span>
				<span><?php _e( "Etape", 'yproject' ); ?> 3</span>
				<span><?php _e( "Etape", 'yproject' ); ?> 4</span>
			</div>
			
			<?php foreach ( $fields_risk as $field ): ?>
				<?php global $wdg_current_field; $wdg_current_field = $field; ?>
				<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
			<?php endforeach; ?>
			
			<?php foreach ( $fields_info as $field ): ?>
				<?php global $wdg_current_field; $wdg_current_field = $field; ?>
				<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
			<?php endforeach; ?>
			
		</div>
		
		<div id="vote-form-slide3" class="vote-form-slide align-left hidden">
			
			<div class="vote-progress-bar align-center">
				<span><?php _e( "Etape", 'yproject' ); ?> 1</span>
				<span><?php _e( "Etape", 'yproject' ); ?> 2</span>
				<span class="selected"><?php _e( "Etape", 'yproject' ); ?> 3</span>
				<span><?php _e( "Etape", 'yproject' ); ?> 4</span>
			</div>
			
			<?php foreach ( $fields_poll_source as $field ): ?>
				<?php global $wdg_current_field; $wdg_current_field = $field; ?>
				<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
			<?php endforeach; ?>
			
		</div>
		
		<div id="vote-form-slide4" class="vote-form-slide align-left hidden">
			
			<div class="vote-progress-bar align-center">
				<span><?php _e( "Etape", 'yproject' ); ?> 1</span>
				<span><?php _e( "Etape", 'yproject' ); ?> 2</span>
				<span><?php _e( "Etape", 'yproject' ); ?> 3</span>
				<span class="selected"><?php _e( "Etape", 'yproject' ); ?> 4</span>
			</div>
			
			<?php foreach ( $field_invest as $field ): ?>
				<?php global $wdg_current_field; $wdg_current_field = $field; ?>
				<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
			<?php endforeach; ?>
			
			<?php foreach ( $field_advice as $field ): ?>
				<?php global $wdg_current_field; $wdg_current_field = $field; ?>
				<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
			<?php endforeach; ?>
			
		</div>
		
		
		<div id="vote-form-buttons">
			
			<button class="button previous half left transparent hidden"><?php _e( "Pr&eacute;c&eacute;dent", 'yproject' ); ?></button>
			
			<button class="button next half right transparent"><?php _e( "Suivant", 'yproject' ); ?></button>
			
			<button class="button save half right red hidden" data-close="vote" data-callback="WDGProjectVote.saveVoteCallback"><?php _e( "Valider", 'yproject' ); ?></button>
			
			<div class="clear"></div>
			
			<div class="loading align-center hidden">
				<img src="<?php echo $stylesheet_directory_uri; ?>/images/loading.gif" width="30" alt="loading" />
			</div>
			
		</div>
		
	</form>
	
</div>

<?php
$lightbox_content = ob_get_contents();
ob_end_clean();
$campaign_title = $post->post_title;
echo do_shortcode('[yproject_lightbox_cornered id="vote" title="'.__( "&Eacute;valuation de ", 'yproject' ).$campaign_title.'"]' . $lightbox_content . '[/yproject_lightbox_cornered]');
echo do_shortcode('[yproject_lightbox_cornered id="vote-simple-confirmation" msgtype="valid"]'.__( "Votre &eacute;valuation est enregistr&eacute;e !", 'yproject' ).'<br><br>[/yproject_lightbox_cornered]');
// *****************************************************************************
?>


<?php
// *****************************************************************************
// Formulaire de validation des détails utilisateur
// *****************************************************************************
$WDGUser_current = WDGUser::current();
$WDGUserDetailsForm = new WDG_Form_User_Details( $WDGUser_current->get_wpref(), WDG_Form_User_Details::$type_vote );
$fields_vote_hidden = $WDGUserDetailsForm->getFields( WDG_Form_User_Details::$field_group_hidden );
$fields_vote_basics = $WDGUserDetailsForm->getFields( WDG_Form_User_Details::$field_group_basics );
$fields_vote_vote = $WDGUserDetailsForm->getFields( WDG_Form_User_Details::$field_group_vote );
?>

<?php ob_start(); ?>
<div id="user-details-vote-form" class="wdg-lightbox-ref">
	
	<form method="post" class="sidebar-login-form db-form v3 full ajax-form">
		
		<?php foreach ( $fields_vote_hidden as $field ): ?>
			<?php global $wdg_current_field; $wdg_current_field = $field; ?>
			<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
		<?php endforeach; ?>
		
		<span class="form-error-general"></span>
		
		<p class="align-left">
		<i><?php _e( "Votre &eacute;valuation a bien &eacute;t&eacute; enregistr&eacute; !", 'yproject' ); ?></i>
		<br><br>
		<?php _e( "Vous avez indiqu&eacute; &ecirc;tre int&eacute;ress&eacute;(e) pour investir, confirmez vos informations afin que le porteur de projet puisse vous joindre lors du lancement de la lev&eacute;e de fonds.", 'yproject' ); ?>
		<br><br>
		</p>
		
		<?php foreach ( $fields_vote_basics as $field ): ?>
			<?php global $wdg_current_field; $wdg_current_field = $field; ?>
			<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
		<?php endforeach; ?>
		
		<?php foreach ( $fields_vote_vote as $field ): ?>
			<?php global $wdg_current_field; $wdg_current_field = $field; ?>
			<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
		<?php endforeach; ?>
		
		
		<div id="user-details-vote-form-buttons">
			<button class="button save red" data-close="user-details-vote" data-open="preinvest-warning"><?php _e( "Confirmer", 'yproject' ); ?></button>
			
			<div class="loading align-center hidden">
				<img src="<?php echo $stylesheet_directory_uri; ?>/images/loading.gif" width="30" alt="loading" />
			</div>
			
		</div>

		<br><br>
		
	</form>
	
</div>

<?php
$lightbox_content = ob_get_contents();
ob_end_clean();
echo do_shortcode('[yproject_lightbox_cornered id="user-details-vote" title="'.__( "&Eacute;valuation de ", 'yproject' ).$campaign_title.'"]' . $lightbox_content . '[/yproject_lightbox_cornered]');
// *****************************************************************************


// *****************************************************************************
// Lightbox d'avertissement de pré-investissement
// *****************************************************************************
$campaign = new ATCF_Campaign( $post->ID );
?>

<?php ob_start(); ?>
<div class="wdg-lightbox-ref">
	
	<form method="post" class="sidebar-login-form db-form v3 full ajax-form">
		
		<div class="align-left">
			<?php echo apply_filters( 'the_content', WDGConfigTexts::get_config_text_by_name( WDGConfigTexts::$type_investment_generalities_preinvestment, 'preinvest_warning' ) ); ?>
		</div>
		
		<div>
			
			<button type="button" class="button half left transparent" data-redirecturl="<?php echo $campaign->get_public_url() . '#vote-share'; ?>"><?php _e( "Plus tard", 'yproject' ); ?></button>

			<button type="button" class="button redirect half right red" data-redirecturl="<?php echo home_url( '/investir/' ) . '?campaign_id=' .$post->ID. '&invest_start=1'; ?>"><?php _e( "Continuer", 'yproject' ); ?></button>
			
		</div>
		
	</form>
	
</div>

<?php
$lightbox_content = ob_get_contents();
ob_end_clean();
echo do_shortcode('[yproject_lightbox_cornered id="preinvest-warning" title="'.__( "Avant de pr&eacute;-investir", 'yproject' ).'"]' . $lightbox_content . '[/yproject_lightbox_cornered]');
// *****************************************************************************