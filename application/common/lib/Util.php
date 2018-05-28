<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/22/022
 * Time: 16:30
 */
namespace app\common\lib;
class Util{
    public static function show($status=0,$message="",$data=[]){
        return [
            'status'=>$status,
            'message'=>$message,
            'data'=>$data
        ];
    }
    public static function create_password($pw_length = 4){
        $randpwd = '';
        for ($i = 0; $i < $pw_length; $i++){
            $randpwd .= chr(mt_rand(33, 126));
        }
        return $randpwd;
    }
}