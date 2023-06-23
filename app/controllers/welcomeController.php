<?php

class WelcomeController
{
    private $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function index()
    {
        echo 'Welcome';
    }


    
}