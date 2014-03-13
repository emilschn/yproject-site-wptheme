<?php global $campaign; ?>

<?php $vote_status = html_entity_decode($campaign->vote()); ?>

<div style="padding-top: 25px"><?php echo html_entity_decode($campaign->summary()); ?></div>

<?php if ($vote_status != 'preview'): ?>
<?php if ($campaign->video() != '') { echo '<br /><br />' . wp_oembed_get($campaign->video(), array('width' => 610)); } ?>
<?php endif; ?>

<h2 class="padding-top">En quoi consiste le projet ?</h2>
<span><?php the_content(); ?></span>

<?php if ($vote_status != 'preview'): ?>
<h2 class="padding-top">Quelle est l'opportunité économique du projet ?</h2>
<div><?php 
    $added_value = html_entity_decode($campaign->added_value()); 
    echo apply_filters('the_content', $added_value);
?></div>
<?php endif; ?>

<h2 class="padding-top">Quelle est l'utilité sociétale du projet ?</h2>
<div><?php 
    $societal_challenge = html_entity_decode($campaign->societal_challenge()); 
    echo apply_filters('the_content', $societal_challenge);
?></div>

<?php if ($vote_status != 'preview'): ?>
<h2 class="padding-top">Quel est le modèle économique du projet ?</h2>
<div><?php 
    $economic_model = html_entity_decode($campaign->economic_model()); 
    echo apply_filters('the_content', $economic_model);
?></div>
<?php endif; ?>

<h2 class="padding-top">Qui porte le projet ?</h2>
<div><?php 
    $implementation = html_entity_decode($campaign->implementation()); 
    echo apply_filters('the_content', $implementation);
?></div>