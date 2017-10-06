<?php
global $can_modify, $disable_logs, $campaign_id, $campaign, $post_campaign, $WDGAuthor, $WDGUser_current, $organization_obj, $is_admin, $is_author, $last_mandate_status;
$mandate_conditions = $campaign->mandate_conditions();

$saved_mandates_list = $organization_obj->get_lemonway_mandates();
$last_mandate_status = '';
if ( !empty( $saved_mandates_list ) ) {
	$last_mandate = end( $saved_mandates_list );
	$last_mandate_status = $last_mandate[ "S" ];
}
?>

<h2><?php _e('Autorisation de pr&eacute;l&egrave;vement', 'yproject'); ?></h2>

<?php if ( $last_mandate_status != 5 && $last_mandate_status != 6 ): ?>
	<?php if ( $is_admin ): ?>
		<form action="" id="forcemandate_form" class="db-form" data-action="save_project_force_mandate">
			<?php DashboardUtility::create_field( array(
				"id"			=> "new_force_mandate",
				"type"			=> "select",
				"label"			=> __( "Forcer l'entrepreneur &agrave; signer l'autorisation de pr&eacute;l&egrave;vement ?", 'yproject' ),
				"value"			=> $campaign->is_forced_mandate(),
				"editable"		=> $is_admin,
				"admin_theme"	=> $is_admin,
				"visible"		=> $is_admin,
				"options_id"	=> array( 0, 1 ),
				"options_names"	=> array( 
					__( "Non", 'yproject' ),
					__( "Oui", 'yproject' )
				)
			) ); ?>

			<?php DashboardUtility::create_field(array(
				"id"			=> "new_mandate_conditions",
				"type"			=> "editor",
				"label"			=> __( "Conditions contractuelles", 'yproject' ),
				"value"			=> $mandate_conditions,
				"editable"		=> $is_admin,
				"admin_theme"	=> $is_admin,
				"visible"		=> $is_admin,
			)); ?>

			<?php DashboardUtility::create_save_button( "forcemandate-form", $is_admin ); ?>
		</form>

	<?php elseif ( !empty( $mandate_conditions ) ) : ?>

		<strong><?php _e( "Conditions contractuelles pour la signature du mandat de pr&eacute;l&egrave;vement", 'yproject' ) ?></strong><br />
		<?php echo $mandate_conditions; ?><br /><br />

	<?php endif; ?>
<?php endif; ?>


<?php 
//Si il n'y a pas de RIB enregistré, demander d'éditer l'organisation
//TODO : permettre l'édition du RIB directement ici
$keep_going = true;
?>
<?php if ( !$organization_obj->has_saved_iban() ): ?>
	<?php
	$keep_going = false;
	$page_edit_orga = get_page_by_path('editer-une-organisation');
	?>
	<?php _e( "Afin de signer votre autorisation de pr&eacute;l&egrave;vement, vous devez au pr&eacute;alable renseigner le RIB de l'organisation.", 'yproject' ); ?><br />
	<p class="align-center"><a class="button red" href="<?php echo get_permalink($page_edit_orga->ID) .'?orga_id='.$organization_obj->get_wpref(); ?>"><?php _e('Editer', 'yproject'); ?></a></p><br /><br />
	<button class="button disabled"><?php _e( "Signer l'autorisation de pr&eacute;l&egrave;vement automatique", 'yproject' ); ?></button>

<?php endif; ?>

<?php
//Si il y a un RIB enregistré
?>
<?php if ( $keep_going ): ?>
	<?php
	//Récupérer la liste des mandats liés au wallet de l'organisation
	//Si il n'y en a pas : enregistrer un mandat lié
	?>
	<?php
	$organization_obj->register_lemonway();
	if ( empty( $saved_mandates_list ) ) {
		$keep_going = false;
		if ( !$organization_obj->add_lemonway_mandate() ) {
			$page_edit_orga = get_page_by_path('editer-une-organisation');
			echo LemonwayLib::get_last_error_message(); ?>
			<a class="button red" href="<?php echo get_permalink($page_edit_orga->ID) .'?orga_id='.$organization_obj->get_wpref(); ?>"><?php _e('Editer', 'yproject'); ?></a><br /><br />
			<button class="button disabled"><?php _e( "Signer l'autorisation de pr&eacute;l&egrave;vement automatique", 'yproject' ); ?></button>
			<?php
		} else {
			_e( "Cr&eacute;ation de mandat en cours", 'yproject' );
		}
	}
	?>
<?php endif; ?>

<?php if ( $keep_going ): ?>
	<?php
	//Récupérer le dernier de la liste, vérifier le statut
	/**
	 * 0 	non validé
	 * 5 	utilisable avec prélèvement effectif dans un délai de 6 jours ouvrés bancaire
	 * 6 	utilisable avec prélèvement effectif dans un délai de 3 jours ouvrés bancaire
	 * 8 	désactivé
	 * 9 	rejeté
	 */
	?>
	<?php if ( $last_mandate_status == 0 ): //Si 0, proposer de signer ?>
		<?php $phone_number = $WDGUser_current->wp_user->get('user_mobile_phone'); ?>

		<?php 
		//Indication pour rappeler qu'ils se sont engagés dans le contrat à autoriser les prélévements automatiques
		?>
		<?php if ( $campaign->is_forced_mandate() ): ?>
			<?php _e( "Selon votre contrat, vous vous &ecirc;tes engag&eacute; &agrave; signer l'autorisation de pr&eacute;l&egrave;vement automatique.", 'yproject' ); ?><br /><br />
		<?php endif; ?>


		<?php if ( empty( $phone_number ) ): ?>
			<?php _e( "Afin de signer l'autorisation de pr&eacute;l&eacute;vement automatique, merci de renseigner votre num&eacute;ro de t&eacute;l&eacute;phone mobile dans votre compte utilisateur.", 'yproject' ); ?><br /><br />

		<?php elseif ( !$organization_obj->is_registered_lemonway_wallet() ): ?>
			<?php _e( "L'organisation doit &ecirc;tre authentifi&eacute;e par notre prestataire de paiement afin de pouvoir signer l'autorisation de pr&eacute;l&egrave;vement automatique.", 'yproject' ); ?><br /><br />

		<?php else: ?>
		<form action="<?php echo admin_url( 'admin-post.php?action=organization_sign_mandate'); ?>" method="post" class="align-center">
			<input type="hidden" name="organization_id" value="<?php echo $organization_obj->get_wpref(); ?>" />
			<button type="submit" class="button red"><?php _e( "Signer l'autorisation de pr&eacute;l&egrave;vement automatique", 'yproject' ); ?></button>
		</form>
		<?php endif; ?>

	<?php elseif ( $last_mandate_status == 5 || $last_mandate_status == 6 ): //Si 5 ou 6, afficher que OK ?>
		<?php _e( "Merci d'avoir signé l'autorisation de pr&eacute;l&egrave;vement automatique.", 'yproject' ); ?>
		

		<?php if ( $is_admin ): ?>
		<br /><br />
		<form class="db-form" data-action="pay_with_mandate">
			<div class="field admin-theme">

				<?php
				DashboardUtility::create_field(array(
					'id'			=> 'pay_with_mandate_amount_for_organization',
					'type'			=> 'text',
					'label'			=> "Montant vers&eacute; sur le porte-monnaie de l'organisation",
					'suffix'		=> " &euro;",
                    "admin_theme"	=> true
				));
				?>
				<br />

				<?php
				DashboardUtility::create_field(array(
					'id'			=> 'pay_with_mandate_amount_for_commission',
					'type'			=> 'text',
					'label'			=> "Montant vers&eacute; en commission",
					'suffix'		=> " &euro;",
                    "admin_theme"	=> true
				));
				?>
				<br />
				
				<?php
				DashboardUtility::create_field( array(
					'id'			=> 'organization_id',
					'type'			=> 'hidden',
					'value'			=> $organization_obj->get_wpref()
				) );
				?>
				
				<?php DashboardUtility::create_save_button( "pay_with_mandate" ); ?>

			</div>
		</form>
		<?php endif; ?>

			

	<?php elseif ( $last_mandate_status == 8 ): //Si 8, demander de nous contacter ?>
		<?php _e( "L'autorisation de pr&eacute;l&egrave;vement automatique a &eacute;t&eacute; d&eacute;sactiv&eacute;e. Merci de nous contacter.", 'yproject' ); ?>

	<?php elseif ( $last_mandate_status == 9 ): //Si 9, demander de nous contacter ?>
		<?php _e( "L'autorisation de pr&eacute;l&egrave;vement automatique a &eacute;t&eacute; rejet&eacute;e. Merci de nous contacter.", 'yproject' ); ?>

	<?php endif; ?>
<?php endif; ?>

<br />
<br />
<br />