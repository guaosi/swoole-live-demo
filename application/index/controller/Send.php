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
        //发送短信
        try{
            $respose=Sms::sendSms($phoneNum,$code);
        }catch (\Exception $exception){
            return Util::show(config('code.error'),'阿里大于短信内部错误');
        }
        if ($respose->Code==='OK'){
             //存入redis
            $redis=new \Swoole\Coroutine\Redis();
            $redis->connect(config('redis.host','redis.port'));
            $redis->set(Redis::complexSms($phoneNum),$code,config('redis.sms_out_time'));
            return Util::show(config('code.success'),'短信发送成功');
        }else{
            return Util::show(config('code.error'),'阿里大于短信发送失败');
        }
    }
}