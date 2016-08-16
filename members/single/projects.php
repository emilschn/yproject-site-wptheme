<?php 
$page_publish = get_page_by_path('financement');
$page_mes_investissements = get_page_by_path('mes-investissements');
$display_loggedin_user = (bp_loggedin_user_id() == bp_displayed_user_id());
$WDGUser_displayed = new WDGUser(bp_displayed_user_id());
?>

<h2 class="underlined">Projets</h2>

	<div>
		<div class="left two-thirds">
			<strong><?php if ($display_loggedin_user) { ?>Mes projets :<?php } else { ?>Ses projets :<?php } ?></strong>
			
			<?php
			$campaign_status = array('publish');
			if ($display_loggedin_user) array_push($campaign_status, 'private');
			$args = array(
				'post_type' => 'download',
				'author' => bp_displayed_user_id(),
				'post_status' => $campaign_status
			);
			if (!$display_loggedin_user) {
				$args['meta_key'] = 'campaign_vote';
				$args['meta_compare'] = '!='; 
				$args['meta_value'] = 'preparing';
			}
			query_posts($args);
			$has_projects = false;
			$page_dashboard = get_page_by_path('tableau-de-bord');

			if (have_posts()) {
				$has_projects = true;
				$i = 0;
				while (have_posts()) {
					the_post();
					if ($i > 0) {?> | <?php }
					if ($display_loggedin_user) { 
					?><a href="<?php echo get_permalink($page_dashboard->ID) . '?campaign_id=' . get_the_ID(); ?>"><?php the_title(); ?></a><?php
					} else {
					?><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><?php
					}
					$i++;
				}
			}
			?>
					
			<?php
			$wdg_current_user = new WDGUser( bp_displayed_user_id() );
			$api_user_id = $wdg_current_user->get_api_id();
			$project_list = BoppUsers::get_projects_by_role($api_user_id, BoppLibHelpers::$project_team_member_role['slug']);
			if (!empty($project_list)) {
				$has_projects = true;
				foreach ($project_list as $project) {	    
					if ($i > 0) {?> | <?php }
					if ($display_loggedin_user) { 
					?><a href="<?php echo get_permalink($page_dashboard->ID) . '?campaign_id=' . $project->project_wp_id; ?>"><?php echo $project->project_name; ?></a><?php
					} else {
					?><a href="<?php echo get_permalink($project->project_wp_id); ?>"><?php echo $project->project_name; ?></a><?php
					}
					$i++;
				}
			}
			
			if (!$has_projects): ?>
			Aucun
			<?php endif; ?>

		</div>
	    
		<?php if ($display_loggedin_user) { ?>
		<div class="right">
			<a href="<?php echo get_permalink($page_publish->ID); ?>" class="button right">Financer mon projet</a>
		</div>
		<?php } ?>
	    
		<div class="clear"></div>
	</div>
	<br /><br /><br />
	
<div id="ajax-loader" class="center" style="text-align: center;"><img id="ajax-loader-img" src="<?php echo get_stylesheet_directory_uri() ?>/images/loading.gif" alt="chargement" /></div>

<?php 
if (is_user_logged_in() && $display_loggedin_user) :
	//Si on a demandé de renvoyer le code
	if (isset($_GET['invest_id_resend']) && $_GET['invest_id_resend'] != '') {
	    $contractid = ypcf_get_signsquidcontractid_from_invest($_GET['invest_id_resend']);
	    // $signsquid_infos = signsquid_get_contract_infos($contractid);
	    $signsquid_signatory = signsquid_get_contract_signatory($contractid);
	    $current_user = wp_get_current_user();
	    if ($signsquid_signatory != '' && $signsquid_signatory->{'email'} == $current_user->user_email) {
			if (ypcf_send_mail_purchase($_GET['invest_id_resend'], "send_code", $signsquid_signatory->{'code'}, $current_user->user_email)) {
				?>
				Votre code de signature de contrat a &eacute;t&eacute; renvoy&eacute; &agrave; l&apos;adresse <?php echo $current_user->user_email; ?>.<br />
				<?php
			} else {
				?>
				<span class="errors">Il y a eu une erreur lors de l&apos;envoi du code. N&apos;h&eacute;sitez pas &agrave; nous contacter.</span><br />
				<?php
			}
	    } else {
		?>
		<span class="errors">Nous ne trouvons pas le contrat correspondant.</span><br />
		<?php
	    }
	}
	?>
	<h2 class="underlined">Mon porte-monnaie électronique</h2>

	<?php $post_details = get_page_by_path("details-des-investissements"); ?>
	<?php $amount = $WDGUser_displayed->get_lemonway_wallet_amount(); ?>
	Vous disposez de <?php echo $amount; ?> &euro; dans votre porte-monnaie.
	<a href="<?php echo get_permalink($post_details->ID); ?>">Voir le d&eacute;tail de mes royalties</a><br /><br />
	

	<?php if ($amount > 0): ?>
		<form action="" method="POST" enctype="multipart/form-data">
			<?php if ($WDGUser_displayed->has_registered_iban()): ?>
			<input type="submit" class="button" value="Reverser sur mon compte bancaire" />

			<?php else: ?>
			<label for="holdername" class="large-label">Nom du propri&eacute;taire du compte : </label>
				<input type="text" id="holdername" name="holdername" value="<?php echo $WDGUser_displayed->get_iban_info("holdername"); ?>" /> <br />
			<label for="address" class="large-label">Adresse du compte : </label>
				<input type="text" id="address" name="address" value="<?php echo $WDGUser_displayed->get_iban_info("address1"); ?>" /> <br />
			<label for="iban" class="large-label">IBAN : </label>
				<input type="text" id="iban" name="iban" value="<?php echo $WDGUser_displayed->get_iban_info("iban"); ?>" /> <br />
			<label for="bic" class="large-label">BIC : </label>
				<input type="text" id="bic" name="bic" value="<?php echo $WDGUser_displayed->get_iban_info("bic"); ?>" /> <br />

			<input type="submit" class="button" value="Enregistrer et reverser sur mon compte bancaire" />
			<?php endif; ?>

			<input type="hidden" name="action" value="user_wallet_to_bankaccount" />
			<input type="hidden" name="user_id" value="<?php echo bp_displayed_user_id(); ?>" />
		</form>
	<?php endif; ?>

	<h2 class="underlined"><?php _e( 'Mes transferts d&apos;argent', 'yproject' ); ?></h2>
	<?php
	$args = array(
		'author'    => get_current_user_id(),
		'post_type' => 'withdrawal_order_lw',
		'post_status' => 'any',
		'orderby'   => 'post_date',
		'order'     =>  'ASC'
	);
	$transfers = get_posts($args);
	if ($transfers) :
	?>
	<ul class="user_history">
		<?php 
		foreach ( $transfers as $post ) :
			$post = get_post($post);
			$post_amount = $post->post_title;
			?>
			<?php if ($post->post_status == 'publish'): ?>
			<li id="<?php echo $post->post_content; ?>"><?php echo $post->post_date; ?> : <?php echo $post_amount; ?>&euro; -- Termin&eacute;</li>
			<?php elseif ($post->post_status == 'draft'): ?>
			<li id="<?php echo $post->post_content; ?>"><?php echo $post->post_date; ?> : <?php echo $post_amount; ?>&euro; -- Annul&eacute;</li>
			<?php else: ?>
			<li id="<?php echo $post->post_content; ?>"><?php echo $post->post_date; ?> : <?php echo $post_amount; ?>&euro; -- En cours</li>
			<?php endif; ?>
		<?php
		endforeach;
		?>
	</ul>
	<?php else: ?>
		Aucun transfert d&apos;argent.
	<?php endif; ?>
<?php endif; ?>
