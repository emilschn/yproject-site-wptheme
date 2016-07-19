<?php
global $campaign;
$posts_in_category = $campaign->get_news_posts();
?>
<div class="project-news center">
	<div class="project-news-title separator-title">
		<span> 
			<?php _e('Actualit&eacute;s', 'yproject'); ?>
		</span>
	</div>
    
	<?php if ($posts_in_category): ?>
		<?php foreach ($posts_in_category as $cat_post): ?>
		<div class="project-news-item">
			<a href="<?php echo get_permalink($cat_post->ID); ?>"><?php echo $cat_post->post_title; ?></a><br />
			<?php echo get_the_date( get_option( 'date_format' ), $cat_post ); ?>
		</div>
		<?php endforeach; ?>
	<?php else: ?>
		<div class="align-center"><?php _e("Aucune actualit&eacute; pour le moment.", 'yproject'); ?></div>
	<?php endif; ?>
</div>