<?php
function page_resume_lightboxes(){
    echo do_shortcode('[yproject_statsadvanced_lightbox]');
}

function print_resume_page()
{
    global $campaign_id, $campaign, $post_campaign,
           $WDGAuthor,
           $is_admin, $is_author;

    global $stats_views, $stats_views_today;
    global $vote_results, $nb_jcrois, $nb_votes, $nb_invests;

    global $status, $collecte_or_after, $vote_or_after, $preview_or_after, $validated_or_after;
	
	$WDGUser_current = WDGUser::current();
	$WDGUser_author = new WDGUser( $campaign->post_author() );
	$campaign_organization = $campaign->get_organization();
	$wdg_organization = new WDGOrganization($campaign_organization->wpref);

    ?>
    <div class="head"><?php _e("Vue d'ensemble", 'yproject'); ?></div>
    <div id="status-list">
        <?php
        $status_list = ATCF_Campaign::get_campaign_status_list();
        $nb_status = count($status_list)-2;
        $i=1; ?>
        <div class="perso <?php if($status==ATCF_Campaign::$campaign_status_preparing){echo ' preparing ';}?>"></div>
        <?php foreach ($status_list as $status_key => $name): ?>
			<?php if ( 
					( $status_key != ATCF_Campaign::$campaign_status_preview )
					&& ( 
						( $status == ATCF_Campaign::$campaign_status_archive && $status_key == ATCF_Campaign::$campaign_status_archive )
						|| 
						( $status != ATCF_Campaign::$campaign_status_archive && $status_key == ATCF_Campaign::$campaign_status_closed )
						|| 
						( $status_key != ATCF_Campaign::$campaign_status_closed && $status_key != ATCF_Campaign::$campaign_status_archive )
						) ): ?>
            <div class="status
                    <?php   if($i==1){echo "begin ";}
                            if($i==$nb_status){echo "end ";}?>"
                   <?php if($status_key==$status){echo 'id="current"';}?>>
                <div class="line
                    <?php   if($i==1){echo "begin ";}
                            if($i==$nb_status){echo "end ";}
                            if($i>$nb_status){echo "none ";}?>">

                </div>
                <?php
                switch ($status_key){
                    case ATCF_Campaign::$campaign_status_validated:
                        echo '<div class="linetoright"></div>';
                        break;
                    case ATCF_Campaign::$campaign_status_preview:
                        echo '<div class="linetoboth"></div>';
                        break;
                    case ATCF_Campaign::$campaign_status_vote:
                        echo '<div class="linetoleft"></div>';
                        break;
                }
                ?>
                <div class="icon-etape">

                </div>
                <div class="name">
                    <?php echo $name.'<br/>';
                    switch ($status_key){
                        case ATCF_Campaign::$campaign_status_preparing:
                            DashboardUtility::get_infobutton("R&eacute;sumez votre projet et transmettez les informations n&eacute;cessaires au comit&eacute; de s&eacute;lection.",true);
                            break;
                        case ATCF_Campaign::$campaign_status_validated:
                            DashboardUtility::get_infobutton("Nous vous conseillons sur votre campagne de communication et la pr&eacute;sentation de votre projet.",true);
                            break;
                        case ATCF_Campaign::$campaign_status_preview:
                            DashboardUtility::get_infobutton("L'avant-première permet de faire d&eacute;couvrir votre projet sur le site. Cette &eacute;tape est facultative",true);
                            break;
                        case ATCF_Campaign::$campaign_status_vote:
                            DashboardUtility::get_infobutton("Testez votre communication et &eacute;valuez votre capacit&eacute; à f&eacute;d&eacute;rer vos cercles d'investisseurs.",true);
                            break;
                        case ATCF_Campaign::$campaign_status_collecte:
                            DashboardUtility::get_infobutton("Mobilisez des investisseurs pour atteindre votre seuil de validation de campagne et le d&eacute;passer !",true);
                            break;
                        case ATCF_Campaign::$campaign_status_funded:
                            DashboardUtility::get_infobutton("Versez les royalties &agrave; vos investisseurs en fonction de votre chiffre d'affaires et tenez-les inform&eacute;s des avanc&eacute;es de votre projet.",true);
                            break;
                        case ATCF_Campaign::$campaign_status_closed:
                        case ATCF_Campaign::$campaign_status_archive:
                            DashboardUtility::get_infobutton("Votre contrat est arriv&eacute; &agrave; son terme.",true);
                            break;
                    }
                    ?>
                </div>
            </div>
			<?php $i++; ?>
			<?php endif; ?>
		<?php endforeach; ?>
    </div>

    <?php if($preview_or_after){ ?>
    <div class="tab-content" id="stats-tab">
        <div id="block-stats" class="large-block">
            <div class="data-blocks">

                <?php if($status==ATCF_Campaign::$campaign_status_preview){ ?>
                    <div id="stats-prepare">
                        <div class="half-card">
                            <div class="stat-little-number-top">Votre projet a &eacute;t&eacute; vu</div>
                            <div class="stat-big-number"><?php echo $stats_views[0]['views'];
                                if ($stats_views[0]['views']==null){echo "-";}?></div>
                            <div class="stat-little-number">fois au total</div>
                        </div><!--
                        --><div class="half-card">
                            <div class="stat-little-number-top">Dont</div>
                            <div class="stat-big-number"><?php echo $stats_views_today[0]['views'];
                                if ($stats_views_today[0]['views']==null){echo "-";}?></div>
                            <div class="stat-little-number">Vues aujourd'hui</div>
                        </div>
                    </div>

                <?php }
                else if($status==ATCF_Campaign::$campaign_status_vote){ ?>
                    <div id="stats-vote">
                        <div class="quart-card">
                            <div class="stat-big-number"><?php echo $nb_votes?></div>
                            <div class="stat-little-number">sur <?php echo ATCF_Campaign::$voters_min_required?> requis</div>
                            <div class="details-card">
                                <strong><?php echo $nb_votes?></strong> personne<?php if($nb_votes>1){echo 's ont';}else{echo ' a';} echo ' voté';?>
                            </div>
                        </div><!--
                        --><div class="quart-card">
                            <canvas id="canvas-vertical-bar-block" width="160" height="160"></canvas><br/>
                            <div class="details-card">
                                En moyenne, les votants notent <strong><?php echo $vote_results[ 'rate_project_average' ]; ?></strong>
                            </div>
                        </div><!--
                        --><div class="quart-card">
							<?php
							$big_number_class = '';
							if ($vote_results['sum_invest_ready'] > 10000) {
								$big_number_class = 'less-big';
							}
							?>
                            <div class="stat-big-number <?php echo $big_number_class; ?>"><?php echo $vote_results['sum_invest_ready'].'&euro;'?></div>
                            <div class="stat-little-number">sur <?php echo $campaign->vote_invest_ready_min_required() ?> &euro; recommand&eacute;s</div>
                            <div class="details-card">
                                <strong><?php echo $vote_results['sum_invest_ready']?></strong>&euro; d'intentions d'investissement
                            </div>
                        </div><!--
                        --><div class="quart-card">
                            <div class="stat-big-number"><?php echo $campaign->time_remaining_str();?><br/></div>
                            <div class="stat-little-number">Avant la fin du vote</div>
                            <div class="details-card"><?php echo $campaign->time_remaining_fullstr()?></div>
                        </div>
                    </div>

                <?php }
                else if($status==ATCF_Campaign::$campaign_status_collecte){ ?>
                    <div id="stats-invest">
                        <div class="quart-card">
							<?php
							$big_number_class = '';
							if ($campaign->current_amount(false) > 10000) {
								$big_number_class = 'less-big';
							}
							?>
                            <div class="stat-big-number <?php echo $big_number_class; ?>"><?php echo $campaign->current_amount(); ?></div>
                            <div class="stat-little-number">sur <?php echo $campaign->minimum_goal(false)/1 ?> &euro; requis</div>
                            <div class="details-card">
                                <strong><?php echo $campaign->current_amount()?></strong> investis par
                                <strong><?php echo $nb_invests?></strong> personne<?php if($nb_invests>1){echo 's';}?>
                            </div>
                        </div><!--
                        --><div class="half-card">
                            <div class="ajax-investments-load" id="ajax-invests-graph-load" style="text-align: center;" data-value="<?php echo $campaign->ID?>">
                                <div id="ajax-graph-loader-img" >
                                    <img src="<?php echo get_stylesheet_directory_uri() ?>/images/loading.gif" alt="chargement" />
                                    <p style="font-style:italic">Chargement des donn&eacute;es d'investissement,<br/>cela peut prendre un peu de temps</p></div>
                            </div>
                            <canvas id="canvas-line-block" width="400" height="200" hidden></canvas>
                        </div><!--
                        --><div class="quart-card">
                            <div class="stat-big-number"><?php echo $campaign->time_remaining_str();?><br/></div>
                            <div class="stat-little-number">Avant la fin de collecte</div>
                            <div class="details-card"><?php echo $campaign->time_remaining_fullstr()?></div>
                        </div>
                    </div>

                <?php } else if ( $status == ATCF_Campaign::$campaign_status_funded || $status == ATCF_Campaign::$campaign_status_archive ){ ?>
                    <div id="stats-funded">
                        <div class="half-card">
							<?php
							$big_number_class = '';
							if ($campaign->current_amount(false) > 10000) {
								$big_number_class = 'less-big';
							}
							?>
                            <div class="stat-big-number <?php echo $big_number_class; ?>"><?php echo $campaign->current_amount()?></div>
                            <div class="stat-little-number">récoltés sur <?php echo $campaign->minimum_goal(false)/1 ?> &euro;</div>
                            <div class="details-card">
                                <strong><?php echo $campaign->current_amount()?></strong> investis par
                                <strong><?php echo $nb_invests?></strong> personne<?php if($nb_invests>1){echo 's';}?>
                            </div>
                        </div><!--
                        --><div class="half-card">
                            <div class="ajax-investments-load" id="ajax-invests-graph-load" style="text-align: center;" data-value="<?php echo $campaign->ID?>">
                                <div id="ajax-graph-loader-img" >
                                    <img src="<?php echo get_stylesheet_directory_uri() ?>/images/loading.gif" alt="chargement" />
                                    <p style="font-style:italic">Chargement des donn&eacute;es d'investissement,<br/>cela peut prendre un peu de temps</p></div>
                            </div>
                            <canvas id="canvas-line-block" width="400" height="200" hidden></canvas>
                        </div>
                    </div>
                <?php } ?>

            </div>

            <div class="list-button">
                <a href="#statsadvanced" class="wdg-button-lightbox-open button" data-lightbox="statsadvanced"><i class="fa fa-line-chart"></i>  Statistiques d&eacute;taill&eacute;es</a>
            </div>
			<?php if ( $status == ATCF_Campaign::$campaign_status_vote ): ?>
            <script type="text/javascript">
                jQuery(document).ready( function($) {
                    var ctxBar = $("#canvas-vertical-bar-block").get(0).getContext("2d");
					var nStepsBar = Math.max(Math.max(Math.max(Math.max(Math.max(0, <?php echo $vote_results['rate_project_list'][1]; ?>), <?php echo $vote_results['rate_project_list'][2]; ?>), <?php echo $vote_results['rate_project_list'][3]; ?>), <?php echo $vote_results['rate_project_list'][4]; ?>), <?php echo $vote_results['rate_project_list'][5]; ?>);
					var barData = {
						labels: [ "1", "2", "3", "4", "5" ],
						datasets: [{
							fillColor: "#FE494C",
							strokeColor: "#FE494C",
							data: [
								<?php echo $vote_results[ 'rate_project_list' ][ '1' ]; ?>,
								<?php echo $vote_results[ 'rate_project_list' ][ '2' ]; ?>,
								<?php echo $vote_results[ 'rate_project_list' ][ '3' ]; ?>,
								<?php echo $vote_results[ 'rate_project_list' ][ '4' ]; ?>,
								<?php echo $vote_results[ 'rate_project_list' ][ '5' ]; ?>
							]
						}]
					};
					var barOptions = {
						scaleOverride: true,
						scaleSteps: ( nStepsBar < 10 ) ? nStepsBar : 10,
						scaleStepWidth: 1,
						scaleStartValue: 0,
						scaleShowLabels: ( nStepsBar < 10 ),
						pointDot: false
					};
					var canvasBar = new Chart( ctxBar ).Bar( barData, barOptions );
                });
            </script>
			<?php endif; ?>
        </div>
    </div>
    <?php } ?>

    <div class="tab-content" id="next-status-tab">
        <?php if (	$status == ATCF_Campaign::$campaign_status_preparing
					|| $status == ATCF_Campaign::$campaign_status_validated
					|| $status == ATCF_Campaign::$campaign_status_preview
					|| ($status == ATCF_Campaign::$campaign_status_vote && $campaign->end_vote_remaining()<=0)) { ?>
        <h2 style='text-align:center'><?php _e("Pr&ecirc;t(e) pour la suite ?", 'yproject'); ?></h2>

        <form method="POST" action="<?php echo admin_url( 'admin-post.php?action=change_project_status'); ?>">
            <input type="hidden" name="campaign_id" value="<?php echo $campaign_id;?>">
            <ul>
                <?php if ($status == ATCF_Campaign::$campaign_status_preparing): ?>
					<p id="desc-preview">
						<?php _e("Votre projet doit maintenant &ecirc;tre valid&eacute; par le Comit&eacute; de s&eacute;lection.", 'yproject'); ?><br />
						<?php _e("&Ecirc;tes-vous pr&ecirc;t(e) &agrave; le pr&eacute;senter ?", 'yproject'); ?>
					</p>

                    <li>
						<label>
							<?php
							$preparing_has_filled_desc = ( $campaign->get_validation_step_status( 'has_filled_desc' ) == "on" );
							$is_waiting_for_comitee = $preparing_has_filled_desc;
							?>
							<input type="checkbox" class="checkbox-next-status" name="validation-step-has-filled-desc" <?php if (!$WDGUser_current->is_admin()) { ?>disabled<?php } ?> <?php checked( $preparing_has_filled_desc ); ?>>
                            J'ai compl&eacute;t&eacute; la description de mon projet et de ses impacts
						</label>
                    </li>
                    <li>
						<label>
							<?php
							$preparing_user_is_complete = ypcf_check_user_is_complete($campaign->post_author());
							$is_waiting_for_comitee &= $preparing_user_is_complete;
							?>
							<input type="checkbox" class="checkbox-next-status" disabled <?php checked( $preparing_user_is_complete ); ?>>
                            L'auteur du projet, <?php echo $WDGUser_author->wp_user->get('display_name'); ?>, a rempli ses informations personnelles
						</label>
                    </li>
                    <li>
						<label>
							<?php
							$preparing_org_is_complete = $wdg_organization->has_filled_invest_infos();
							$is_waiting_for_comitee &= $preparing_org_is_complete;
							?>
							<input type="checkbox" class="checkbox-next-status" disabled <?php checked( $preparing_org_is_complete ); ?>>
                            J'ai compl&eacute;t&eacute; les informations sur l'organisation qui porte le projet
						</label>
                    </li>
                    <li>
						<label>
							<?php
							$preparing_finance_ready = ( $campaign->get_validation_step_status( 'has_filled_finance' ) == "on" );
							$is_waiting_for_comitee &= $preparing_finance_ready;
							?>
							<input type="checkbox" class="checkbox-next-status" name="validation-step-has-filled-finance" <?php if (!$WDGUser_current->is_admin()) { ?>disabled<?php } ?> <?php checked( $preparing_finance_ready ); ?>>
                            J'ai pr&eacute;cis&eacute; mon besoin de financement
						</label>
                    </li>
                    <li>
						<label>
							<?php
							$preparing_parameters_validated = ( $campaign->get_validation_step_status( 'has_filled_parameters' ) == "on" );
							$is_waiting_for_comitee &= $preparing_parameters_validated;
							?>
							<input type="checkbox" class="checkbox-next-status" name="validation-step-has-filled-parameters" <?php if (!$WDGUser_current->is_admin()) { ?>disabled<?php } ?> <?php checked( $preparing_parameters_validated ); ?>>
                            J'ai valid&eacute; les param&egrave;tres de ma lev&eacute;e de fonds avec un membre de l'équipe WE DO GOOD
						</label>
                    </li>
                    <li>
						<label>
							<?php
							$preparing_signed_order = ( $campaign->get_validation_step_status( 'has_signed_order' ) == "on" );
							$is_waiting_for_comitee &= $preparing_signed_order;
							?>
							<input type="checkbox" class="checkbox-next-status" name="validation-step-has-signed-order" <?php if (!$WDGUser_current->is_admin()) { ?>disabled<?php } ?> <?php checked( $preparing_signed_order ); ?>>
                            J'ai exprim&eacute; mon engagement en renvoyant le bon de commande sign&eacute;
						</label>
                    </li>
					
					
                <?php elseif ($status == ATCF_Campaign::$campaign_status_validated && !$campaign->skip_vote()): ?>
                    <p id="desc-preview">
						<?php _e("Il est temps maintenant de pr&eacute;parer la publication de votre projet.", 'yproject'); ?><br />
						<?php _e("Vous devrez r&eacute;unir au moins :", 'yproject'); ?><br />
						<?php _e("- 50 votants avec des intentions d'investissement", 'yproject'); ?><br />
						<?php _e("- 50% de votes positifs", 'yproject'); ?><br />
						<?php _e("- 50% d'intentions d'investissement par rapport &agrave; votre objectif", 'yproject'); ?><br />
						<br />
						<?php _e("&Ecirc;tes-vous pr&ecirc;t &agrave; le publier ?", 'yproject'); ?><br />
					</p>
					
                    <li>
						<label>
							<?php
							$validated_documents_sent = $wdg_organization->has_sent_all_documents();
							?>
							<input type="checkbox" class="checkbox-next-status" disabled <?php checked($validated_documents_sent); ?>>
                            J'ai transmis les documents d'authentification de mon organisation
						</label>
                    </li>
                    <li>
						<label>
							<?php
							$validated_org_authentified = $wdg_organization->is_registered_lemonway_wallet();
							?>
							<input type="checkbox" class="checkbox-next-status" disabled <?php checked($validated_org_authentified); ?>>
                            L'organisation est authentifiée par le prestataire de paiement
							<?php DashboardUtility::get_infobutton("Le prestataire de paiement s&eacute;curis&eacute; doit valider votre compte. Cela peut prendre quelques jours.", true); ?>
						</label>
                    </li>
                    <li>
						<label>
							<?php
							$validated_presentation_complete = ( $campaign->get_validation_step_status( 'has_filled_presentation' ) == "on" );
							?>
							<input type="checkbox" class="checkbox-next-status" name="validation-step-has-filled-presentation" <?php if (!$WDGUser_current->is_admin()) { ?>disabled<?php } ?> <?php checked($validated_presentation_complete); ?>>
                            J'ai compl&eacute;t&eacute; la pr&eacute;sentation de mon projet
						</label>
                    </li>
                    <li>
						<label>
							<?php
							$validated_vote_authorized = $campaign->can_go_next_status();
							?>
							<input type="checkbox" class="checkbox-next-status" disabled <?php checked($validated_vote_authorized); ?>>
                            WE DO GOOD a autoris&eacute; la publication de mon projet en vote
						</label>
                    </li>
					
                    <li>
						<label>
							<input type="checkbox" class="checkbox-next-status">
                            J'ai list&eacute; tous les contacts mobilisables pour ma lev&eacute;e de fonds avec toute mon &eacute;quipe
						</label>
                    </li>
                    <li>
						<label>
							<input type="checkbox" class="checkbox-next-status">
                            J'ai pr&eacute;par&eacute; des mails &agrave; envoyer à l'ensemble de mes contacts et des publications pour les r&eacute;seaux sociaux
						</label>
                    </li>
                    <li>
						<label>
							<input type="checkbox" class="checkbox-next-status">
                            J'ai planifi&eacute; des rencontres et des prises de contact pour parler de mon projet et de ma campagne &agrave; mes proches et &agrave; mon r&eacute;seau
						</label>
                    </li>
					
					<li>
						<label>
							Nombre de jours du vote :
							<input type="number" id="innbdayvote" name="innbdayvote" min="10" max="30" value="30" style="width: 40px;">
						</label>
						Fin du vote : <span id="previewenddatevote"></span>
						<?php //TODO : choisir l'heure de fin de vote ?>
					</li>


                <?php elseif ( $status == ATCF_Campaign::$campaign_status_vote || ( $status == ATCF_Campaign::$campaign_status_validated && $campaign->skip_vote() ) ): ?>
                    <p id="desc-preview">
						<?php _e("Il est temps maintenant de passer aux choses s&eacute;rieuses.", 'yproject'); ?>
						<?php _e("&Ecirc;tes-vous pr&ecirc;t &agrave; lancer votre lev&eacute;e de fonds ?", 'yproject'); ?>
					</p>
					<li>
						<label>
							<?php
							$vote_can_go_next = $campaign->can_go_next_status();
							?>
							<input type="checkbox" class="checkbox-next-status" id="cbcannext" disabled <?php checked( $vote_can_go_next ); ?>>
							WE DO GOOD a autoris&eacute; mon passage en lev&eacute;e de fonds
						</label>
					</li>
                    <li>
						<label>
							<input type="checkbox" class="checkbox-next-status">
                            Je suis pr&ecirc;t &agrave; devenir le premier investisseur de mon projet 
						</label>
                    </li>
                    <li>
						<label>
							<input type="checkbox" class="checkbox-next-status">
                            J'ai pr&eacute;par&eacute; des mails &agrave; envoyer &agrave; l'ensemble de mes contacts et des publications pour les r&eacute;seaux sociaux
						</label>
                    </li>
                    <li>
						<label>
							<input type="checkbox" class="checkbox-next-status">
                            J'ai planifi&eacute; des rencontres et des prises de contact pour parler de mon projet et de ma campagne &agrave; mes proches et &agrave; mon r&eacute;seau
						</label>
                    </li>
                    <li>
						<label>
							Nombre de jours de la collecte :
                            <input type="number" id="innbdaycollecte" name="innbdaycollecte" min="1" max="60" value="30" style="width: 40px;">
						</label>
                        Fin de la collecte : <span id="previewenddatecollecte"></span>
                    </li>
                    <li>
                        <label>
							La lev&eacute;e de fonds se terminera &agrave; :
                            <input type="number" id="inendh" name="inendh" min="0" max="23" value="12" style="width: 40px;">h
                            <input type="number" id="inendm" name="inendm" min="0" max="59" value="00" style="width: 40px;">
						</label>
						<?php DashboardUtility::get_infobutton("Veillez &agrave; d&eacute;finir l'heure de fin &agrave; un moment o&ugrave; vous pourrez toucher des investisseurs et encore mener des action de communication. Nous vous conseillons 22h.",true); ?>
                    </li>
				<?php endif; ?>
            </ul>

            <div class="list-button">
				<?php if ($status == ATCF_Campaign::$campaign_status_preparing): ?>
					<?php if ( $WDGUser_current->is_admin() ): ?>
					<?php DashboardUtility::get_admin_infobutton( TRUE ); ?>
					<button type="submit" name="validation-next-save" value="1" id="submit-go-next-status-admin" class="button admin-theme">Enregistrer le statut</button><br /><br />
					<?php endif; ?>
					
					<?php if ( $is_waiting_for_comitee ): ?>
						Dossier en attente de validation par le Comit&eacute; de s&eacute;lection.<br />
						<?php if ( $WDGUser_current->is_admin() ): ?>
							<?php DashboardUtility::get_admin_infobutton( TRUE ); ?>
							<button type="submit" name="validation-next-validate" value="1" id="submit-go-next-status-admin" class="button admin-theme">Valider le projet</button>
						<?php endif; ?>
					<?php endif; ?>
							
				<?php elseif ( $status == ATCF_Campaign::$campaign_status_validated && !$campaign->skip_vote() ): ?>
					<?php if ( $WDGUser_current->is_admin() ): ?>
					<?php DashboardUtility::get_admin_infobutton( TRUE ); ?>
					<button type="submit" name="validation-next-save" value="1" id="submit-go-next-status-admin" class="button admin-theme">Enregistrer le statut</button><br /><br />
					<?php endif; ?>
					
                    <input type="submit" value="Publier mon projet en vote !" class="button" id="submit-go-next-status">
					
                <?php elseif ( $status == ATCF_Campaign::$campaign_status_vote || ( $status == ATCF_Campaign::$campaign_status_validated && $campaign->skip_vote() ) ): ?>
                    <input type="submit" value="Lancer ma lev&eacute;e de fonds !" class="button" id="submit-go-next-status">
                <?php endif; ?>
            </div>

            <input type="hidden" name="next_status" value="2" id="next-status-choice">
        </form>
        <?php } ?>

        <?php if ($is_admin){ ?>
        <form action="" id="statusmanage_form" class="db-form" data-action="save_project_status">
            <hr class="form-separator"/>
            <?php
            DashboardUtility::create_field(array(
                "id"			=> "new_campaign_status",
                "type"			=> "select",
                "label"			=> "Changer l'&eacute;tape actuelle de la campagne",
                "value"			=> $status,
                "editable"		=> $is_admin,
                "admin_theme"	=> $is_admin,
                "visible"		=> $is_admin,
                "options_id"	=> array_keys($status_list),
                "options_names"	=> array_values($status_list),
                "warning"		=> true
            ));

            DashboardUtility::create_field(array(
                "id"			=> "new_can_go_next_status",
                "type"			=> "check",
                "label"			=> "Autoriser &agrave; passer &agrave; l'&eacute;tape suivante",
                "value"			=> $campaign->can_go_next_status(),
                "editable"		=> $is_admin,
                "admin_theme"	=> $is_admin,
                "visible"		=> $is_admin && $validated_or_after,
                "placeholder"	=> "http://....."
            ));

            DashboardUtility::create_save_button("statusmanage-form",$is_admin);
            ?>
        </form>
		<?php } ?>
		
		
		<?php if ( $is_admin && $status == ATCF_Campaign::$campaign_status_archive ): ?>

			<form method="POST" action="<?php echo admin_url( 'admin-post.php?action=refund_investors'); ?>" class="align-center admin-theme-block">

				<br />
				<input type="hidden" name="campaign_id" value="<?php echo $campaign_id; ?>" />
				<button type="submit" class="button"><?php _e( "Rembourser les investisseurs", 'yproject' ); ?></button>
				<br /><br />

			</form>
			<br /><br />

		<?php endif; ?>
    </div>
    <?php

}