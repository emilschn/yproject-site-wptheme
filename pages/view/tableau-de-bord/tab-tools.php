<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<?php
$post_guide_tool_content_filtered = FALSE;
$post_guide_tool = get_page_by_path( 'tableau-de-bord-onglet-outil' );
if ( $post_guide_tool ) {
	$post_guide_tool_content = $post_guide_tool->post_content;
	$post_guide_tool_content_filtered = wpautop( $post_guide_tool_content );
}
?>

<h2><?php _e( "Guide et outils", 'yproject' ); ?></h2>
<div class="db-form v3 center">
	<?php if ( !empty( $post_guide_tool_content_filtered ) ): ?>
		<?php echo $post_guide_tool_content_filtered; ?>
	
	<?php else: ?>
		<br>
		<h3><?php _e( "Centre de support", 'yproject' ); ?></h3>
		<p class="align-justify">
			<?php _e( "Le centre de support regroupe toutes les FAQ et met &agrave; votre disposition des conseils pratiques sur la pr&eacute;paration de votre lev&eacute;e de fonds, l'animation de votre campagne, ainsi que le versement de royalties.", 'yproject' ); ?><br>
			<?php _e( "Nous sommes aussi disponibles via le chat en ligne ou &agrave; l'adresse suivante : support@wedogood.co.", 'yproject' ); ?><br>
		</p>
		<br><br>

		<a href="https://support.wedogood.co/comment-animer-ma-campagne-de-financement" class="button red" target="_blank"><?php _e( "Consulter le centre de support", 'yproject' ); ?></a>
	<?php endif; ?>
</div>