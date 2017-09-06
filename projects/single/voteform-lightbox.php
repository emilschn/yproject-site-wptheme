<?php global $stylesheet_directory_uri, $post; ?>

<?php
$WDGVoteForm = new WDG_Form_Vote( $post->ID );
$fields_hidden = $WDGVoteForm->getFields( WDG_Form_Vote::$field_group_hidden );
$fields_impact = $WDGVoteForm->getFields( WDG_Form_Vote::$field_group_impacts );
$fields_validate = $WDGVoteForm->getFields( WDG_Form_Vote::$field_group_validate );
$fields_risk = $WDGVoteForm->getFields( WDG_Form_Vote::$field_group_risk );
$fields_info = $WDGVoteForm->getFields( WDG_Form_Vote::$field_group_info );
$field_invest = $WDGVoteForm->getFields( WDG_Form_Vote::$field_group_invest );
$field_advice = $WDGVoteForm->getFields( WDG_Form_Vote::$field_group_advice );
?>

<?php ob_start(); ?>
<div id="vote-form" class="wdg-lightbox-ref">
	
	<form method="post" class="sidebar-login-form db-form v3 full form-register ajax-form">
		
		<?php foreach ( $fields_hidden as $field ): ?>
			<?php global $wdg_current_field; $wdg_current_field = $field; ?>
			<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
		<?php endforeach; ?>
		
		<span class="form-error-general"></span>
		
		<div id="vote-form-slide1" class="vote-form-slide align-left">
			
			<h3><?php _e( "Impact et coh&eacute;rence du projet", 'yproject' ); ?></h3>
			
			
			<h4><?php _e( "Comment &eacute;valuez-vous les impacts soci&eacute;taux de ce projet ?", 'yproject' ); ?></h4>
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
			
			<?php foreach ( $field_invest as $field ): ?>
				<?php global $wdg_current_field; $wdg_current_field = $field; ?>
				<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
			<?php endforeach; ?>
			
			<h4><?php _e( "Conseils", 'yproject' ); ?></h4>
			<?php foreach ( $field_advice as $field ): ?>
				<?php global $wdg_current_field; $wdg_current_field = $field; ?>
				<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
			<?php endforeach; ?>
			
		</div>
		
		
		<div id="vote-form-buttons">
			
			<button class="button previous half left transparent hidden"><?php _e( "Pr&eacute;c&eacute;dent", 'yproject' ); ?></button>
			
			<button class="button next half right transparent"><?php _e( "Suivant", 'yproject' ); ?></button>
			
			<button class="button save half right transparent hidden"><?php _e( "Suivant", 'yproject' ); ?></button>
			
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
echo do_shortcode('[yproject_lightbox_cornered id="vote" title="'.__( "Vote sur ", 'yproject' ).$campaign_title.'"]' . $lightbox_content . '[/yproject_lightbox_cornered]');