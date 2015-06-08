<?php ypbp_core_screen_signup(); ?>        
<?php do_action( 'bp_before_register_page' ); ?>
       
		    <form action="" name="signup_form" id="signup_form" class="standard-form" method="post" enctype="multipart/form-data">
		    <?php if ( 'registration-disabled' == ypbp_get_current_signup_step() ) : ?>
			    <?php do_action( 'template_notices' ); ?>
			    <?php do_action( 'bp_before_registration_disabled' ); ?>
                            
					<p><?php _e( 'User registration is currently not allowed.', 'buddypress' ); ?></p>

			    <?php do_action( 'bp_after_registration_disabled' ); ?>
		    <?php endif; // registration-disabled signup setp ?>

		    <?php if ( 'request-details' == ypbp_get_current_signup_step() ) : ?>
                     <div id="warning">
                        La création d'un compte de Membre sur WEDOGOOD.co est exclusivement réservé aux personnes physique. Chaque Membre ne peut bénéficier que d'un seul compte à son nom.
                        Si vous souhaitez investir ou porter un projet pour une organisation, vous pourrez l'indiquer au moment de l'investissement ou dans les paramètres du projet.
                        En m'inscrivant, je recevrai automatiquement la newsletter de WE DO GOOD. Je pourrai me désinscrire à tout moment.
                    </div>                   
			    <?php do_action( 'template_notices' ); ?>

				<div style="font-size: 13px;" class="errors">
				    <?php do_action( 'bp_signup_username_errors' ); ?>
				    <?php do_action( 'bp_signup_email_errors' ); ?>
				    <?php do_action( 'bp_signup_password_errors' ); ?>
				    <?php do_action( 'bp_signup_password_confirm_errors' ); ?>
                                    <?php do_action( 'bp_validate_terms_check_errors' ); ?>
				</div>
                                <br>
				
				<div class="register-section" id="basic-details-section">
				    <?php /***** Basic Account Details ******/ ?>
				    <?php do_action( 'bp_before_account_details_fields' ); ?>
                                     	
                                    <div class="on-focus clearfix" style="position: relative; padding: 0px; margin: 10px auto; display: block; ">
                                    <input type="text" name="signup_username" placeholder="<?php _e( 'Identifiant', 'yproject' ); ?> *" id="signup_username" value="<?php bp_signup_username_value(); ?>" />
                                    <div class="tool-tip slideIn right">Choisissez un Identifiant</div>
                                    </div>
                                  
                                    <div class="on-focus clearfix" style="position: relative; padding: 0px; margin: 10px auto; display: block; ">
                                    <input  style="margin-bottom: 5px;" type="text" name="signup_email" placeholder="<?php _e( 'Adresse e-mail', 'yproject' ); ?> *" id="signup_email" value="<?php bp_signup_email_value(); ?>" />
                                    <div class="tool-tip slideIn right">Saisissez votre adresse e-mail </div>
                                    </div>
                                
                                    <div class="on-focus clearfix" style="position: relative; padding: 0px; margin: 10px auto; display: block; ">
                                    <input  style="margin-bottom: 5px;" type="password" name="signup_password" placeholder="<?php _e( 'Mot de passe', 'yproject' ); ?> *" id="signup_password" value="" />
                                    <div class="tool-tip slideIn right">Saisissez un mot de passe</div>
                                    </div>
                                  
                                    <div class="on-focus clearfix" style="position: relative; padding: 0px; margin: 10px auto; display: block; ">
                                     <input  style="margin-bottom: 5px;" type="password" name="signup_password_confirm" placeholder="<?php _e( 'Confirmation du mot de passe', 'yproject' ); ?> *" id="signup_password_confirm" value="" />
                                    <div class="tool-tip slideIn right">Confirmez votre mot de passe</div>
                                    </div>
                                    
                                    
					
                                    <label for="validate-terms-check"><input type="checkbox" name="validate-terms-check" /> J&apos;accepte <a href="<?php echo home_url().'/cgu';  ?>"  target="_blank">les conditions g&eacute;n&eacute;rales d&apos;utilisation</a></label><br />
                                    </br>
                                   
                                   
				    <?php do_action( 'bp_after_account_details_fields' ); ?>



				    <?php /***** Extra Profile Details ******/ ?>
				    <?php if ( bp_is_active( 'xprofile' ) ) : ?>

					<?php do_action( 'bp_before_signup_profile_fields' ); ?>

					<div class="register-section" id="profile-details-section">

						<?php /* Use the profile field loop to render input fields for the 'base' profile field group */ ?>
						<?php if ( bp_is_active( 'xprofile' ) ) : if ( bp_has_profile( 'profile_group_id=1' ) ) : while ( bp_profile_groups() ) : bp_the_profile_group(); ?>

						<?php while ( bp_profile_fields() ) : bp_the_profile_field(); ?>

							<div class="editfield">

								<?php if ( 'textbox' == bp_get_the_profile_field_type() ) : ?>

									<label style="font-size: 13px;" class="medium-label" for="<?php bp_the_profile_field_input_name(); ?>"><?php if (bp_get_the_profile_field_name() == 'Name') echo 'Nom public'; else bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?>*<?php endif; ?></label>
									<div style="font-size: 13px; color: #FF0000; display: inline-block"> <?php do_action( bp_get_the_profile_field_errors_action() ); ?>
									    <input type="text" name="<?php bp_the_profile_field_input_name(); ?>" id="<?php bp_the_profile_field_input_name(); ?>" value="<?php bp_the_profile_field_edit_value(); ?>" /> 
									</div>

								<?php endif; ?>

								<?php if ( 'textarea' == bp_get_the_profile_field_type() ) : ?>

									<label style="font-size: 13px;" class="medium-label" for="<?php bp_the_profile_field_input_name(); ?>"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?>*<?php endif; ?></label>
			<div style="font-size: 13px; color: #FF0000;"><?php do_action( bp_get_the_profile_field_errors_action() ); ?>
									<textarea rows="5" cols="40" name="<?php bp_the_profile_field_input_name(); ?>" id="<?php bp_the_profile_field_input_name(); ?>"><?php bp_the_profile_field_edit_value(); ?></textarea></div>

								<?php endif; ?>

								<?php if ( 'selectbox' == bp_get_the_profile_field_type() ) : ?>

				                              <label  style="font-size: 13px;"class="medium-label" for="<?php bp_the_profile_field_input_name(); ?>"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?>*<?php endif; ?></label>
				<?php do_action( bp_get_the_profile_field_errors_action() ); ?>
									<select name="<?php bp_the_profile_field_input_name(); ?>" id="<?php bp_the_profile_field_input_name(); ?>">
										<?php bp_the_profile_field_options(); ?>
									</select>

								<?php endif; ?>

								<?php if ( 'multiselectbox' == bp_get_the_profile_field_type() ) : ?>

									<label class="medium-label" for="<?php bp_the_profile_field_input_name(); ?>"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?>*<?php endif; ?></label>
									<?php do_action( bp_get_the_profile_field_errors_action() ); ?>
									<select name="<?php bp_the_profile_field_input_name(); ?>" id="<?php bp_the_profile_field_input_name(); ?>" multiple="multiple">
										<?php bp_the_profile_field_options(); ?>
									</select>

								<?php endif; ?>

								<?php if ( 'radio' == bp_get_the_profile_field_type() ) : ?>

									<div class="radio">
										<span class="label"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?>*<?php endif; ?></span>

										<?php do_action( bp_get_the_profile_field_errors_action() ); ?>
										<?php bp_the_profile_field_options(); ?>

										<?php if ( !bp_get_the_profile_field_is_required() ) : ?>
											<a class="clear-value" href="javascript:clear( '<?php bp_the_profile_field_input_name(); ?>' );"><?php _e( 'Clear', 'buddypress' ); ?></a>
										<?php endif; ?>
									</div>

								<?php endif; ?>

								<?php if ( 'checkbox' == bp_get_the_profile_field_type() ) : ?>

									<div class="checkbox">
										<span class="label"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?>*<?php endif; ?></span>

										<?php do_action( bp_get_the_profile_field_errors_action() ); ?>
										<?php bp_the_profile_field_options(); ?>
									</div>

								<?php endif; ?>

								<?php if ( 'datebox' == bp_get_the_profile_field_type() ) : ?>

									<div class="datebox">
										<label class="medium-label" for="<?php bp_the_profile_field_input_name(); ?>_day"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?>*<?php endif; ?></label>
										<?php do_action( bp_get_the_profile_field_errors_action() ); ?>

										<select name="<?php bp_the_profile_field_input_name(); ?>_day" id="<?php bp_the_profile_field_input_name(); ?>_day">
											<?php bp_the_profile_field_options( 'type=day' ); ?>
										</select>

										<select name="<?php bp_the_profile_field_input_name(); ?>_month" id="<?php bp_the_profile_field_input_name(); ?>_month">
											<?php bp_the_profile_field_options( 'type=month' ); ?>
										</select>

										<select name="<?php bp_the_profile_field_input_name(); ?>_year" id="<?php bp_the_profile_field_input_name(); ?>_year">
											<?php bp_the_profile_field_options( 'type=year' ); ?>
										</select>
									</div>

								<?php endif; ?>

								<?php do_action( 'bp_custom_profile_edit_fields' ); ?>

								<p class="description"><?php bp_the_profile_field_description(); ?></p>

							</div>

						<?php endwhile; ?>

						<input type="hidden" name="signup_profile_field_ids" id="signup_profile_field_ids" value="<?php bp_the_profile_group_field_ids(); ?>" />

						<?php endwhile; endif; endif; ?>

					</div><!-- #profile-details-section -->

					<?php do_action( 'bp_after_signup_profile_fields' ); ?>

				    <?php endif; ?>
				    
				    <?php do_action( 'bp_before_registration_submit_buttons' ); ?>
				    <div class="submit">
					<input style=" font-size: 9pt;" type="submit" name="signup_submit" id="signup_submit" value="Cr&eacute;er mon compte" />
				    </div>
				    <?php do_action( 'bp_after_registration_submit_buttons' ); ?>


				    <?php wp_nonce_field( 'bp_new_signup' ); ?>
				</div>
                            <hr style="-moz-border-bottom-colors: none; -moz-border-left-colors: none; -moz-border-right-colors: none; -moz-border-top-colors: none; border-color: -moz-use-text-color; border-image: none; border-right: 0 none; border-style: dotted none none; border-width: 1px 0 0; color: #808080; margin: 15px 0;"/>
   
                            <div id="connexion_facebook_container"><a href="javascript:void(0);" class="social_connect_login_facebook"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/facebook_connexion.jpg" alt="logo facebook connexion" class="vert-align" width="25" height="25"/><span style=" font-size:12px;" >&nbsp;S&apos;inscrire avec Facebook</span></a></div>
                            <div class="hidden"><?php dynamic_sidebar( 'sidebar-1' ); ?></div>
                                
			    <div style="clear: both"></div>

		    <?php endif; // request-details signup step ?>

				    
				    
		    <?php if ( 'completed-confirmation' == ypbp_get_current_signup_step() ) : ?>
                      
			    <h2><?php _e( 'Votre compte est maintenant cr&eacute;&eacute;.', 'yproject' ); ?></h2>

			    <?php do_action( 'template_notices' ); ?>
			    <?php do_action( 'bp_before_registration_confirmed' ); ?>

			    <?php if ( bp_registration_needs_activation() ) : ?>
				    <?php _e( 'Pour l&apos;utiliser, rendez-vous sur l&apos;e-mail que nous avons envoy&eacute. Un e-mail de confirmation vous a &eacute;t&eacute; envoy&eacute;. Pensez à vérifier votre courrier indésirable (spam).', 'yproject' ); ?>
			    <?php else : ?>
				    <p><?php _e( 'Votre compte est maintenant cr&eacute;&eacute;. Vous pouvez &agrave; pr&eacute;sent vous identifier.', 'yproject' ); ?></p>
			    <?php endif; ?>
      
			    <?php do_action( 'bp_after_registration_confirmed' ); ?>
                           <?php // Redirection ?>

		    <?php endif; // completed-confirmation signup step ?>

				    
				    
		    <?php do_action( 'bp_custom_signup_steps' ); ?>
		    <div>&nbsp;</div>
		    </form>


        
    <script type="text/javascript">
	jQuery(document).ready( function() {
	    if ( jQuery('div#blog-details').length && !jQuery('div#blog-details').hasClass('show') )
		    jQuery('div#blog-details').toggle();

	    jQuery( 'input#signup_with_blog' ).click( function() {
		    jQuery('div#blog-details').fadeOut().toggle();
	    });
	});
    </script>

    
<?php 

function ypbp_get_current_signup_step() {
        global $bp;

        return $bp->signup->step;
}

function ypbp_core_screen_signup() {
	global $bp;

	// Not a directory
	bp_update_is_directory( false, 'register' );

	if ( !isset( $bp->signup ) ) {
		$bp->signup = new stdClass;
	}

	$bp->signup->step = 'request-details';

 	if ( !bp_get_signup_allowed() ) {
		$bp->signup->step = 'registration-disabled';

	// If the signup page is submitted, validate and save
	} elseif ( isset( $_POST['signup_submit'] ) && bp_verify_nonce_request( 'bp_new_signup' ) ) {

		// Check the base account details for problems
		$account_details = bp_core_validate_user_signup( $_POST['signup_username'], $_POST['signup_email'] );

		// If there are errors with account details, set them for display
		if ( !empty( $account_details['errors']->errors['user_name'] ) )
			$bp->signup->errors['signup_username'] = $account_details['errors']->errors['user_name'][0];

		if ( !empty( $account_details['errors']->errors['user_email'] ) )
			$bp->signup->errors['signup_email'] = $account_details['errors']->errors['user_email'][0];
           
		// Check that both password fields are filled in
		if ( empty( $_POST['signup_password'] ) || empty( $_POST['signup_password_confirm'] ) )
			$bp->signup->errors['signup_password'] = __( 'Please make sure you enter your password twice', 'buddypress' );

		// Check that the passwords match
		if ( ( !empty( $_POST['signup_password'] ) && !empty( $_POST['signup_password_confirm'] ) ) && $_POST['signup_password'] != $_POST['signup_password_confirm'] )
			$bp->signup->errors['signup_password'] = __( 'The passwords you entered do not match.', 'buddypress' );

		$bp->signup->username = $_POST['signup_username'];
		$bp->signup->email = $_POST['signup_email'];

		// Now we've checked account details, we can check profile information
		if ( bp_is_active( 'xprofile' ) ) {

			// Make sure hidden field is passed and populated
			if ( isset( $_POST['signup_profile_field_ids'] ) && !empty( $_POST['signup_profile_field_ids'] ) ) {

				// Let's compact any profile field info into an array
				$profile_field_ids = explode( ',', $_POST['signup_profile_field_ids'] );

				// Loop through the posted fields formatting any datebox values then validate the field
				foreach ( (array) $profile_field_ids as $field_id ) {
					if ( !isset( $_POST['field_' . $field_id] ) ) {
						if ( !empty( $_POST['field_' . $field_id . '_day'] ) && !empty( $_POST['field_' . $field_id . '_month'] ) && !empty( $_POST['field_' . $field_id . '_year'] ) )
							$_POST['field_' . $field_id] = date( 'Y-m-d H:i:s', strtotime( $_POST['field_' . $field_id . '_day'] . $_POST['field_' . $field_id . '_month'] . $_POST['field_' . $field_id . '_year'] ) );
					}

					// Create errors for required fields without values
					if ( xprofile_check_is_required_field( $field_id ) && empty( $_POST['field_' . $field_id] ) )
						$bp->signup->errors['field_' . $field_id] = __( 'This is a required field', 'buddypress' );
				}

			// This situation doesn't naturally occur so bounce to website root
			} else {
				bp_core_redirect( bp_get_root_domain() );
			}
		}

		// Finally, let's check the blog details, if the user wants a blog and blog creation is enabled
		if ( isset( $_POST['signup_with_blog'] ) ) {
			$active_signup = $bp->site_options['registration'];

			if ( 'blog' == $active_signup || 'all' == $active_signup ) {
				$blog_details = bp_core_validate_blog_signup( $_POST['signup_blog_url'], $_POST['signup_blog_title'] );

				// If there are errors with blog details, set them for display
				if ( !empty( $blog_details['errors']->errors['blogname'] ) )
					$bp->signup->errors['signup_blog_url'] = $blog_details['errors']->errors['blogname'][0];

				if ( !empty( $blog_details['errors']->errors['blog_title'] ) )
					$bp->signup->errors['signup_blog_title'] = $blog_details['errors']->errors['blog_title'][0];
			}
		}

		do_action( 'bp_signup_validate' );

		// Add any errors to the action for the field in the template for display.
		if ( !empty( $bp->signup->errors ) ) {
			foreach ( (array) $bp->signup->errors as $fieldname => $error_message ) {
				// addslashes() and stripslashes() to avoid create_function()
				// syntax errors when the $error_message contains quotes
				add_action( 'bp_' . $fieldname . '_errors', create_function( '', 'echo apply_filters(\'bp_members_signup_error_message\', "<div class=\"error\">" . stripslashes( \'' . addslashes( $error_message ) . '\' ) . "</div>" );' ) );
			}
		} else {
			$bp->signup->step = 'save-details';

			// No errors! Let's register those deets.
			$active_signup = !empty( $bp->site_options['registration'] ) ? $bp->site_options['registration'] : '';

			if ( 'none' != $active_signup ) {

				// Make sure the extended profiles module is enabled
				if ( bp_is_active( 'xprofile' ) ) {
					// Let's compact any profile field info into usermeta
					$profile_field_ids = explode( ',', $_POST['signup_profile_field_ids'] );

					// Loop through the posted fields formatting any datebox values then add to usermeta - @todo This logic should be shared with the same in xprofile_screen_edit_profile()
					foreach ( (array) $profile_field_ids as $field_id ) {
						if ( ! isset( $_POST['field_' . $field_id] ) ) {

							if ( ! empty( $_POST['field_' . $field_id . '_day'] ) && ! empty( $_POST['field_' . $field_id . '_month'] ) && ! empty( $_POST['field_' . $field_id . '_year'] ) ) {
								// Concatenate the values
								$date_value = $_POST['field_' . $field_id . '_day'] . ' ' . $_POST['field_' . $field_id . '_month'] . ' ' . $_POST['field_' . $field_id . '_year'];

								// Turn the concatenated value into a timestamp
								$_POST['field_' . $field_id] = date( 'Y-m-d H:i:s', strtotime( $date_value ) );
							}
						}

						if ( !empty( $_POST['field_' . $field_id] ) )
							$usermeta['field_' . $field_id] = $_POST['field_' . $field_id];

						if ( !empty( $_POST['field_' . $field_id . '_visibility'] ) )
							$usermeta['field_' . $field_id . '_visibility'] = $_POST['field_' . $field_id . '_visibility'];
					}

					// Store the profile field ID's in usermeta
					$usermeta['profile_field_ids'] = $_POST['signup_profile_field_ids'];
				}

				// Hash and store the password
				$usermeta['password'] = wp_hash_password( $_POST['signup_password'] );

				// If the user decided to create a blog, save those details to usermeta
				if ( 'blog' == $active_signup || 'all' == $active_signup )
					$usermeta['public'] = ( isset( $_POST['signup_blog_privacy'] ) && 'public' == $_POST['signup_blog_privacy'] ) ? true : false;

				$usermeta = apply_filters( 'bp_signup_usermeta', $usermeta );

				// Finally, sign up the user and/or blog
				if ( isset( $_POST['signup_with_blog'] ) && is_multisite() )
					$wp_user_id = bp_core_signup_blog( $blog_details['domain'], $blog_details['path'], $blog_details['blog_title'], $_POST['signup_username'], $_POST['signup_email'], $usermeta );
				else
					$wp_user_id = bp_core_signup_user( $_POST['signup_username'], $_POST['signup_password'], $_POST['signup_email'], $usermeta );

				if ( is_wp_error( $wp_user_id ) ) {
					$bp->signup->step = 'request-details';
					bp_core_add_message( $wp_user_id->get_error_message(), 'error' );
				} else {
					$bp->signup->step = 'completed-confirmation';
				}
			}

			do_action( 'bp_complete_signup' );
		}

	}

	do_action( 'bp_core_screen_signup' );
	//bp_core_load_template( apply_filters( 'bp_core_template_register', array( 'register', 'registration/register' ) ) );
}

?>
