<?php global $page_controler, $stylesheet_directory_uri; ?>

<?php
$current_investment = WDGInvestment::current();
echo $current_investment->get_session_amount();
echo ' - ';
echo $current_investment->get_session_user_type();
