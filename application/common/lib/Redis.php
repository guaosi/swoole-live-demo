<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/23/023
 * Time: 10:26
 */
namespace app\common\lib;
class Redis{
    public static $pre='Sms_';
    public static $userPre='User_';
    public static function complexSms($phone){
        return Redis::$pre.$phone;
    }
    public static function complexUser($phone){
        return self::$userPre.$phone;
    }
}