<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/23/023
 * Time: 15:41
 */
namespace app\index\controller;
use app\common\lib\Redis;
use app\common\lib\redis\Predis;
use app\common\lib\Util;
use think\Request;

class Login{
    public function index(Request $request){
        $phoneNum=$request->get('phone_num');
        $code=$request->get('code');
        if (empty($phoneNum)||empty($code)){
            return Util::show(config('code.error'),"手机号或者验证码不能为空");
        }
        try{
           $redis_code=Predis::getInstance()->get(Redis::complexSms($phoneNum));
        }catch (\Exception $exception){
           echo $exception->getMessage();
        }
        if ($code==$redis_code){
            $data=[
                'user'=>$phoneNum,
                'srcKey'=>md5(Redis::complexUser($phoneNum)),
                'time'=>time(),
                'is_login'=>true
            ];
            Predis::getInstance()->set(Redis::complexSms($phoneNum),"");
            Predis::getInstance()->set(Redis::complexUser($phoneNum),$data);
            return Util::show(config('code.success'),'login success',$data);
        }else{
            return Util::show(config('code.error'),'login fail');
        }
    }
}