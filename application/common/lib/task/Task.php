<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/24/024
 * Time: 10:48
 */
namespace app\common\lib\task;
use app\common\lib\ali\Sms;
use app\common\lib\Redis;
use app\common\lib\redis\Predis;

class Task{

    /**
     * 发送信息
     * @param $data['phone'] 手机号 $data['code'] 验证码
     * @return bool
     */
     public static function sendSms($data){

         try{
             $respose=Sms::sendSms($data['phone'],$data['code']);
         }catch (\Exception $exception){
             return false;
         }
         if ($respose->Code==='OK'){
             Predis::getInstance()->set(Redis::complexSms($data['phone']),$data['code'],config('redis.sms_out_time'));
         }else{
             return false;
         }
         return true;
     }
}