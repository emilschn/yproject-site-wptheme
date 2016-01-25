<?php
global $campaign, $current_error;
if (!isset($campaign)) {
	$campaign = atcf_get_current_campaign();
}

if (isset($campaign)) {
	$min_value = ypcf_get_min_value_to_invest();
	$max_value = ypcf_get_max_value_to_invest();
	$part_value = ypcf_get_part_value();
	$max_part_value = ypcf_get_max_part_value();
	
	if ($max_part_value > 0):
		global $edd_options;
		$page_invest = get_page_by_path('investir');
		$page_invest_link = get_permalink($page_invest->ID);
		$page_invest_link .= '?campaign_id=' . $_GET['campaign_id'];
?>
    
		<?php echo ypcf_print_invest_breadcrumb(1, $campaign->funding_type()); ?>
		
		<div class="invest_step1_generalities">
		<?php switch ($campaign->funding_type()) {
			case "fundingdonation":
				echo wpautop( $edd_options['donation_generalities'] );
				break;
		    default:
				echo wpautop( $edd_options['investment_generalities'] );
				break;
		} ?>
		</div>
		
		<div class="invest_step1_currentproject"><?php echo html_entity_decode( $campaign->investment_terms() ); ?></div>
			
		<?php if (!ypcf_mangopay_is_user_strong_authenticated($test_user->ID) && ypcf_mangopay_is_user_strong_authentication_sent($test_user->ID)): ?>
			<div class="invest_step1_currentproject" style="text-align: center; font-weight: bold;">Votre pi&egrave;ce d&apos;identit&eacute; est en cours de validation.<br />Un d&eacute;lai maximum de 24h est n&eacute;cessaire &agrave; cette validation.<br />Merci de votre compr&eacute;hension.</div>
		<?php endif; ?>
			
		<?php ?>
		
		<form id="invest_form" action="<?php echo $page_invest_link; ?>" method="post" enctype="multipart/form-data">
			<input type="hidden" id="input_invest_min_value" name="old_min_value" value="<?php echo $min_value; ?>">
			<input type="hidden" id="input_invest_max_value" name="old_max_value" value="<?php echo $max_value; ?>">
			<input type="hidden" id="input_invest_part_value" name="part_value" value="<?php echo $part_value; ?>">
			<input type="hidden" id="input_invest_max_part_value" name="part_value" value="<?php echo $max_part_value; ?>">
			<input type="hidden" id="input_invest_amount_total" value="<?php echo ypcf_get_current_amount(); ?>">
			
			<?php switch ($campaign->funding_type()) {
				case 'fundingdonation':
					$rewards = atcf_get_rewards($campaign->ID);
					?>
					<span style="display:none;">(<span id="input_invest_amount">0</span> &euro;)</span><br />
				
					<?php if (isset($rewards->rewards_list)): ?>
						<p>Choisissez votre contrepartie :</p>
						<ul id="reward-selector">
							<li><label>
								<input type="radio" name="selected_reward" data-amount="0" value="-1" checked="checked">
								<span class="reward-amount" style="display:none"><?php echo $min_value; ?></span>
								Je ne souhaite <span class="reward-name">pas de contrepartie</span>.
							</label></li>

						<?php foreach ($rewards->rewards_list as $reward): ?>
							<li <?php if (!$rewards->is_available_reward($reward['id'])) { ?>class="unavailable-reward"<?php } ?>><label>

							<div>
								<input type="radio" name="selected_reward" 
										value="<?php echo $reward['id']; ?>"
										<?php if (!$rewards->is_available_reward($reward['id'])) { ?>disabled="disabled"<?php } ?> />

								<span class="reward-amount"><?php echo intval($reward['amount']); ?></span>&euro; ou plus
							</div>
							<div class="reward-name reward-not-null"><?php echo $reward['name']; ?></div>

							<?php if ($rewards->is_limited_reward($reward['id'])): ?>
								<?php $remaining = (intval($reward['limit']) - intval($reward['bought'])); ?>
								<div>
									<span class="detail">Contrepartie limit&eacute;e :</span>
									<span class="reward-remaining"><?php echo $remaining; ?></span>
									restant<?php if ($remaining > 1) { ?>s<?php } ?>
									sur <?php echo intval($reward['limit']); ?>
								</div>
							<?php endif; ?>

							</label></li>
						<?php endforeach; ?>
						</ul>
						Je souhaite donner <input type="text" id="input_invest_amount_part" name="amount_part" placeholder="<?php echo $min_value; ?>"> &euro; <br />
					<?php endif;
				break;
				
				case 'fundingdevelopment':
				case 'fundingproject': ?>
					<input type="text" id="input_invest_amount_part" name="amount_part" placeholder="<?php echo $min_value; ?>" value="<?php echo (!empty($_GET["init_invest"]) ? $_GET["init_invest"] : ''); ?>"> &euro; <span id="input_invest_amount" class="hidden">0</span><br />
				<?php
				break;
			} ?>
					
			&nbsp;&nbsp;<center><a href="javascript:void(0);" id="link_validate_invest_amount" class="button">Valider</a></center><br /><br />
		
			<div id="validate_invest_amount_feedback" style="display: none;">
				<?php $temp_min_part = ceil($min_value / $part_value); ?>
		
				<?php switch ($campaign->funding_type()) {
					case 'fundingproject': ?>
						<span class="invest_error <?php if ($current_error != "min") { ?>hidden<?php } ?>" id="invest_error_min">Vous devez investir au moins <?php echo $temp_min_part; ?> &euro;.</span>
						<span class="invest_error <?php if ($current_error != "max") { ?>hidden<?php } ?>" id="invest_error_max">Vous ne pouvez pas investir plus de <?php echo $max_part_value; ?> &euro;.</span>
					<?php
					break;

					case 'fundingdevelopment':
					case 'fundingdonation': ?>
						<span class="invest_error <?php if ($current_error != "min") { ?>hidden<?php } ?>" id="invest_error_min">Le montant minimal de soutien est de <?php echo $temp_min_part; ?> &euro;.</span>
						<span class="invest_error <?php if ($current_error != "max") { ?>hidden<?php } ?>" id="invest_error_max">Vous ne pouvez pas soutenir avec plus de <?php echo $max_part_value; ?> &euro;.</span>
						<span class="invest_error <?php if ($current_error != "reward_remaining") { ?>hidden<?php } ?>" id="invest_error_reward_remaining">La contrepartie que vous avez choisi n'est plus disponible.</span>
						<span class="invest_error <?php if ($current_error != "reward_insufficient") { ?>hidden<?php } ?>" id="invest_error_reward_insufficient">Vous devez donner plus pour obtenir cette contrepartie.</span>
					<?php
					break;
				} ?>
						
				<span class="invest_error <?php if ($current_error != "interval") { ?>hidden<?php } ?>" id="invest_error_interval">Merci de ne pas laisser moins de <?php echo $min_value; ?>&euro; &agrave; investir.</span>
				<span class="invest_error <?php if ($current_error != "integer") { ?>hidden<?php } ?>" id="invest_error_integer">Le montant que vous pouvez investir doit &ecirc;tre entier.</span>
				<span class="invest_error <?php if ($current_error != "general") { ?>hidden<?php } ?>" id="invest_error_general">Le montant saisi semble comporter une erreur.</span>
				<span class="invest_success hidden" id="invest_success_message" class="button">
					<?php if ($campaign->funding_type()=="fundingdonation"): ?>
                    Vous vous appr&ecirc;tez &agrave; donner <strong><span id="invest_show_amount"></span>&euro;</strong> en &eacute;change de : <strong><span id="invest_show_reward"></span></strong>.<br/><br/>
					<?php endif; ?>
					Gr&acirc;ce à vous, nous serons <?php echo (ypcf_get_backers() + 1); ?> &agrave; soutenir le projet. La somme atteinte sera de <span id="invest_success_amount"></span>&euro;.
				</span>
		
				<div class="invest_step1_conditions">
				<?php switch ($campaign->funding_type()) {
					case "fundingdonation":
						echo wpautop( $edd_options['message_before_donation'] );
					break;
					default:
						echo wpautop( $edd_options['contract'] );
					break;
				} ?>
				</div>
		
				<br />

				<center>
					<?php switch ($campaign->funding_type()) {
						case "fundingdonation": ?>
							<input type="hidden" name="invest_type" value="user" />
							<input type="submit" value="Confirmer mon don" class="button" />
						<?php
						break;
					
						default: 
							$current_user = wp_get_current_user();
							$api_user_id = BoppLibHelpers::get_api_user_id($current_user->ID);
							$organisations_list = BoppUsers::get_organisations_by_role($api_user_id, BoppLibHelpers::$organisation_creator_role['slug']);
							?>
							<input type="submit" value="Investir" class="button" />
							<select name="invest_type">
								<option value="user">En mon nom (personne physique)</option>
								<?php if (count($organisations_list) > 0): ?>
									<?php foreach ($organisations_list as $organisation_item): ?>
										<option value="<?php echo $organisation_item->organisation_wpref; ?>">Pour <?php echo $organisation_item->organisation_name; ?></option>
									<?php endforeach; ?>
									<option value="new_organisation">Pour une nouvelle organisation (personne morale)...</option>
								<?php else: ?>
									<option value="new_organisation">Pour une organisation (personne morale)...</option>
								<?php endif; ?>
							</select>
						<?php
						break;
					} ?>
				</center>
			</div>
		
		</form>
		<br /><br />
		<div class="align-center mangopay-image"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/powered_by_mangopay.png" alt="Mangopay" /></div>
	    
		
	<?php else: ?>
		Il n&apos;est plus possible d&apos;investir sur ce <a href="<?php echo get_permalink($campaign->ID); ?>">projet</a> !
	<?php endif; ?>
	
<?php }