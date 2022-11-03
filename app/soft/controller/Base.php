<?php
namespace app\soft\controller;
use think\admin\Controller;

class Base extends Controller {
    protected $userinfo;
    public function initialize()
    {
        $this->userinfo = $this->app->session->get("user");
    }
}