<?php

class Redirect 
{
    public function to($location)
    {
        header("Location:".baseURL()."/".$location);
    }


    public function back($location)
    {
        header("Location:".$_SERVER['HTTP_REFERER']);
    }
}