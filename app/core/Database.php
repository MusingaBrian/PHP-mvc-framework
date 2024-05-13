<?php

/**
 * -----------------------------------
 *  Database connections and Methods
 * -----------------------------------
 */
class Database
{
    private $db_conn = null;
    private $stmt = null;
    private static $database = null;

    public function __construct()
    {
        $this->db_conn = new PDO(
            'mysql:dbname='.Config::get('mysql/db_name').
            '; host='.Config::get('mysql/db_host').
            '; charset='.Config::get('mysql/db_charset'),
            Config::get('mysql/db_user'),
            Config::get('mysql/db_pass')
        );

        $this->db_conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $this->db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function open_db()
    {
        if(self::$database === null)
        {
            self::$database = new Database();
        }

        return self::$database;
    }
    

    public function prepare($query)
    {
        $this->stmt = $this->db_conn->prepare($query);
    }
    
    public static function close_db()
    {
        if(isset(self::$database))
        {
            self::$database->db_conn = null;
            self::$database->stmt = null;
            self::$database->database = null;
        }
    }

}