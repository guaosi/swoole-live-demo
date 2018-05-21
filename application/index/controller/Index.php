<?php
namespace app\index\controller;

class Index
{
    public function index()
    {
        return 'guaosi-123';
    }

    public function hello($name = 'ThinkPHP5')
    {
        print_r($_GET);
        return 'hello,' . $name;
    }
}
