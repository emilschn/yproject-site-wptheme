<?php function print_block_news() { 
    global $post_campaign, $campaign_id;
	$category_slug = $post_campaign->ID . '-blog-' . $post_campaign->post_name;
	$category_obj = get_category_by_slug($category_slug);
	$category_link = (!empty($category_obj)) ? get_category_link($category_obj->cat_ID) : '';
	$news_link = esc_url($category_link);
	$posts_blog = get_posts(array('category'=>$category_obj->cat_ID));
	$nbposts_blog = count($posts_blog);
	$page_edit_news = get_page_by_path('editer-une-actu');
?>
<div id ="block-news" class="block">
    <div class="head">Actualit&eacute;s</div>
    <div class="body" style="text-align:center">
        <?php if ($nbposts_blog == 0): ?>
            Vous n'avez pas encore publi&eacute; d'article.
        <?php else: ?>
            <ul>
			<?php
            foreach ($posts_blog as $post_blog) {
                $link = get_permalink($post_blog->ID);
                $link_edit = get_permalink($page_edit_news->ID)."?campaign_id=".$campaign_id."&edit_post_id=".$post_blog->ID;
                $title = $post_blog->post_title;
                $date = (new DateTime($post_blog->post_date))->format('d/m/Y');

                $line = "<li>";
                $line .= '<div class="news-item-tools">';
				$line .= '<a class="nb-comments-widget" href="'.$link.'#responds">'.$post_blog->comment_count.' <img src="'.get_stylesheet_directory_uri().'/images/com.png" alt=" Commentaires" /></a> ';
				$line .= '<a class="button" href="'.$link_edit.'">&Eacute;diter</a></div>';
                $line .= '<a href="'.$link.'">'.$title.'</a><br/>';
                $line .= '<em>Publi&eacute; le '.$date.'</em>';
                $line .= '<div class="clear"/></li>';

                echo $line;
            }
			?>
            </ul>
        <?php endif; ?>

		<div class="list-button">
			<a href="<?php echo $news_link; ?>" class="button"><?php _e('Voir plus', 'yproject'); ?></a>
			<a href="<?php echo $news_link.'?new-topic=1'; ?>" class="button"><?php _e('Publier une nouvelle actualit&eacute;', 'yproject'); ?></a>
		</div>
    </div>
</div>
<?php } ?>