<?php
define('DB_DRIVER', 'mysql');
define('DB_NAME', 'do');
define('DB_USER', 'root');
define('DB_PASSWORD', '123');
define('DB_HOST', 'localhost');
define('DB_CHARSET', 'utf8');

//define('DO_DEBUG', true);

/** Set default value of search */
define('DEFAULT_GENE', 'TP53');
define('DEFAULT_DISEASE', 'primary breast cancer');

//define('TIMEZONE', 'Asia/Chongqing');

define('STRING_SEPARATOR', '|');
if (defined('ICG_DEBUG'))
    define('DB_DEBUG', true);
else
    define('DB_DEBUG', false);
// End of script