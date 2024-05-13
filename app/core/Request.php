<?php

class Request 
{
    public $data = [];
    public $query = [];

    public function __construct()
    {
        $this->data = $this->merge_data($_POST, $_FILES);
        $this->query = $_GET;
    }

    /**
     * merge post and files data
     * You shouldn't have two fields with the same 'name' attribute in $_POST & $_FILES
     * 
     * @param  array $post
     * @param  array $files
     * @return array the merged array
     */

    public function merge_data(array $post, array $files)
    {
        foreach($post as $key => $value)
        {
            if(is_string($value)) 
            {
                $post[$key] = trim($value);
            }
        }

        return array_merge($files, $post);
    }

    /**
     * safer and better access to $this->data
     * 
     * @param string $key
     * @return mixed
     */

    public function query($key)
    {
        return array_key_exists($key, $this->query) ? $this->query[$key] : null;
    }

    /**
     * Detect if request is POST request
     * 
     * @return boolean
     */

    public function is_post()
    {
        return $_SERVER["REQUEST_METHOD"] === "POST";
    }

    /**
     * Detect if request is GET request
     * 
     * @return boolean
     */

    public function is_get()
    {
        return $_SERVER["REQUEST_METHOD"] === "GET";
    }

    /**
     * Get the referer of this request.
     * 
     * @return string|null
     */

    public function referer()
    {
        return isset($_SERVER["REFERER"]) ? $_SERVER["REFERER"] : null;
    }

    /**
     * Get the client IP addresses.
     * 
     * @return string|null
     */

    public function client_ip()
    {
        return isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : null;
    }

    /**
     * Get the contents of the User Agent
     * 
     * @return string|null
     */

    public function user_agent()
    {
        return isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : null;
    }
}