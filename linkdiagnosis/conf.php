<?php
/*
 * plugin specific configuration variables 
 */

// the pagerank used for find out the excellent and good links 
define('LD_EXCEL_PR', 4);

// the number of outbound links used for find out the excellent and good links
define('LD_EXCEL_OUTBOUND', 6);
define('LD_EXCEL_OUTBOUND_POOR', 15);

// link score values
define('LD_LINK_SCORE_EXCEL', 35);
define('LD_LINK_SCORE_GREAT', 20);
define('LD_LINK_SCORE_GOOD', 8);

// time interval for backlink crawl in microseconds
define('LD_CRAWL_DELAY_BACKLINKS', 20000);

// delay b/w to show crawl info in micro seconds
define('LD_CRAWL_DELAY_INFO', 15000);

// time interval for backlink crawl for cron job in seconds
define('LD_CRAWL_DELAY_BACKLINKS_CRON', 60);

// delay b/w to show crawl info for cron job seconds
define('LD_CRAWL_DELAY_INFO_CRON', 10);

// number of reports executed in one cron job
define('LD_CRON_REPORT_DAILY_LIMIT', 1);

// number of backlinks informations checked in single execution
define('LD_BACKLINK_INFO_CHECK_LIMIT', 10);
?>