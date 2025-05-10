<?php
/*
 * plugin specific configuration variables 
 */

// the minimum number of characters are allowed in blog comments
define('BC_MIN_CHARS_COMMENT', 80);

// the default blog search engine
define('BC_DEF_BLOG_SEARCH_ENGINE', 'search.wordpress.com');

// the count of search result entries per page
define('BC_SEARCH_DELAY_TIME', 10000);

// the default blog search engine
define('BC_SEARCH_ENGINE_ID', 1);

// the cookie jar and file
define('BC_COOKIE_JAR', SP_TMPPATH . "/bc_cookie_jar.txt");
define('BC_COOKIE_FILE', SP_TMPPATH . "/bc_cookie_file.txt");
?>