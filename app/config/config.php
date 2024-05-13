<?php

/**
 * ------------------------
 * Base URL
 * ------------------------
 */

function baseurl()
{
    return 'http://localhost/mvc-framework';
    
}

/**
 * ------------------------
 * APP GENERAL CONFIGURATION
 * ------------------------
 */

 $GLOBAL['config'] = array(
    //configuration for the database connection

    "mysql" => array(
        "db_host" => "localhost",  
        "db_user" => "root",  
        "db_pass" => "",  
        "db_name" => "mvc-framework",  
        "db_charset" => "utf8"
    ),
);