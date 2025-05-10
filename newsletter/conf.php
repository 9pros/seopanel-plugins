<?php
/*
 * plugin specific configuration variables 
 */

// the common name of the subscriber
define('NL_COMMON_NAME', 'Subscriber');

// the number of emails send in each execution of cron job
define('NL_MAX_EMAIL_SEND_PER_CRON', 0);

// the number of emails send in each execution of send newsletter
define('NL_EMAIL_SEND_PER_EXE', 10);

// the number of emails send in each execution of send newsletter
define('NL_SEND_DELAY', 2000);
?>