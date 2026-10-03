<?php
if ( PHP_SAPI !== 'cli' ) { exit(1); }
require '/var/www/unicancercenter.com/wp-load.php';
flush_rewrite_rules( true );
echo "Rewrite rules flushed.\n";
