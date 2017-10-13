<?php
/**
 * Template Name: Créer une organisation
 *
 */
?>

<?php
WDGOrganization::submit_new();
get_header();
?>

<div id="content">
    
	<div class="padder">
	    
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	    
			<?php locate_template( array("common/basic-header.php"), true ); ?>
	    
			<div class="center margin-height">
	    
				<?php if (is_user_logged_in()): ?>
			    
					<?php the_content(); ?>

					<?php global $errors_submit_new, $errors_create_orga; ?>
					<?php if (count($errors_submit_new->errors) > 0 || count($errors_create_orga) > 0): ?>
					<ul class="errors">
						<?php $error_messages = $errors_submit_new->get_error_messages(); ?>
						<?php foreach ($error_messages as $error_message): ?>
							<li><?php echo $error_message; ?></li>
						<?php endforeach; ?>
						<?php foreach ($errors_create_orga as $error_create_orga): ?>
							<li><?php echo $error_create_orga; ?></li>
						<?php endforeach; ?>
					</ul>
					<?php endif; ?>

					<form action="" method="POST" enctype="multipart/form-data" class="wdg-forms">

						<label for="org_name"><?php _e('D&eacute;nomination sociale', 'yproject'); ?> *</label>
						<input type="text" name="org_name" value="<?php echo filter_input(INPUT_POST, 'org_name'); ?>" /><br />

						<label for="org_email"><?php _e('e-mail de contact', 'yproject'); ?> *</label>
						<input type="text" name="org_email" value="<?php echo filter_input(INPUT_POST, 'org_email'); ?>" /><br />

						<label for="org_representative_function"><?php _e("Fonction du repr&eacute;sentant", 'yproject'); ?>*</label>
						<input type="text" name="org_representative_function" value="<?php echo filter_input(INPUT_POST, 'org_representative_function'); ?>" /><br />

						<label for="org_description"><?php _e("Descriptif de l'activit&eacute;", 'yproject'); ?> *</label>
						<input type="text" name="org_description" value="<?php echo filter_input(INPUT_POST, 'org_description'); ?>" /><br />

						<label for="org_legalform"><?php _e('Forme juridique', 'yproject'); ?> *</label>
						<input type="text" name="org_legalform" value="<?php echo filter_input(INPUT_POST, 'org_legalform'); ?>" /><br />

						<label for="org_idnumber"><?php _e('Num&eacute;ro SIREN', 'yproject'); ?> *</label>
						<input type="text" name="org_idnumber" value="<?php echo filter_input(INPUT_POST, 'org_idnumber'); ?>" /><br />

						<label for="org_rcs"><?php _e('RCS', 'yproject'); ?> *</label>
						<input type="text" name="org_rcs" value="<?php echo filter_input(INPUT_POST, 'org_rcs'); ?>" /><br />

						<label for="org_capital"><?php _e('Capital social (en euros)', 'yproject'); ?> *</label>
						<input type="text" name="org_capital" value="<?php echo filter_input(INPUT_POST, 'org_capital'); ?>" /><br />

						<label for="org_ape"><?php _e('Code APE', 'yproject'); ?> *</label>
						<input type="text" name="org_ape" value="<?php echo filter_input(INPUT_POST, 'org_ape'); ?>" /><br />

						<label for="org_vat"><?php _e('Num&eacute;ro de TVA', 'yproject'); ?> *</label>
						<input type="text" name="org_vat" value="<?php echo filter_input(INPUT_POST, 'org_vat'); ?>" /><br />

						<label for="org_fiscal_year_end_month"><?php _e("L'exerice comptable se termine au cours du mois", 'yproject'); ?> *</label><br />
						<?php
						$months = array( 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' );
						$count_months = count( $months );
						?>
						<select name="org_fiscal_year_end_month">
							<?php for ( $i = 0; $i < $count_months; $i++ ): ?>
							<option value="<?php echo ( $i + 1 ); ?>" <?php selected( filter_input(INPUT_POST, 'orga_fiscal_year_end_month'), $i + 1 ); ?>><?php _e( $months[ $i ] ); ?></option>
							<?php endfor; ?>
						</select>
						<br /><br />

						<h2><?php _e('Si&egrave;ge social', 'yproject'); ?></h2>
						<label for="org_address"><?php _e('Adresse', 'yproject'); ?> *</label>
						<input type="text" name="org_address" value="<?php echo filter_input(INPUT_POST, 'org_address'); ?>" /><br />

						<label for="org_postal_code"><?php _e('Code postal', 'yproject'); ?> *</label>
						<input type="text" name="org_postal_code" value="<?php echo filter_input(INPUT_POST, 'org_postal_code'); ?>" /><br />

						<label for="org_city"><?php _e('Ville', 'yproject'); ?> *</label>
						<input type="text" name="org_city" value="<?php echo filter_input(INPUT_POST, 'org_city'); ?>" /><br />

						<label for="org_nationality"><?php _e('Pays', 'yproject'); ?> *</label>
						<select name="org_nationality" id="org_nationality">
							<?php
							global $country_list;
							$selected_country = filter_input(INPUT_POST, 'org_nationality');
							foreach ($country_list as $country_code => $country_name): ?>
								<option value="<?php echo $country_code; ?>" <?php if ($country_code == $selected_country) { echo 'selected="selected"'; } ?>><?php echo $country_name; ?></option>
							<?php endforeach; ?>
						</select><br />
						
						
						<h2><?php _e('Informations bancaires - si vous souhaitez faire un virement d&apos;une somme obtenue', 'yproject'); ?></h2>
						<label for="org_bankownername"><?php _e('Nom du propri&eacute;taire du compte', 'yproject'); ?></label>
						<input type="text" name="org_bankownername" value="<?php echo filter_input(INPUT_POST, 'org_bankownername'); ?>" /> <br />

						<label for="org_bankowneraddress"><?php _e('Adresse du compte', 'yproject'); ?></label>
						<input type="text" name="org_bankowneraddress" value="<?php echo filter_input(INPUT_POST, 'org_bankowneraddress'); ?>" /> <br />

						<label for="org_bankowneriban"><?php _e('IBAN', 'yproject'); ?></label>
						<input type="text" name="org_bankowneriban" value="<?php echo filter_input(INPUT_POST, 'org_bankowneriban'); ?>" /> <br />

						<label for="org_bankownerbic"><?php _e('BIC', 'yproject'); ?></label>
						<input type="text" name="org_bankownerbic" value="<?php echo filter_input(INPUT_POST, 'org_bankownerbic'); ?>" /> <br />

						    
						<input type="checkbox" name="org_capable" /><?php _e('Je d&eacute;clare &ecirc;tre en capacit&eacute; de repr&eacute;senter cette organisation.', 'yproject'); ?><br />

						<input type="hidden" name="action" value="submit-new-organization" />

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