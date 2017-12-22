<?php 
global $can_modify, $campaign_id, $is_campaign_page;
$this_category = get_category($cat);
$this_category_name = $this_category->name;
$name_exploded = explode('cat', $this_category_name);
if (count($name_exploded) > 1) {
	$campaign_id = $name_exploded[1];
}
$classes = '';
if (isset($campaign_id)) {
	$campaign_post = get_post($campaign_id);
	$campaign = atcf_get_campaign($campaign_post);
	$edit_version = $campaign->edit_version();
	$classes = 'version-' . $edit_version;
	$is_campaign_page = TRUE;
	
	$tag_list = wp_get_post_terms($campaign_id, 'download_tag');
	foreach ($tag_list as $tag) {
		if ($classes != '') { $classes .= ' '; }
		$classes .= 'theme-' . $tag->slug;
		$client_context = $tag->slug;
	}
}
$page_edit_news = get_page_by_path('editer-une-actu');
if (isset($_POST['action']) && $_POST['action'] == 'ypcf-campaign-add-news') {
	WDGFormProjects::form_validate_news_add($campaign_id);
	//Afficher le nouvel article
	header('Location: '.$_SERVER['REQUEST_URI']);
}

//Supprime un article (le place dans la corbeille de WP)
if (isset($_GET['delete_post_id'])){
    //Test pour vérifier que le post de blog appartient à la campagne
    $posts_blog = get_posts( array(
		'category' => $campaign->get_news_category_id()
	));
    $delete_post_id = ($_GET['delete_post_id']);
    $post_belong_campaign = false;
    foreach ($posts_blog as $post_blog) {
        if ($post_blog->ID == $delete_post_id){
            $post_belong_campaign = true;
            $title = $post_blog->post_title;
        }
    }
    if ($post_belong_campaign && $campaign->current_user_can_edit()){
        wp_trash_post($delete_post_id);

        //Rafraichit la liste des posts
        header('Location: '.$_SERVER['REQUEST_URI']);
    }
}
?>

<?php get_header(); ?>

<div id="content" <?php echo 'class="'.$classes.'"'; ?>>
    
	<?php if ($client_context != '') {
		locate_template( array("clients/myphotoreporter/menu.php"), true ); 
		display_photoreporter_menu();
	} ?>
    
	<div class="padder">

	<div class="page" id="blog-archives" role="main">
		
		<?php locate_template( array("projects/single/banner.php"), true ); ?>

		<div id="post_bottom_content" class="center margin-height">

			<?php if ($can_modify): ?>

				<h2><a class="expandator" data-target="add-news" id="add-news-opener"><?php _e('Publier une actualit&eacute;', 'yproject'); ?> <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/plus.png" alt="signe plus"/></a></h2>
                                
				<form action="" method="post" enctype="multipart/form-data" id="extendable-add-news" class="expandable 
					<?php if(isset($_GET['new-topic']) || (isset($_POST['action']) && $_POST['action'] == 'ypcf-campaign-preview-news')){echo 'default-expanded';}?>">

					<?php if (isset($_POST['action']) && $_POST['action'] == 'ypcf-campaign-preview-news') { ?>
					<div class="preview">
						<h3>Pr&eacute;visualisation de l'actu</h3>
						<div class="preview-frame">
							<div class="post-content">
								<?php echo '<h3 class="posttitle">'.$_POST['posttitle'].'</h3>';
									echo '<p class="date">'.mysql2date( get_option( 'date_format' ), date_format(new DateTime(), 'Y-m-d H:i:s')).'</p>';
									echo '<div class="entry">'.nl2br($_POST['postcontent']).'</div>';?>
							</div>
						</div>
					</div>
					<?php } ?>
                                    
					<label for="posttitle"><?php _e( 'Titre', 'ypcf' ); ?></label>
					<input type="text" name="posttitle" style="width: 250px;" value="<?php if (isset($_POST['posttitle'])){echo $_POST['posttitle'];}?>"><br />

					<label for="postcontent"><?php _e( 'Contenu', 'ypcf' ); ?></label>
					<?php
					global $post_ID, $post;
					$post_ID = $post = 0;
                                        
					if (isset($_POST['postcontent'])){
						$previous_content = $_POST['postcontent'];
					} else {
						$previous_content = '';
					}
                                        
					wp_editor( $previous_content, 'postcontent', 
						array(
							'media_buttons' => true,
							'quicktags'     => false,
							'tinymce'       => array(
							    'plugins'		    => 'wordpress, paste, wplink, textcolor',
							    'paste_remove_styles'   => true
							)
						) 
					);
					?><br /><br />

					<label><input type="checkbox" name="send_mail" <?php if (isset($_POST['send_mail'])){echo 'checked';}?>/>
					Envoyer par mail cette actualité aux utilisateurs qui croient au projet. <em>Les utilisateurs qui se sont désabonnés de vos actualités ne les recevront pas.</em></label> <br/><br/>
					
					<?php _e('Relayez cette actualit&eacute; sur vos r&eacute;seaux sociaux et pr&eacute;venez WE DO GOOD pour une communication d&eacute;cupl&eacute;e !', 'yproject'); ?><br /><br />
					
					<button type="submit" name="action" value="ypcf-campaign-preview-news" class="button"><?php _e('Prévisualisation', 'yproject'); ?></button>
					<button type="submit" name="action" value="ypcf-campaign-add-news" class="button"><?php _e('Publier', 'yproject'); ?></button><br /><br />
					<?php wp_nonce_field('ypcf-campaign-add-news'); ?>
					<?php wp_nonce_field('ypcf-campaign-preview-news'); ?>
                                        
					<hr>

				</form>

			<?php endif; ?>


			<h2><?php printf( __( 'Derni&egrave;res actualit&eacute;s du projet %1$s', 'yproject' ), $campaign_post->post_title); ?></h2>

			<?php if ( have_posts() ) : ?>

				<?php while (have_posts()) : the_post(); ?>

					<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

						<div class="post-content">
							<h3 class="posttitle">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								<?php if ($can_modify): ?>
								&nbsp;&nbsp;&nbsp;
								<a href="<?php echo get_permalink($page_edit_news->ID); ?>?campaign_id=<?php echo $campaign_post->ID; ?>&edit_post_id=<?php echo $post->ID; ?>" class="button"><?php _e('Editer', 'yproject'); ?></a>
								<?php endif; ?>
							</h3>

							
							<p class="date"><?php echo get_the_date(); ?></p>

							<div class="entry">
								<?php the_content( __( "Lire la suite...", 'yproject' ) ); ?>
							</div>

							<span class="comments"><?php comments_popup_link( __( 'Aucun commentaire &#187;', 'yproject' ), __( '1 commentaire &#187;', 'yproject' ), __( '% commentaires &#187;', 'yproject' ) ); ?></span></p>
						</div>

					</div>

				<?php endwhile; ?>

			<?php else : ?>

			    <?php if (isset($campaign_post)) : ?>
				Retrouvez bient&ocirc;t les actualit&eacute;s de ce projet !
			    <?php else : ?>
				<h2 class="center"><?php _e( "Aucun...", 'yproject' ); ?></h2>
				<?php get_search_form(); ?>
			    <?php endif; ?>

			<?php endif; ?>
		</div>

	</div>

	</div><!-- .padder -->
</div><!-- #content -->

<?php get_footer();
