<?php
namespace app\index\controller;
use app\common\lib\ali;
use think\Request;


class Index
{
    public function index()
    {
        return;
    }

    public function hello($name = 'ThinkPHP5')
    {
        print_r($_GET);
        return 'hello,' . $name;
    }
    public function sendSms(){
        ali\Sms::sendSms(13123329831,1234);
    }
    public function testpost(Request $request){
        return $request->post('m',0,'intval');
    }
}
