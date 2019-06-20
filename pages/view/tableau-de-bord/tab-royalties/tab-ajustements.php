<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<div id="stat-subtab-ajustements" class="stat-subtab">
	
	<h3><?php _e( "Somme des reliquats non-vers&eacute;s aux investisseurs", 'yproject' ); ?></h3>
	<?php
	$sum_remaining_amount = 0;
	$declaration_list = WDGROIDeclaration::get_list_by_campaign_id( $page_controler->get_campaign_id() );
	foreach ( $declaration_list as $declaration ) {
		$sum_remaining_amount += $declaration->remaining_amount;
	}
	?>
	<?php echo UIHelpers::format_number( $sum_remaining_amount ); ?> &euro;<br><br>

	<h3><?php _e( "Documents &agrave; traiter (pas encore affect&eacute;s &agrave; un ajustement)", 'yproject' ); ?></h3>
	<?php $files = WDGWPREST_Entity_Project::get_files_unused( $page_controler->get_campaign()->get_api_id(), 'project_document' ); ?>
	<?php foreach ( $files as $file_item ): $file_item_metadata = json_decode( $file_item->metadata ); ?>
		<a href="<?php echo $file_item->url; ?>" target="_blank"><?php echo $file_item_metadata->name; ?></a><br>
		<?php echo $file_item_metadata->details; ?>
		<br><br>
	<?php endforeach; ?>
	<br>
	
	<h3><?php _e( "Ajustements", 'yproject' ); ?></h3>
	A venir...<br><br>
	
</div>