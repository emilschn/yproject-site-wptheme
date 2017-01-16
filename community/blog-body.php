
<?php $page_community = get_page_by_path('communaute'); // Menu Communauté ?>
&lt;&lt; <a href="<?php echo get_permalink($page_community->ID); ?>"><?php echo __('Communaute', 'yproject'); ?></a>

<?php 
query_posts( array(
	'post_status' => 'publish',
	'category_name' => 'wedogood',
	'orderby' => 'post_date',
	'order' => 'desc',
	'posts_per_page' => -1
) );
global $more;
$more = 0;

if ( have_posts() ) : ?>

<?php while (have_posts()) : the_post(); ?>

	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<div class="post-content">
			<h2 class="posttitle"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			
			<p class="date"><?php echo get_the_date(); ?></p>

			<div class="entry">
				<?php the_content( 'Lire la suite' ); ?>
			</div>
			
			<span class="comments"><?php comments_popup_link( __( 'Aucun commentaire &#187;', 'yproject' ), __( '1 commentaire &#187;', 'yproject' ), __( '% commentaires &#187;', 'yproject' ) ); ?></span></p>
		</div>

	</div>

<?php endwhile; ?>

<?php else : ?>
	<div>Retrouvez bient&ocirc;t les actualit&eacute;s de l&apos;&eacute;quipe !</div>

<?php endif; 

wp_reset_query();