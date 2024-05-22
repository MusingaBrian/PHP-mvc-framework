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
        if (filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS)) {
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
        if (filter_var($email, FILTER_SANITIZE_EMAIL)) {
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

    public static function minLength($str, $args)
    {
        return mb_strlen($str, "UTF-8") >= (int)$args;
    }

    /**
     * @param string $str
     * @param array $args(max)
     * 
     * @return bool
     */

    public function maxLength($str, $args)
    {
        return mb_strlen($str, 'UTF-8') <= (int)$args;
    }

    /**
     * check if value is a valid number
     * 
     * @param string|integer $value
     * 
     * @return bool
     */

    public function integer($value)
    {
        return filter_var($value, FILTER_VALIDATE_INT);
    }

    /**
     * Check if value is contains alphanumeric characters.
     * 
     * @param mixed $value
     * 
     * @return bool
     */

    public function alphaNumeric($value)
    {
        return preg_match('/\A[a-z0-9]+\z/i', $value);
    }

    /**
     * check if password has at least
     * - one lowercase letter
     * - one uppercase letter
     * - one number
     * - one special(non-word) character
     *
     * @param  mixed   $value
     * @return bool
     * @see http://stackoverflow.com/questions/8141125/regex-for-password-php
     * @see http://code.runnable.com/UmrnTejI6Q4_AAIM/how-to-validate-complex-passwords-using-regular-expressions-for-php-and-pcre
     */
    public static function password($value)
    {
        return preg_match_all('$\S*(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$', $value);
    }

    /**
     * Check if value is equals to another value(strings)
     *
     * @param  string  $value
     * @param  array   $args(value)
     * @return bool
     */
    public function equals($value, $args)
    {
        return $value === $args[0];
    }
}
