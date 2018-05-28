<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/25/025
 * Time: 16:27
 */
namespace app\index\controller;
use app\common\lib\Util;
use think\Request;

class Chart{
    public function index(Request $request){
        $content=$request->post('content');
        $game_id=$request->post('game_id');
        if (empty($content)){
            return Util::show(config('code.error'),"error");
        }
        if (empty($game_id)){
            return Util::show(config('code.error'),"error");
        }
        //默认从数据库中取到了用户的相关数据
        $ws=$_POST['http_server'];
        $data=[
            'user'=>"用户 ".Util::create_password(),
            'content'=>$content
        ];
        $pushData=[
            'method'=>'pushMessage',
            'data'=>$data
        ];
        $ws->task($pushData);
        return Util::show(config('code.success'),'ok');
    }
}