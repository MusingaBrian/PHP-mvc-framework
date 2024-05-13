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

    // This method for instantiating  object using the Singleton Design pattern.
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

	//Binds a value to a parameter.
    public function bind_value($param, $value) 
    {
        $type = self::getPDOType($value);
        $this->stmt->bind_value($param, $value, $type);
    }

    //Binds variable by reference to a parameter.
    public function bind_param($param, $var)
    {
        $type = self::getPDOType($var);
        $this->stmt->bind_param($param, $var, $type);
    }

    //Executes a prepared stmt
    public function execute($arr = null)
    {
        if($arr === null) 
        {
            return $this->stmt->execute();
        } else {
            return $this->stmt->execute($arr);
        }
    }

    //To fetch only a single column in form of 0-indexed array.
    public function fetch_column()
    {
        return $this->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    //To fetch the result data in form of [0-indexed][key][value] array.
    public function fetch_all_associative() 
    {
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //To fetch Only the next row from the result data in form of [key][value] array.
    public function fetch_associative() 
    {
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    //To fetch All the data in form of [0-indexed][an anonymous object with property names that correspond to the column names] array.
    public function fetch_all_object() 
    {
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    //To fetch Only the next row from the result data in form of an anonymous object with property names that correspond to the column names.
    public function fetch_object() 
    {
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

     //To fetch All data in form of an array indexed by both column name and 0-indexed column
     public function fetch_all_both() 
     {
        return $this->stmt->fetchAll(PDO::FETCH_BOTH);
    }

    //To fetch Only the next row from the result data in form of an array indexed by both column name and 0-indexed column
    public function fetch_both() 
    {
        return $this->stmt->fetch(PDO::FETCH_BOTH);
    }

    //Returns the ID of the last inserted row or sequence value
    public function lastInsertedId() 
    {
        return $this->db_conn->lastInsertId();
    }

    //Start a transaction
    public function begin_transaction() 
    {
        $this->db_conn->begin_transaction();
    }

    //Commit a transaction. This method will be called after beginTransaction()
    public function commit() {
        $this->db_conn->commit();
    }

    //Rollback a transaction. This method will be called after beginTransaction()
    public function roll_back() 
    {
        $this->db_conn->roll_back();
    }

    //Returns the number of rows affected by the last SQL stmt
    public function count_rows() 
    {
        return $this->stmt->rowCount();
    }

    //Counts the number of rows in a specific table
    public function count_all($table)
    {
        $this->stmt = $this->db_conn->prepare('SELECT COUNT(*) AS count FROM '.$table);
        $this->execute();
        return (int)$this->fetch_associative()["count"];
    }

    //Select all rows from a table
    public function get_all($table){
        $this->stmt = $this->db_conn->prepare('SELECT * FROM '.$table);
        $this->execute();
    }

    //Select a row from a table provided by id(primary key)
    public function get_by_id($table, $id)
    {
        $this->stmt = $this->db_conn->prepare('SELECT * FROM '.$table. ' WHERE id = :id LIMIT 1');
        $this->bind_value(':id', $id);
        $this->execute();
    }

    //Select a row from a table provided by email
    public function get_by_email($table, $email){
        $this->stmt = $this->db_conn->prepare('SELECT * FROM '.$table. ' WHERE email = :email LIMIT 1');
        $this->bind_value(':email', $email);
        $this->execute();
    }

    //Delete all rows from a table
    public function delete_all($table)
    {
        $this->stmt = $this->db_conn->prepare('DELETE FROM '.$table);
        $this->execute();
    }

    //Delete all data from a table provided by id(primary key)
    public function delete_by_id($table, $id){
        $this->stmt = $this->db_conn->prepare('DELETE FROM '.$table. ' WHERE id = :id LIMIT 1');
        $this->bind_value(':id', $id);
        $this->execute();
    }

    
    //Select all rows from a table provided by user id
    public function get_by_user_id($table, $userId)
    {
        $this->stmt = $this->db_conn->prepare('SELECT * FROM '.$table. ' WHERE user_id = :user_id');
        $this->bind_value(':user_id', $userId);
        $this->execute();
    }

    //Select all rows from a table provided by user email
    public function get_by_user_email($table, $user_email)
    {
        $this->stmt = $this->db_conn->prepare('SELECT * FROM '.$table. ' WHERE user_email = :user_email');
        $this->bind_value(':user_email', $user_email);
        $this->execute();
    }


    //Determine the PDOType of a passed value.
    private static function getPDOType($value)
    {
        switch ($value) {
            case is_int($value):
                return PDO::PARAM_INT;
            case is_bool($value):
                return PDO::PARAM_BOOL;
            case is_null($value):
                return PDO::PARAM_NULL;
            default:
                return PDO::PARAM_STR;
        }
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