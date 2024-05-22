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
    public function equalTo($value, $args)
    {
        return $value === $args[0];
    }

    /**
     * Check if value is not equal to another value(strings)
     *
     * @param  string  $value
     * @param  array   $args(value)
     * @return bool
     */
    public static function notEqualTo($value, $args)
    {
        return $value !==  $args[0];
    }

    /**
     * =======================================================
     * =                                                ======
     * =                 Database Validations           ======
     * =                                                ======
     * =======================================================
     */

    /**
     * Check if a value of a column is unique.
     *
     * @param  string  $value
     * @param  array   $args(table, column)
     * @return bool
     */
    public function unique($value, $args)
    {

        $table = $args[0];
        $col   = $args[1];

        $this->db = Database::open_db();
        $this->db->prepare("SELECT * FROM {$table} WHERE {$col} = :{$col}");
        $this->db->bindValue(":{$col}", $value);
        $this->db->execute();

        return $this->db->countRows() === 0;
    }

    /**
     * Check if email is unique
     * This will check if email exists and activated.
     *
     * @param  string  $email
     * @return bool
     */
    public function emailUnique($email)
    {

        $this->db = Database::open_db();

        // email is unique in the database, So, we can't have more than 2 same emails
        $this->db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $this->db->bindValue(':email', $email);
        $this->db->execute();
        $user =  $this->db->fetchAssociative();

        if ($this->db->countRows() === 1) {

            if (!empty($user["is_email_activated"])) {
                return false;
            } else {

                $expiry_time = (24 * 60 * 60);
                $time_elapsed = time() - $user['email_last_verification'];

                // If time elapsed exceeded the expiry time, it worth to reset the token, and the email as well.
                // This indicates the email of $user hasn't been verified, and token is expired.
                if ($time_elapsed >= $expiry_time) {

                    // $login = new AuthModel();
                    // $login->resetEmailVerificationToken($user["id"], false);
                    return true;
                } else {

                    // TODO check if $email is same as current user's email(not-activated),
                    // then ask the user to verify his email
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * =======================================================
     * =                                                ======
     * =                   Login Validations            ======
     * =                                                ======
     * =======================================================
     */

    /**
     * Check if user credentials are valid or not.
     *
     * @param  array   $user
     * @return bool
     * @see Login::doLogin()
     */
    public function credentials($user)
    {
        if (empty($user["hashed_password"]) || empty($user["user_id"])) {
            return false;
        }
        return password_verify($user["password"], $user["hashed_password"]);
    }

    /**
     * Check if user has exceeded number of failed logins or number of forgotten password attempts.
     *
     * @param  array   $attempts
     * @return bool
     */
    public function attempts($attempts)
    {

        if (empty($attempts['last_time']) && empty($attempts['count'])) {
            return true;
        }

        $block_time = (10 * 60);
        $time_elapsed = time() - $attempts['last_time'];

        // TODO If user is Blocked, Update failed logins/forgotten passwords
        // to current time and optionally number of attempts to be incremented,
        // but, this will reset the last_time every time there is a failed attempt

        if ($attempts["count"] >= 3 && $time_elapsed < $block_time) {

            // here i can't define a default error message as in defaultMessages()
            // because the error message depends on variables like $block_time & $time_elapsed
            Session::set('danger', "You exceeded number of possible attempts, please try again later after " .

                date("i", $block_time - $time_elapsed) . " minutes");
            return false;
        } else {

            return true;
        }
    }
}
