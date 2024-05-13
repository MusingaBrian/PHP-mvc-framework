<?php

class Controller 
{
    /**
     * Load View
     * @param string $view
     * @param array $data
     */

    public function view($view, $data = [])
    {
        if(is_array($data))
        {
            extract($data);

        }

        $filename = "app/views/".$view.".php";

        if(file_exists($filename))
        {
            require $filename;
        }

    }

    /**
     * Load Model
     * @param string $model
     */

     public function model($model) 
     {
        $filename = "app/models";
     }

}