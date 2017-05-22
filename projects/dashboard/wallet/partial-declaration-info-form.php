<?php
global $can_modify, $disable_logs, $campaign_id, $campaign, $post_campaign, $WDGAuthor, $WDGUser_current, $organization_obj, $is_admin, $is_author;
?>

<?php $declaration_info = $campaign->declaration_info(); ?>
<?php if ( $is_admin ): ?>
	<form action="" id="forcemandate_form" class="db-form" data-action="save_project_declaration_info">
		<?php DashboardUtility::create_field(array(
			"id"			=> "new_declaration_info",
			"type"			=> "editor",
			"label"			=> __( "Informations de reversement", 'yproject' ),
			"value"			=> $declaration_info,
			"editable"		=> $is_admin,
			"admin_theme"	=> $is_admin,
			"visible"		=> $is_admin,
		)); ?>

		<?php DashboardUtility::create_save_button( "forcemandate-form", $is_admin ); ?>
	</form>
<?php elseif ( !empty( $declaration_info ) ) : ?>

	<strong><?php _e( "Informations relatives &agrave; votre d&eacute;claration", 'yproject' ) ?></strong><br />
	<?php echo $declaration_info; ?><br /><br />

<?php endif; ?>