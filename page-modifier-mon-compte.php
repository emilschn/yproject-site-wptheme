<?php 
/**
 * Enregistrement fait dans le plugin pour gérer la redirection éventuelle au bon moment
 * Le reste de la page devrait être fait dans un shortcode. On verra ça plus tard.
 */ 
if (!is_user_logged_in()) wp_redirect(site_url());
ypcf_session_start();
get_header();
$WDGUser_current = WDGUser::current();
?>

<div id="content">
	<div class="padder">
	    
	    <?php locate_template( array( 'members/single/admin-bar.php' ), true ); ?>
	    
	    <div class="center">
		    <h2 class="underlined"><?php _e( 'Param&egrave;tres', 'yproject' ); ?></h2>
		    
		    <?php if (isset($_POST['update_user_posted'])){ 
			    $valid = true;
			    if ( isset($_POST["update_password_current"]) && !wp_check_password( $_POST["update_password_current"], $current_user->data->user_pass, $current_user->ID)) :
				$valid = false;
			    ?>
				<span class="errors"><?php _e( 'Le mot de passe renseign&eacute; ne correspond pas. Les modifications ne sont pas enregistr&eacute;es.', 'yproject' ); ?></span><br />
			    <?php endif;
			    
			    global $validate_email;
			    if ($validate_email !== true):
				$valid = false;
			    ?>
				<span class="errors"><?php _e( 'L&apos;adresse e-mail renseign&eacute;e est invalide ou d&eacute;j&agrave; utilis&eacute;e', 'yproject' ); ?></span><br />
			    <?php endif; ?>
				
			    <?php if ($valid) { ?>
				
				    <span class="invest_success"><?php _e('Informations utilisateur enregistr&eacute;es', 'yproject'); ?></span><br />
				    
				    <?php
				    if(!empty($_FILES['avatar_image']['name'])) {
					    $avatar_error = false;
					    if ($_FILES['avatar_image']['error'] > 0) {
						    echo "<span class='errors'>Erreur lors du transfert</span><br/>";
						    $avatar_error = true;
						    
					    } else {
						    $info = getimagesize($_FILES['avatar_image']['tmp_name']);
					    }
					    
					    if ($info === FALSE) {
						    echo "<span class='errors'>Impossible de déterminer le type de l'image</span><br/>";
						    $avatar_error=true;
					    }
					    if (($info[2] !== IMAGETYPE_JPEG) && ($info[2] !== IMAGETYPE_PNG)) {
						    echo "<span class='errors'>L'image n'est pas au format JPG ou PNG</span><br/>";	
						    $avatar_error=true;
					    }
					    $type;
					    if($info[2] === IMAGETYPE_JPEG) $type='.jpg';
					    if($info[2] === IMAGETYPE_PNG) $type='.png';
					    if(!$avatar_error){
						    $avatar_path = BP_AVATAR_UPLOAD_PATH . '/avatars/';
						    if ( !file_exists($avatar_path)) {
							    mkdir($avatar_path);
						    }
						    $avatar_path .= bp_loggedin_user_id().'/';
						    if ( !file_exists($avatar_path)) {
							    mkdir($avatar_path);
						    }
						    move_uploaded_file($_FILES['avatar_image']['tmp_name'],$avatar_path.'avatar'.$type);
					    }
				    }
				    
				    if ($_POST['facebook_avatar'] || $_POST['reset_avatar']) {
					    if(file_exists(BP_AVATAR_UPLOAD_PATH. '/avatars/'.bp_loggedin_user_id().'/avatar.png')){
						    unlink(BP_AVATAR_UPLOAD_PATH. '/avatars/'.bp_loggedin_user_id().'/avatar.png');
					    }
					    if(file_exists(BP_AVATAR_UPLOAD_PATH. '/avatars/'.bp_loggedin_user_id().'/avatar.jpg')){
						    unlink(BP_AVATAR_UPLOAD_PATH. '/avatars/'.bp_loggedin_user_id().'/avatar.jpg');
					    }
					    if($_POST['reset_avatar']){
						    file_put_contents(BP_AVATAR_UPLOAD_PATH. '/avatars/'.bp_loggedin_user_id().'/avatar.jpg', file_get_contents(get_stylesheet_directory_uri() . "/images/default_avatar.jpg"));
					    }
				    }
			    }
		    }
		    
			if (isset($_SESSION['error_invest'])) {
				for ($i = 0; $i < count($_SESSION['error_invest']); $i++) { ?>
				<span class="errors"><?php echo $_SESSION['error_invest'][$i]; ?></span><br />
				<?php }
				unset($_SESSION['error_invest']);
			}
			?>
				
		    <?php
		    $is_campaign_investment_type = FALSE;
		    if (isset($_SESSION['redirect_current_campaign_id'])) {
			    $post_campaign = get_post($_SESSION['redirect_current_campaign_id']);
			    $campaign = atcf_get_campaign($post_campaign);
			    if ($campaign->funding_type() != 'fundingdonation') { $is_campaign_investment_type = TRUE; }
		    }
		    ?>

		    <?php $page_update_account = get_page_by_path('modifier-mon-compte'); ?>
		    <form name="update-form" class="standard-form" action="<?php echo get_permalink($page_update_account->ID); ?>" method="post" enctype="multipart/form-data" id='update-user-form'>

				<h4 style="padding-left: 20px;">
					<?php if ($is_campaign_investment_type): ?>
						<?php _e('Les informations suivies d&apos;une &eacute;toile sont n&eacute;cessaires pour investir sur un projet.', 'yproject'); ?>
					<?php else: ?>
						<?php _e('Les informations suivies d&apos;une &eacute;toile sont n&eacute;cessaires pour soutenir un projet.', 'yproject'); ?>
					<?php endif; ?>
				</h4>

				<div id="form_infoperso_projet">
					<label for="update_gender" class="standard-label"><?php _e("Vous &ecirc;tes", 'yproject'); ?> <?php if ($is_campaign_investment_type){ ?>*<?php } ?></label>
					<select name="update_gender" id="update_gender">
						<option value="female"<?php if ($current_user->get('user_gender') == "female") echo ' selected="selected"';?>>une femme</option>
						<option value="male"<?php if ($current_user->get('user_gender') == "male") echo ' selected="selected"';?>>un homme</option>
					</select><br />

					<label for="update_firstname" class="standard-label"><?php _e( 'Pr&eacute;nom', 'yproject' ); ?> *</label>
					<input type="text" name="update_firstname" id="update_firstname" value="<?php echo $current_user->user_firstname; ?>" /><br />

					<label for="update_lastname" class="standard-label"><?php _e( 'Nom', 'yproject' ); ?> *</label>
					<input type="text" name="update_lastname" id="update_lastname" value="<?php echo $current_user->user_lastname; ?>" /><br />

					<label for="update_publicname" class="standard-label">Nom public</label>
					<input type="text" name="update_publicname" id="update_publicname" value="<?php echo $current_user->display_name; ?>" /><br />

					<label for="update_birthday_day" class="standard-label"><?php _e( 'Date de naissance', 'yproject' ); ?> *</label>
					<select name="update_birthday_day" id="update_birthday_day">
						<?php for ($i = 1; $i <= 31; $i++) { ?>
							<option value="<?php echo $i; ?>"<?php if ($current_user->get('user_birthday_day') == $i) echo ' selected="selected"';?>><?php echo $i; ?></option>
						<?php } ?>
					</select>
					<select name="update_birthday_month" id="update_birthday_month">
						<?php
						$months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
						for ($i = 1; $i <= 12; $i++) { ?>
							<option value="<?php echo $i; ?>"<?php if ($current_user->get('user_birthday_month') == $i) echo ' selected="selected"';?>><?php _e($months[$i - 1]); ?></option>
						<?php }
						?>
					</select>
					<select name="update_birthday_year" id="update_birthday_month">
						<?php for ($i = date("Y"); $i >= 1900; $i--) { ?>
							<option value="<?php echo $i; ?>"<?php if ($current_user->get('user_birthday_year') == $i) echo ' selected="selected"';?>><?php echo $i; ?></option>
						<?php } ?>
					</select>
					<br />

					<label for="update_birthplace" class="standard-label"><?php _e( 'Ville de naissance', 'yproject' ); ?> <?php if ($is_campaign_investment_type){ ?>*<?php } ?></label>
					<input type="text" name="update_birthplace" id="update_birthplace" value="<?php echo $current_user->get('user_birthplace'); ?>" /><br />

					<?php require_once("country_list.php"); ?>
					<label for="update_nationality" class="standard-label"><?php _e( 'Nationalit&eacute;', 'yproject' ); ?> *</label>
					<select name="update_nationality" id="update_nationality">
						<option value=""></option>
						<?php foreach ($country_list as $country_code => $country_name) : ?>
							<option value="<?php echo $country_code; ?>"<?php if ($current_user->get('user_nationality') == $country_code) echo ' selected="selected"';?>><?php echo $country_name; ?></option>
						<?php endforeach; ?>
					</select><br />

					<label for="update_address" class="standard-label"><?php _e( 'Adresse', 'yproject' ); ?> <?php if ($is_campaign_investment_type){ ?>*<?php } ?></label>
					<input type="text" name="update_address" id="update_address" value="<?php echo $current_user->get('user_address'); ?>" /><br />

					<label for="update_postal_code" class="standard-label"><?php _e( 'Code postal', 'yproject' ); ?> <?php if ($is_campaign_investment_type){ ?>*<?php } ?></label>
					<input type="text" name="update_postal_code" id="update_postal_code" value="<?php echo $current_user->get('user_postal_code'); ?>" /><br />

					<label for="update_city" class="standard-label"><?php _e( 'Ville', 'yproject' ); ?> <?php if ($is_campaign_investment_type){ ?>*<?php } ?></label>
					<input type="text" name="update_city" id="update_city" value="<?php echo $current_user->get('user_city'); ?>" /><br />

					<label for="update_country" class="standard-label"><?php _e( 'Pays', 'yproject' ); ?> <?php if ($is_campaign_investment_type){ ?>*<?php } ?></label>
					<input type="text" name="update_country" id="update_country" value="<?php echo $current_user->get('user_country'); ?>" /><br />

					<label for="update_mobile_phone" class="standard-label"><?php _e( 'T&eacute;l&eacute;phone mobile', 'yproject' ); ?></label>
					<input type="text" name="update_mobile_phone" id="update_mobile_phone" value="<?php echo $current_user->get('user_mobile_phone'); ?>" /><br /><br />

					<?php 
					//Champs avatar et description uniquement si on n'est pas redirigé depuis un investissement
					if (!isset($_SESSION['redirect_current_campaign_id'])): ?>
						<label for="avatar_image" class="standard-label">Avatar</label>
						<input type="file" name="avatar_image" id="avatar_image" />
						<input type="checkbox" name="reset_avatar">Supprimer l'avatar actuel
						<?php 
						$facebook_meta = get_user_meta($current_user->ID, 'social_connect_facebook_id', true);
						if (isset($facebook_meta) && $facebook_meta != "") : 
						?>
						<input type="checkbox" name="facebook_avatar">Utiliser l'avatar facebook
						<?php endif; ?>
						<br />

						<label for="user_description" class="standard-label">Description</label>
						<textarea name="user_description"> <?php $user_meta = get_userdata(bp_loggedin_user_id()); echo($user_meta->description);?></textarea> <br /> <br />
						
						<strong>RIB enregistré</strong><br />
						<label for="holdername" class="standard-label" style="width: 220px;">Nom du propri&eacute;taire du compte : </label>
						<?php echo $WDGUser_current->get_iban_info("holdername"); ?> <br />
							
						<label for="address" class="standard-label" style="width: 220px;">Adresse du compte : </label>
						<?php echo $WDGUser_current->get_iban_info("address1"); ?> <br />
						
						<label for="iban" class="standard-label" style="width: 220px;">IBAN : </label>
						<?php echo $WDGUser_current->get_iban_info("iban"); ?> <br />
						
						<label for="bic" class="standard-label" style="width: 220px;">BIC : </label>
						<?php echo $WDGUser_current->get_iban_info("bic"); ?> <br />
					<?php endif; ?>
				</div>
		

				<?php 
				//Si l'utilisateur n'est pas connecté avec Facebook, et qu'on ne vient pas d'une redirection d'investissement, on peut afficher les données "sensibles" : email et mot de passe
				if ((strpos($current_user->user_url, 'facebook.com') === false) && !isset($_SESSION['redirect_current_campaign_id'])) {
				?>
					<h4 style="padding-left: 20px;"><?php _e('Informations de base', 'yproject'); ?></h4>

					<div id="form_infoperso_projet">
						<label for="update_email" class="large-label"><?php _e( 'Adresse e-mail', 'yproject' ); ?></label>
						<input type="text" name="update_email" id="update_email" value="<?php echo $current_user->user_email; ?>" /><br />

						<label for="update_password" class="large-label"><?php _e( 'Nouveau mot de passe', 'yproject' ); ?><?php _e(' (vide si pas de changement)', 'yproject'); ?></label>
						<input type="password" name="update_password" id="update_password" value="" /><br />

						<label for="update_password_confirm" class="large-label"><?php _e( 'Confirmer le nouveau mot de passe', 'yproject' ); ?><?php _e(' (vide si pas de changement)', 'yproject'); ?></label>
						<input type="password" name="update_password_confirm" id="update_password_confirm" value="" /><br /><br />

						<label for="update_password_current" class="large-label"><?php _e( 'Mot de passe actuel', 'yproject' ); ?>*</label>
						<input type="password" name="update_password_current" id="update_password_current" value="" />
					</div>
				<?php } elseif (strpos($current_user->user_url, 'facebook.com') !== false) { ?>
					<h4 style="padding-left: 20px;">Contact</h4>

					<div id="form_infoperso_projet">
						<label for="update_email_contact" class="large-label">Adresse e-mail de contact</label>
						<input type="text" name="update_email_contact" id="update_email_contact" value="<?php echo $current_user->user_email; ?>" /><br />
					</div>
				<?php } ?>
		   
				<center><input type="submit" value="Enregistrer les modifications" /></center>

				<?php if (isset($_SESSION['redirect_current_amount_part'])) { ?>
					<input type="hidden" name="amount_part" value="<?php echo $_SESSION['redirect_current_amount_part']; ?>" />
				<?php } ?>
				<?php if (isset($_SESSION['redirect_current_invest_type']) && $_SESSION['redirect_current_invest_type'] != "new_organisation") { ?>
					<input type="hidden" name="invest_type" value="<?php echo $_SESSION['redirect_current_invest_type']; ?>" />
				<?php } ?>
				<input type="hidden" name="update_user_posted" value="posted" />
				<input type="hidden" name="update_user_id" value="<?php echo $current_user->ID; ?>" />
			
			
				<h4 style="padding-left: 20px;"><?php _e('Organisations', 'yproject'); ?></h4>

				<div id="form_infoperso_projet">
					<?php $page_new_orga = get_page_by_path('creer-une-organisation'); ?>
					<div class="right">
						<a href="<?php echo get_permalink($page_new_orga->ID); ?>" class="button right">Cr&eacute;er une organisation</a>
					</div>


					<?php
					$page_edit_orga = get_page_by_path('editer-une-organisation');
					$can_edit = true;
					global $current_user;
					$api_user_id = BoppLibHelpers::get_api_user_id($current_user->ID);
					$organisations_list = BoppUsers::get_organisations_by_role($api_user_id, BoppLibHelpers::$organisation_creator_role['slug']);
					if (!empty($organisations_list)) {
						foreach ($organisations_list as $organisation_item) {
							$str_organisations .= '<li>';
							if ($can_edit) { $str_organisations .= '<a href="'.  get_permalink($page_edit_orga->ID) .'?orga_id='.$organisation_item->organisation_wpref.'">'; }
							$str_organisations .= $organisation_item->organisation_name; 
							if ($can_edit) { $str_organisations .= '</a>'; }
							$str_organisations .= '</li>';
						}
					}
					if ($str_organisations != ''): ?>
						<ul style="padding-left: 0px;"><?php echo $str_organisations; ?></ul>

					<?php else: ?>
						<?php _e('Aucune organisation.', 'yproject'); ?>

					<?php endif; ?>
				</div>
		    </form>
                    	
				<?php /*
                     <h2 class="underlined"><?php _e( 'Avertissement', 'yproject' ); ?></h2>
                    <div id="form_infoperso_projet">
                   
                    <div id="warning">
                        <?php 
                        $check = yproject_check_user_warning(get_current_user_id());
                        if (!$check){ 
                        ?>
                        <p class="button red"> Il est important de répondre au formulaire pour avoir accès à l'ensemble des descriptions des différents projets.</p>
                        <?php } else { ?>
                        <p class="button"> Vous avez pris connaisances dees risques d'investissement : </p>
                        <?php } ?>
                         Avertissement : l’investissement dans les projets présentés sur WEDOGOOD.co comporte des risques spécifiques :
                       <ul>
                           <li>Risque de perte totale ou partielle du montant investi</li>
                           <li>Risque d’illiquidiité : la revente des parts sociales ou des contrats financiers n’est pas garantie, elle peut être incertaine voire impossible</li>
                           <li>Le retour sur investissement dépend de la réussite du projet financé</li>
                       </ul> 
                    <?php 
                       
                    if ($check){ ?>
                       <form action="" name="" id="" class="standard-form"  method="post" >
                           <b>Avez-vous conscience que vous pouvez perdre éventuellement la totalité de votre investissement ? </b><div id="input-style"><input type="radio" name="warning1" checked="checked"  value="true"> OUI <input type="radio" name="warning1" disabled="disabled" value="false"> NON</div>
                            <b>Avez-vous conscience que vous aurez des difficultés ou l'impossibilité de revendre vos parts ou contrats ?</b><div id="input-style"><input type="radio" name="warning2" checked="checked" value="true"> OUI <input type="radio" name="warning2" disabled="disabled" value="false"> NON</div>

                           <input type="checkbox" disabled="disabled" name="warning3" value="true">Je reconnais ne pas avoir fait l'objet de démarchage bancaire ou financier pour m'inscrire sur WEDOGOOD.co
                          
                       
                    <?php
                    } else { ?>
                        <form action="" name="" id="" class="standard-form"  method="post" >
                           <b>Avez-vous conscience que vous pouvez perdre éventuellement la totalité de votre investissement ? </b><div id="input-style"><input type="radio" name="warning1" value="true"> OUI <input type="radio" name="warning1" value="false"> NON</div>
                           <b>Avez-vous conscience que vous aurez des difficultés ou l'impossibilité de revendre vos parts ou contrats ?</b><div id="input-style"><input type="radio" name="warning2" value="true"> OUI <input type="radio" name="warning2" value="false"> NON</div>

                           <input type="checkbox" name="warning3" value="true">Je reconnais ne pas avoir fait l'objet de démarchage bancaire ou financier pour m'inscrire sur WEDOGOOD.co

                       <br/>
                       <center><input type="submit" name="submit_warning" value="valider les avertissements"></center>
                    <?php } ?>
                     </form>   
                    </div>
                    </div> <?php */ ?>
	    </div>
	</div>
</div>

<?php get_footer(); ?>

<?php
function clearDir($dossier) {
	$ouverture=@opendir($dossier);
	if (!$ouverture) return;
	while($fichier=readdir($ouverture)) {
		if ($fichier == '.' || $fichier == '..') continue;
			if (is_dir($dossier."/".$fichier)) {
				$r=clearDir($dossier."/".$fichier);
				if (!$r) return false;
			}
			else {
				$r=@unlink($dossier."/".$fichier);
				if (!$r) return false;
			}
	}
	closedir($ouverture);
	$r=@rmdir($dossier);
	if (!$r) return false;
	return true;
}