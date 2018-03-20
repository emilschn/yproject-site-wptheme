<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
global $organization_obj;
?>

<h2><?php _e( "Organisation", 'yproject' ); ?></h2>
<form id="orgainfo_form" class="ajax-db-form db-form v3 full center bg-white" data-action="save_project_organization">
	<ul class="errors">

	</ul>

	<?php
	// Gestion des organisations
	$organization_list_ids = array();
	$organization_list_names = array();
	$current_organization = $page_controler->get_campaign_organization();
	$organization_obj = $current_organization;
	$organizations_list = $page_controler->get_campaign_author()->get_organizations_list();
	if ($organizations_list) {
		foreach ($organizations_list as $organization_item) {
			array_push( $organization_list_ids, $organization_item->wpref );
			array_push( $organization_list_names, $organization_item->name );
		}
	}
	?>


	<?php if ( !empty( $organization_list_ids ) ): ?>
		<?php
		DashboardUtility::create_field( array(
			'id'			=> 'new_project_organization',
			'type'			=> 'select',
			'label'			=> "Organisation li&eacute;e au projet",
			'value'			=> $current_organization->get_wpref(),
			'options_id'	=> $organization_list_ids,
			'options_names'	=> $organization_list_names
		) );
		?>

		<!--bouton d'édition de l'organisation-->
		<a id="edit-orga-button" class="wdg-button-lightbox-open button red" data-lightbox="editOrga" style="display: none;">
			<?php _e("&Eacute;diter", "yproject"); echo '&nbsp;'.$current_organization->get_name(); ?>
		</a>
		<p id="save-mention" class="hidden"><?php _e("Veuillez enregistrer l'organisation choisie pour la lier à votre projet", "yproject"); ?></p>
		<?php DashboardUtility::create_save_button("orgainfo_form"); ?>

	<?php else: ?>
		<?php _e('Le porteur de projet n&apos;est li&eacute; &agrave; aucune organisation.', 'yproject'); ?>
		<input type="hidden" name="project-organization" value="" />
	<?php endif; ?>


	<!--bouton de création de l'organisation visible dans tous les cas -->
	<a id="btn-new-orga" class="wdg-button-lightbox-open button red" data-lightbox="newOrga"><?php _e("Cr&eacute;er une organisation","yproject") ?></a>               
	<br />
	<br />

</form> 
<?php
if ($current_organization!=null){
	ob_start();
	locate_template( array( 'pages/view/tableau-de-bord/tab-organization/lightbox-organization-edit.php' ), true );                  
	$lightbox_content = ob_get_clean();
	echo do_shortcode( '[yproject_widelightbox id="editOrga" scrolltop="1"]'.$lightbox_content.'[/yproject_widelightbox]' );
}
?>
<?php 
ob_start();
locate_template( array( 'pages/view/tableau-de-bord/tab-organization/lightbox-organization-new.php' ), true );
$lightbox_content = ob_get_clean();
echo do_shortcode( '[yproject_widelightbox id="newOrga" scrolltop="1"]'.$lightbox_content.'[/yproject_widelightbox]' );
?>

<?php
$msg_valid_changeOrga = __( "L'organisation a bien &eacute;t&eacute; li&eacute;e au projet", 'yproject' );
echo do_shortcode('[yproject_lightbox_cornered id="valid-changeOrga" scrolltop="1" msgtype="valid"]'.$msg_valid_changeOrga.'[/yproject_lightbox_cornered]' );

$msg_valid_newOrga = __( "Votre nouvelle organisation a bien &eacute;t&eacute; cr&eacute;&eacute;e", 'yproject' );
echo do_shortcode('[yproject_lightbox_cornered id="valid-newOrga" scrolltop="1" msgtype="valid"]'.$msg_valid_newOrga.'[/yproject_lightbox_cornered]' );

$msg_valid_editOrga = __( "Les informations ont bien &eacute;t&eacute; enregistr&eacute;es", 'yproject' );
echo do_shortcode('[yproject_lightbox_cornered id="valid-editOrga" scrolltop="1" msgtype="valid"]'.$msg_valid_editOrga.'[/yproject_lightbox_cornered]' );
?>