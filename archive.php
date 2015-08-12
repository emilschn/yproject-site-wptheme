<?php 
global $can_modify, $campaign_id;
$this_category = get_category($cat);
$this_category_name = $this_category->name;
$name_exploded = explode('cat', $this_category_name);
if (count($name_exploded) > 1) {
	$campaign_id = $name_exploded[1];
}
if (isset($campaign_id)) {
	$campaign_post = get_post($campaign_id);
	$campaign = atcf_get_campaign($campaign_post);
}
$page_edit_news = get_page_by_path('editer-une-actu');
locate_template( array("requests/projects.php"), true );
if (isset($_POST['action']) && $_POST['action'] == 'ypcf-campaign-add-news') {
	YPProjectLib::form_validate_news_add($campaign_id);
	//Afficher le nouvel article
	header('Location: '.$_SERVER['REQUEST_URI']);
}

//Supprime un article (le place dans la corbeille de WP)
if (isset($_GET['delete_post_id'])){
    //Test pour vérifier que le post de blog appartient à la campagne
    $category_slug = $campaign_post->ID . '-blog-' . $campaign_post->post_name;
    $category_obj = get_category_by_slug($category_slug);
    $posts_blog = get_posts(array('category'=>$category_obj->cat_ID));
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

<div id="content">
	<div class="padder">

	<?php do_action( 'bp_before_archive' ); ?>

	<div class="page" id="blog-archives" role="main">

		<?php locate_template( array("projects/single-admin-bar.php"), true ); ?>
                
		<?php locate_template( array("projects/single-header.php"), true ); ?>

		<div id="post_bottom_content" class="center margin-height">

			<?php if ($can_modify): ?>

				<h2><a href="javascript:void();" id="add-news-opener"><?php _e('Publier une actualit&eacute;', 'yproject'); ?> <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/plus.png" alt="signe plus"/></a></h2>

				<form action="" method="post" enctype="multipart/form-data" id="add-news">

					<label for="posttitle"><?php _e( 'Titre', 'ypcf' ); ?></label>
					<input type="text" name="posttitle" style="width: 250px;"><br />

					<label for="postcontent"><?php _e( 'Contenu', 'ypcf' ); ?></label>
					<?php
					global $post_ID, $post;
					$post_ID = $post = 0;
					wp_editor( '', 'postcontent', 
						array(
							'media_buttons' => true,
							'quicktags'     => false,
							'tinymce'       => array(
							    'plugins'		    => 'paste',
							    'paste_remove_styles'   => true
							)
						) 
					);
					?><br /><br />

					<input type="hidden" name="action" value="ypcf-campaign-add-news" />
					<?php wp_nonce_field('ypcf-campaign-add-news'); ?>
                                        <label><input type="checkbox" name="send_mail" /> Envoyer par mail cette actualité aux utilisateurs qui croient au projet. <em>Les utilisateurs qui se sont désabonnés de vos actualités ne les recevront pas.</em></label> <br/><br/>
					<?php _e('Relayez cette actualit&eacute; sur vos r&eacute;seaux sociaux et pr&eacute;venez WE DO GOOD pour une communication d&eacute;cupl&eacute;e !', 'yproject'); ?><br /><br />
					
					<input type="submit" value="<?php _e('Publier', 'yproject'); ?>" class="button" /><br /><br />
					
					<hr>

				</form>

			<?php endif; ?>


			<h2><?php printf( __( 'Derni&egrave;res actualit&eacute;s du projet %1$s', 'yproject' ), $campaign_post->post_title); ?></h2>

			<?php if ( have_posts() ) : ?>

				<?php bp_dtheme_content_nav( 'nav-above' ); ?>

				<?php while (have_posts()) : the_post(); ?>

					<?php do_action( 'bp_before_blog_post' ); ?>

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
								<?php the_content( __( 'Read the rest of this entry &rarr;', 'buddypress' ) ); ?>
							</div>

							<span class="comments"><?php comments_popup_link( __( 'No Comments &#187;', 'buddypress' ), __( '1 Comment &#187;', 'buddypress' ), __( '% Comments &#187;', 'buddypress' ) ); ?></span></p>
						</div>

					</div>

					<?php do_action( 'bp_after_blog_post' ); ?>

				<?php endwhile; ?>

				<?php bp_dtheme_content_nav( 'nav-below' ); ?>

			<?php else : ?>

			    <?php if (isset($campaign_post)) : ?>
				Retrouvez bient&ocirc;t les actualit&eacute;s de ce projet !
			    <?php else : ?>
				<h2 class="center"><?php _e( 'Not Found', 'buddypress' ); ?></h2>
				<?php get_search_form(); ?>
			    <?php endif; ?>

			<?php endif; ?>
		</div>

	</div>

	<?php do_action( 'bp_after_archive' ); ?>

	</div><!-- .padder -->
</div><!-- #content -->

<?php get_footer(); ?>
