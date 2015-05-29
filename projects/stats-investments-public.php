<?php function print_investments($id_campaign, $is_advanced = FALSE) { ?>

    <div class="ajax-investments-load" data-value="<?php echo $id_campaign?>">
    <h3>G&eacute;n&eacute;ral</h3>
    <div class="ajax-data-inv-loader-img"><img src="<?php echo get_stylesheet_directory_uri() ?>/images/loading.gif" alt="chargement" /></div>
    <strong class="data-inv-count_validate_investments">&hellip;</strong> investissements valid&eacute;s.<br />
    Les investisseurs ont <strong class="data-inv-average_age">&hellip;</strong> ans de moyenne.<br />
    Ce sont <strong class="data-inv-percent_female">&hellip;</strong>% de femmes et <strong class="data-inv-percent_male">&hellip;</strong>% d&apos;hommes.<br />
    <strong class="data-campaign_days_remaining"><?php echo atcf_get_campaign($id_campaign)->days_remaining()?></strong> jours restants.<br />
    Investissement moyen par personne : <strong class="data-inv-average_invest">&hellip;</strong>&euro;<br />
    Investissement m&eacute;dian : <strong class="data-inv-median_invest">&hellip;</strong>&euro;

    <?php if ($is_advanced === TRUE): ?>
    <br /><br />
    Total des investissements par ch&egrave;que : <strong class="data-inv-amount_check">&hellip;</strong><br />
    <?php endif; ?>

    <h3>Ils ont investi</h3>
    <p class="data-inv-investors_string">&hellip;</p>
    </div>

<?php }?>