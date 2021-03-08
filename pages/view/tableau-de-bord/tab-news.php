<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>
<h2><?php _e( "Actualit&eacute;s", 'yproject' ); ?></h2>

<?php DashboardUtility::add_help_item( $page_controler->get_current_user(), 'news', 1 ); ?>

<?php
$news_link = esc_url( get_category_link( $page_controler->get_campaign()->get_news_category_id() ) );
$posts_blog = $page_controler->get_campaign()->get_news_posts();
$nbposts_blog = count( $posts_blog );
?>
<div class="db-form v3 center">
	<?php if ( $nbposts_blog == 0 ): ?>
		<p>Vous n'avez pas encore publi&eacute; d'article.</p>
		
	<?php else: ?>
		<ul>
			<?php
			foreach ( $posts_blog as $post_blog ) {
				$link = get_permalink( $post_blog->ID );
				$link_edit = WDG_Redirect_Engine::override_get_page_url( 'editer-une-actu' ). "?campaign_id=" .$page_controler->get_campaign_id(). "&edit_post_id=" .$post_blog->ID;
				$title = $post_blog->post_title;
				$date = ( new DateTime( $post_blog->post_date ) )->format( 'd/m/Y' ); ?>
				<li>
					<a class="news-title" href="<?php echo $link; ?>"><?php echo $title; ?></a><br>
					<a class="news-title" href="<?php echo $link; ?>#responds">
						<?php echo $post_blog->comment_count; ?> <i class="fa fa-comments" aria-hidden="true"></i>
					</a><br>
					<em>Publi&eacute; le <?php echo $date; ?></em>
					<a class="button blue" href="<?php echo $link_edit; ?>">&Eacute;diter</a>
					<br><br>
				</li>

			<?php
			}
			?>
		</ul>
	<?php endif; ?>

	<div>
		<a href="<?php echo $news_link.'?new-topic=1'; ?>" class="button red"><?php _e('Publier une nouvelle actualit&eacute;', 'yproject'); ?></a>
	</div>
</div>