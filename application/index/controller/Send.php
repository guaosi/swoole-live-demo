<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/22/022
 * Time: 16:35
 */
namespace app\index\controller;
use app\common\lib\ali\Sms;
use app\common\lib\Redis;
use app\common\lib\Util;
use think\Request;

class Send{
    public function index(Request $request){
        $phoneNum=$request->get('phone_num',0,'intval');
        if (empty($phoneNum)){
            return Util::show(config('code.error'),'error');
        }
        $code=rand(1000,9999);
        $CodeData=[
            'method'=>'sendSms',
            'data'=>[
                'phone'=>$phoneNum,
                'code'=>$code
            ]
        ];
        $http=$_POST['http_server'];
        //投递给任务
        $http->task($CodeData);
        return Util::show(config('code.success'),'短信发送成功');
    }
}