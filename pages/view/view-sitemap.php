<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<?php if ( !empty( $page_controler ) && $page_controler->has_send_in_blue_templates_to_init() ): ?>
	<br><br><br><br><br><br><br><br><br>
	<div class="sendinblue-templates-init-status">
		En cours d'initialisation...
		<span>0</span> / <?php echo $page_controler->get_send_in_blue_templates_count(); ?>
	</div>
	<div class="sendinblue-templates-init-status-complete">
		Initialisation terminée
	</div>

	<script>
	$( '.sendinblue-templates-init-status-complete' ).hide();
	var wdg_sendinblue_template_count = <?php echo $page_controler->get_send_in_blue_templates_count(); ?>;
	function wdg_update_next_sendinblue_template( nTemplateIndex ) {
		$.ajax({
			'type' : "POST",
			'url' : ajax_object.ajax_url,
			'data': {
				'action':'init_sendinblue_templates',
				'template_index':nTemplateIndex
			}
		}).done(function(result){
			$( '.sendinblue-templates-init-status span' ).text( result );
			if ( result < wdg_sendinblue_template_count ) {
				wdg_update_next_sendinblue_template( result );
			} else {
				$( '.sendinblue-templates-init-status-complete' ).show();
				$( '.sendinblue-templates-init-status' ).hide();
			}
		});
	}
	wdg_update_next_sendinblue_template( 0 );
	</script>
	<br><br><br><br><br><br><br><br><br>
<?php endif; ?>