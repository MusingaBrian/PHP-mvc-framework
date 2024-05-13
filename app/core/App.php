<?php

class App 
{
    private $controller = "Home";
    private $method = "index";
    private $params;
    
    private function split_url()
    {
        $URL = $_GET['url'];
        $URL = explode("/", filter_var(trim($URL, "/")), FILTER_SANITIZE_URL);
        return $URL;
    }

    public function run()
    {
        
    }
}