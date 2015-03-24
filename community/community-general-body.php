
<?php $page_community = get_page_by_path('communaute'); // Menu Communauté ?>
&lt;&lt; <a href="<?php echo get_permalink($page_community->ID); ?>"><?php echo __('Communaute', 'yproject'); ?></a>
<?php the_content(); ?>
