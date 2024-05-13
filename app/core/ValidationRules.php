<?php

class ValidationRules
{
    public $database;

    /**
     * =======================================================
     * =                                                ======
     * =                     Validations                ======
     * =                                                ======
     * =======================================================
     */

    /**
     * Determine if a given value has 'required' rule
     * 
     * @param array $value
     * @return boolean
     */

    public static function is_required($value)
    {
        if(filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS))
        {
            return true;
        }

        return false;
    }

    /**
     * Check if value is a valid email
     * 
     * @param array $email
     */

    public static function email($email)
    {
        if(filter_var($email, FILTER_SANITIZE_EMAIL))
        {
            return true;
        }

        return false;
    }

    /**
     * Minium string length
     * 
     * @param string $str
     * @param array $args(min)
     * @return boolean
     */

    public static function min_length($str, $args)
    {
    return mb_strlen($str, "UTF-8") >= (int)$args;
    }
}