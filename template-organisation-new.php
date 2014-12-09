<?php
/**
 * Template Name: Créer une organisation
 *
 */
?>

<?php 
locate_template( array("requests/organisations.php"), true );
YPOrganisationLib::submit_new();
get_header();
?>

<div id="content">
    
	<div class="padder">
	    
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	    
			<?php locate_template( array("basic/basic-header.php"), true ); ?>
	    
			<div class="center margin-height">
	    
				<?php if (is_user_logged_in()): ?>
			    
					<?php the_content(); ?>

					<?php global $errors_submit_new; ?>
					<?php if (count($errors_submit_new->errors) > 0): ?>
					<ul class="errors">
						<?php $error_messages = $errors_submit_new->get_error_messages(); ?>
						<?php foreach ($error_messages as $error_message): ?>
							<li><?php echo $error_message; ?></li>
						<?php endforeach; ?>
					</ul>
					<?php endif; ?>

					<form action="" method="POST" enctype="multipart/form-data" class="wdg-forms">

						<label for="org_name"><?php _e('D&eacute;nomination sociale', 'yproject'); ?></label>
						<input type="text" name="org_name" value="<?php echo filter_input(INPUT_POST, 'org_name'); ?>" /><br />

						<?php /*
						<label for="org_email"><?php _e('e-mail de contact', 'yproject'); ?></label>
						<input type="text" name="org_email" value="<?php echo filter_input(INPUT_POST, 'org_email'); ?>" /><br />
						 * 
						 */ ?>

						<label for="org_type"><?php _e('Type d&apos;organisation', 'yproject'); ?></label>
						<em>Pour l&apos;instant, seules les sociétés peuvent investir.</em><br />

						<label for="org_legalform"><?php _e('Forme juridique', 'yproject'); ?></label>
						<input type="text" name="org_legalform" value="<?php echo filter_input(INPUT_POST, 'org_legalform'); ?>" /><br />

						<label for="org_idnumber"><?php _e('Num&eacute;ro d&apos;immatriculation', 'yproject'); ?></label>
						<input type="text" name="org_idnumber" value="<?php echo filter_input(INPUT_POST, 'org_idnumber'); ?>" /><br />

						<label for="org_rcs"><?php _e('RCS', 'yproject'); ?></label>
						<input type="text" name="org_rcs" value="<?php echo filter_input(INPUT_POST, 'org_rcs'); ?>" /><br />

						<label for="org_capital"><?php _e('Capital social (en euros)', 'yproject'); ?></label>
						<input type="text" name="org_capital" value="<?php echo filter_input(INPUT_POST, 'org_capital'); ?>" /><br />

						<label for="org_address"><?php _e('Si&egrave;ge social', 'yproject'); ?></label>
						<input type="text" name="org_address" value="<?php echo filter_input(INPUT_POST, 'org_address'); ?>" /><br />

						<label for="org_postal_code"><?php _e('Code postal', 'yproject'); ?></label>
						<input type="text" name="org_postal_code" value="<?php echo filter_input(INPUT_POST, 'org_postal_code'); ?>" /><br />

						<label for="org_city"><?php _e('Ville', 'yproject'); ?></label>
						<input type="text" name="org_city" value="<?php echo filter_input(INPUT_POST, 'org_city'); ?>" /><br />

						<label for="org_nationality"><?php _e('Pays', 'yproject'); ?></label>
						<select name="org_nationality" id="org_nationality">
							<option value=""></option>
							<?php 
							require_once("country_list.php");
							global $country_list;
							$selected_country = filter_input(INPUT_POST, 'org_nationality');
							foreach ($country_list as $country_code => $country_name): ?>
								<option value="<?php echo $country_code; ?>" <?php if ($country_code == $selected_country) { echo 'selected="selected"'; } ?>><?php echo $country_name; ?></option>
							<?php endforeach; ?>
						</select><br />

						<input type="checkbox" name="org_capable" /><?php _e('Je d&eacute;clare &ecirc;tre en capacit&eacute; de repr&eacute;senter cette organisation.', 'yproject'); ?><br />

						<input type="hidden" name="action" value="submit-new-organisation" />

						<input type="submit" value="<?php _e('Enregistrer', 'yproject'); ?>" />

					</form>
					
					
				<?php else: ?>
					
					<?php $page_connexion = get_page_by_path('connexion'); ?>
					
					<a href="<?php echo get_permalink($page_connexion->ID); ?>"><?php _e('Connexion', 'yproject'); ?></a>
					
				<?php endif; ?>
					
			</div>
		
		<?php endwhile; endif; ?>
	    
	</div><!-- .padder -->
	
</div><!-- #content -->
	
<?php get_footer();