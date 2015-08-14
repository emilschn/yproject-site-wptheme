<?php function print_block_info() { 
    global $campaign,
            $page_particular_terms,
            $page_guide; ?>

<div id="block-info" class="block">
    <div class="head"><?php _e('Informations','yproject'); ?></div>
    <div class="body">
        <ul>
        <a href="<?php echo get_permalink($page_particular_terms->ID); ?>" target="_blank"><li><?php _e('Conditions particuli&egrave;res', 'yproject'); ?></li></a>

        <a href="<?php echo get_permalink($page_guide->ID); ?>" target="_blank"><li><?php _e('Guide de campagne', 'yproject'); ?></li></a>
        </ul>
        <div class="list-button">
            <?php if ($campaign->google_doc() != ''): ?>
                <a href="<?php echo $campaign->google_doc(); ?>/edit" target="_blank" class="button"><?php _e('Ouvrir le document de gestion de campagne', 'yproject'); ?></a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php } ?>