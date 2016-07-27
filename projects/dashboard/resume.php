<?php

function print_resume_page()
{
    global $campaign_id, $campaign, $post_campaign,
           $WDGAuthor, $WDGUser_current,
           $stylesheet_directory_uri;

    $status = $campaign->campaign_status();

    ?>
    <div id="block-summary" >
        <div class="current-step">
            <?php ?>
            <img src="<?php echo $stylesheet_directory_uri; ?>/images/frise-preview.png" alt="" /><br>
            <?php
            foreach (ATCF_Campaign::get_campaign_status_list() as $step => $name){
                $step_to_write = '<span ';
                if($status==$step){$step_to_write.= 'id="current"';}
                $step_to_write .= '>'.$name.'</span>';
                echo $step_to_write;
            }
            ?>
        </div>
    </div>
    <?php

}