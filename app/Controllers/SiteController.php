<?php
namespace App\Controllers;

use JF\Kernel\View;

class SiteController{
    protected $view;

    public function __construct(View $view){
        $this->view=$view;
    }

    public function index(){
        $data=[
            'content'=>'It works!'
        ];
        return $this->view->setPageTitle('Home')->render('index',$data);
    }
}
?>
