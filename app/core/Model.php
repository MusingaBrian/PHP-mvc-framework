<?php

class Model extends Database
{
    public $database;

    public function __construct()
    {
        $this->$database = Database::open_db;
    }
}