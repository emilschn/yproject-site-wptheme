<h2 class="underlined">Organisations</h2>

<div class="right">
	<a href="<?php echo home_url('/creer-une-organisation'); ?>" class="button right">Cr&eacute;er une organisation</a>
</div>


<?php
$can_edit = true;
global $current_user;
$WDGUser_current = WDGUser::current();
$api_user_id = BoppLibHelpers::get_api_user_id( $WDGUser_current->wp_user->ID );
$organizations_list = $WDGUser_current->get_organizations_list();
if (!empty($organizations_list)) {
	foreach ($organizations_list as $organization_item) {
		$str_organizations .= '<li>';
		if ($can_edit) { $str_organizations .= '<a href="'. home_url('/editer-une-organisation') .'?orga_id='.$organization_item->wpref.'">'; }
		$str_organizations .= $organization_item->name; 
		if ($can_edit) { $str_organizations .= '</a>'; }
		$str_organizations .= '</li>';
	}
}
if ($str_organizations != ''): ?>
	<ul><?php echo $str_organizations; ?></ul>

<?php else: ?>
	<?php _e('Aucune organisation.', 'yproject'); ?>

<?php endif; ?>
<div class="clear"></div>

<br /><br />