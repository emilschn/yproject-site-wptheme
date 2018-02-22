<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
global $organization_obj;
?>

<div class="db-form v3 center">
	<form id="orgainfo_form" class="ajax-db-form" data-action="save_project_organization">
		<ul class="errors">

		</ul>

		<?php
		// Gestion des organisations
		$str_organizations = '';
		$current_organization = $page_controler->get_campaign_organization();
		$organization_obj = $current_organization;
		$organizations_list = $page_controler->get_campaign_author()->get_organizations_list();
		if ($organizations_list) {
			foreach ($organizations_list as $organization_item) {
				$selected_str = ($organization_item->id == $current_organization->get_api_id()) ? 'selected="selected"' : '';
				$str_organizations .= '<option ' . $selected_str . ' value="'.$organization_item->wpref.'">' .$organization_item->name. '</option>';
			}
		}
		?>
		<label for="project-organization"><?php _e("Organisation li&eacute;e au projet"); ?> :</label>
		<?php if ($str_organizations != ''): ?>
			<span class="field-value" data-type="select" data-id="new_project_organization">
				<select name="project-organization" id="new_project_organization">
					<?php echo $str_organizations; ?>
				</select>
			</span>
			<!--bouton d'édition de l'organisation-->
			<a id="edit-orga-button" class="wdg-button-lightbox-open button" data-lightbox="editOrga" style="display: none;">
				<?php _e("&Eacute;diter", "yproject"); echo '&nbsp;'.$current_organization->get_name(); ?></a>
			<?php DashboardUtility::create_save_button("orgainfo_form"); ?>
			<p id="save-mention" class="hidden"><?php _e("Veuillez enregistrer l'organisation choisie pour la lier à votre projet", "yproject"); ?></p>
		<?php else: ?>
			<?php _e('Le porteur de projet n&apos;est li&eacute; &agrave; aucune organisation.', 'yproject'); ?>
			<input type="hidden" name="project-organization" value="" />
		<?php endif; ?>

		<!--bouton de création de l'organisation visible dans tous les cas -->
		<a id="btn-new-orga" class="wdg-button-lightbox-open button" data-lightbox="newOrga"><?php _e("Cr&eacute;er une organisation","yproject") ?></a>               
		<br />
		<br />

	</form> 
	<?php
	if ($current_organization!=null){
		ob_start();
		locate_template( array("projects/dashboard/informations/lightbox-organization-edit.php"), true );                  
		$lightbox_content = ob_get_clean();
		echo do_shortcode('[yproject_widelightbox id="editOrga" scrolltop="1"]'.$lightbox_content.'[/yproject_widelightbox]');
	}
	?>
	<?php 
	ob_start();
	locate_template( array("projects/dashboard/informations/lightbox-organization-new.php"), true );
	$lightbox_content = ob_get_clean();
	echo do_shortcode('[yproject_lightbox id="newOrga" scrolltop="1"]'.$lightbox_content.'[/yproject_lightbox]');
	?>

	<?php
	$msg_valid_changeOrga = __("L'organisation a bien &eacute;t&eacute; li&eacute;e au projet", "yproject");
	echo do_shortcode('[yproject_lightbox_cornered id="valid-changeOrga" scrolltop="1" msgtype="valid"]'.$msg_valid_changeOrga.'[/yproject_lightbox_cornered]');

	$msg_valid_newOrga = __("Votre nouvelle organisation a bien &eacute;t&eacute; cr&eacute;&eacute;e", "yproject");
	echo do_shortcode('[yproject_lightbox_cornered id="valid-newOrga" scrolltop="1" msgtype="valid"]'.$msg_valid_newOrga.'[/yproject_lightbox_cornered]');

	$msg_valid_editOrga = __("Les informations ont bien &eacute;t&eacute; enregistr&eacute;es", "yproject");
	echo do_shortcode('[yproject_lightbox_cornered id="valid-editOrga" scrolltop="1" msgtype="valid"]'.$msg_valid_editOrga.'[/yproject_lightbox_cornered]');
	?>
</div>