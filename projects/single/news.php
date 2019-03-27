<?php
global $campaign;
$posts_in_category = $campaign->get_news_posts();
?>
<div class="project-news padder">
	<h2 class="standard">/ <?php _e('Actualit&eacute;s', 'yproject'); ?> /</h2>
    
	<?php if ($posts_in_category): ?>
		<?php foreach ($posts_in_category as $cat_post): ?>
		<div class="project-news-item">
			<a href="<?php echo get_permalink($cat_post->ID); ?>"><?php echo $cat_post->post_title; ?></a><br />
			<span class="project-news-item-date"><?php echo get_the_date( get_option( 'date_format' ), $cat_post ); ?></span><br />
			<p class="project-news-item-excerpt">
				<?php echo apply_filters( 'the_excerpt', $cat_post->post_content ); ?>
			</p>
			<div class="clear"></div>
			<a href="<?php echo get_permalink($cat_post->ID); ?>" class="button blue-pale"><?php _e("En savoir plus", 'yproject'); ?></a>
		</div>
		<?php endforeach; ?>
	<?php else: ?>
		<div class="align-center"><?php _e("Aucune actualit&eacute; pour le moment.", 'yproject'); ?></div>
	<?php endif; ?>
</div>