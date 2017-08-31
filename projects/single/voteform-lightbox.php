<?php global $vote_errors, $stylesheet_directory_uri; ?>

<?php
$WDGVoteForm = new WDG_Form_Vote();
$fields_impact = $WDGVoteForm->getFields( WDG_Form_Vote::$field_group_impacts );
$fields_validate = $WDGVoteForm->getFields( WDG_Form_Vote::$field_group_validate );
$fields_risk = $WDGVoteForm->getFields( WDG_Form_Vote::$field_group_risk );
$fields_info = $WDGVoteForm->getFields( WDG_Form_Vote::$field_group_info );
?>

<?php ob_start(); ?>
<div id="vote-form" class="wdg-lightbox-ref">
	
	<form method="post" class="sidebar-login-form db-form v3 full form-register">
		
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
		
		
		<div id="vote-form-buttons">
			
			<button class="button half left transparent hidden"><?php _e( "Pr&eacute;c&eacute;dent", 'yproject' ); ?></button>
			
			<button class="button half right transparent"><?php _e( "Suivant", 'yproject' ); ?></button>
			
		</div>
		
	</form>
	
</div>

<?php
$lightbox_content = ob_get_contents();
ob_end_clean();
echo do_shortcode('[yproject_lightbox_cornered id="vote" title="'.__( "Voter", 'yproject' ).'"]' . $lightbox_content . '[/yproject_lightbox_cornered]');