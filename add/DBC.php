<?php

/**
 * Description of DB
 *
 * @author lzf
 */
class DBC {

    private static $DB_HOST = 'localhost';
    private static $DB_USER = 'root';
    private static $DB_PASS = '123';
    private static $DB_NAME = 'do';
    private static $db;

    final private function __construct() {
        
    }

    final private function __clone() {
        
    }

    public static function get_conn() {
        if (is_null(self::$db)) {
            self::$db = mysqli_connect(self::$DB_HOST, self::$DB_USER, self::$DB_PASS, self::$DB_NAME) or die('Error: Can not connect to MySQL server.');
        }
        return self::$db;
    }

    public static function close_conn($dbc) {
        if (!is_null($dbc)) {
            mysqli_close($dbc);
        }
    }
    
}

?>
