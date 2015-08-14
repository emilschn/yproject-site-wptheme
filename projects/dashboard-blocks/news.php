<?php function print_block_news() { 
    global $category_obj,
           $campaign_id,
           $news_link; ?>
<div id ="block-news" class="block">
    <div class="head">Actualit&eacute;s</div>
    <div class="body" style="text-align:center">
        <?php 
        /***************Donnees blog ******************/
        $posts_blog = get_posts(array('category'=>$category_obj->cat_ID));
        //var_dump($posts_blog);
        $nbposts_blog = count($posts_blog);
        $page_edit_news = get_page_by_path('editer-une-actu');

        if ($nbposts_blog == 0){
            echo "Vous n'avez pas encore publi&eacute; d'article.";
        } else {
            echo "<ul>";
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
            echo "</ul>";
        }
        ?>

    <div class="list-button">
        <a href="<?php echo $news_link; ?>" class="button"><?php _e('Voir plus', 'yproject'); ?></a>
        <a href="<?php echo $news_link.'?new-topic=1'; ?>" class="button"><?php _e('Publier une nouvelle actualit&eacute;', 'yproject'); ?></a>
    </div>
    </div>
</div>
<?php } ?>