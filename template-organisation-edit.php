<?php
/**
 * Template Name: Editer une organisation
 *
 */
?>

<?php 
locate_template( array("requests/organisations.php"), true );
$organisation_obj = YPOrganisation::get_current();
YPOrganisationLib::edit($organisation_obj);
get_header();
?>

<div id="content">
    
	<div class="padder">
	    
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	    
			<?php $post->post_title = 'Organisation ' . $organisation_obj->get_name(); ?>
	    
			<?php locate_template( array("basic/basic-header.php"), true ); ?>
	    
			<div class="center margin-height">
	    
				<?php if ($organisation_obj !== FALSE): ?>
			    
					<?php if (is_user_logged_in()): ?>

						<?php the_content(); ?>

						<?php global $errors_edit; ?>
						<?php if (count($errors_edit->errors) > 0): ?>
						<ul class="errors">
							<?php $error_messages = $errors_edit->get_error_messages(); ?>
							<?php foreach ($error_messages as $error_message): ?>
								<li><?php echo $error_message; ?></li>
							<?php endforeach; ?>
						</ul>
						<?php elseif (filter_input(INPUT_POST, 'action') == 'edit-organisation'): ?>
						<p class="success">
							<?php _e('Modifications enregistr&eacute;es.'); ?>
						</p>
						<?php endif; ?>

						<form action="" method="POST" enctype="multipart/form-data" class="wdg-forms">

							<label for="org_name"><?php _e('D&eacute;nomination sociale', 'yproject'); ?></label>
							<em><?php echo $organisation_obj->get_name(); ?></em><br />

							<label for="org_type"><?php _e('Type d&apos;organisation', 'yproject'); ?></label>
							<em><?php if ($organisation_obj->get_type() == "society") { echo "Société"; } ?></em><br />

							<label for="org_legalform"><?php _e('Forme juridique', 'yproject'); ?></label>
							<input type="text" name="org_legalform" value="<?php echo $organisation_obj->get_legalform(); ?>" /><br />

							<label for="org_idnumber"><?php _e('Num&eacute;ro d&apos;immatriculation', 'yproject'); ?></label>
							<input type="text" name="org_idnumber" value="<?php echo $organisation_obj->get_idnumber(); ?>" /><br />

							<label for="org_rcs"><?php _e('RCS', 'yproject'); ?></label>
							<input type="text" name="org_rcs" value="<?php echo $organisation_obj->get_rcs(); ?>" /><br />

							<label for="org_capital"><?php _e('Capital social (en euros)', 'yproject'); ?></label>
							<input type="text" name="org_capital" value="<?php echo $organisation_obj->get_capital(); ?>" /><br />

							<label for="org_ape"><?php _e('Code APE', 'yproject'); ?></label>
							<input type="text" name="org_ape" value="<?php echo $organisation_obj->get_ape(); ?>" /><br />

							<label for="org_address"><?php _e('Si&egrave;ge social', 'yproject'); ?></label>
							<input type="text" name="org_address" value="<?php echo $organisation_obj->get_address(); ?>" /><br />

							<label for="org_postal_code"><?php _e('Code postal', 'yproject'); ?></label>
							<input type="text" name="org_postal_code" value="<?php echo $organisation_obj->get_postal_code(); ?>" /><br />

							<label for="org_city"><?php _e('Ville', 'yproject'); ?></label>
							<input type="text" name="org_city" value="<?php echo $organisation_obj->get_city(); ?>" /><br />

							<label for="org_nationality"><?php _e('Pays', 'yproject'); ?></label>
							<select name="org_nationality" id="org_nationality">
								<option value=""></option>
								<?php 
								require_once("country_list.php");
								global $country_list;
								$selected_country = $organisation_obj->get_nationality();
								foreach ($country_list as $country_code => $country_name): ?>
									<option value="<?php echo $country_code; ?>" <?php if ($country_code == $selected_country) { echo 'selected="selected"'; } ?>><?php echo $country_name; ?></option>
								<?php endforeach; ?>
							</select><br />

							<input type="hidden" name="action" value="edit-organisation" />

							<input type="submit" value="<?php _e('Enregistrer', 'yproject'); ?>" />

						</form>


					<?php else: ?>

						<?php $page_connexion = get_page_by_path('connexion'); ?>

						<a href="<?php echo get_permalink($page_connexion->ID); ?>"><?php _e('Connexion', 'yproject'); ?></a>

					<?php endif; ?>
					
				<?php else: ?>
						
					<?php _e('Cette page n&apos;est pas accessible.', 'yproject'); ?>
						
				<?php endif; ?>
						
			</div>
		
		<?php endwhile; endif; ?>
	    
	</div><!-- .padder -->
	
</div><!-- #content -->
	
<?php get_footer();