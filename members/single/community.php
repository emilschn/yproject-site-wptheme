<h2 class="underlined">Organisations</h2>

<div class="right">
	<a href="<?php echo home_url('/creer-une-organisation'); ?>" class="button right">Cr&eacute;er une organisation</a>
</div>


<?php
$can_edit = true;
global $current_user;
$WDGUser_current = WDGUser::current();
$api_user_id = BoppLibHelpers::get_api_user_id( $WDGUser_current->wp_user->ID );
$organisations_list = BoppUsers::get_organisations_by_role($api_user_id, BoppLibHelpers::$organisation_creator_role['slug']);
if (!empty($organisations_list)) {
	foreach ($organisations_list as $organisation_item) {
		$str_organisations .= '<li>';
		if ($can_edit) { $str_organisations .= '<a href="'. home_url('/editer-une-organisation') .'?orga_id='.$organisation_item->organisation_wpref.'">'; }
		$str_organisations .= $organisation_item->organisation_name; 
		if ($can_edit) { $str_organisations .= '</a>'; }
		$str_organisations .= '</li>';
	}
}
if ($str_organisations != ''): ?>
	<ul><?php echo $str_organisations; ?></ul>

<?php else: ?>
	<?php _e('Aucune organisation.', 'yproject'); ?>

<?php endif; ?>
<div class="clear"></div>

<br /><br />