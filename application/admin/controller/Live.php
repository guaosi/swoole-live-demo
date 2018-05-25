<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/24/024
 * Time: 17:07
 */
namespace app\admin\controller;
use app\common\lib\redis\Predis;
use app\common\lib\Util;
use think\Request;

class Live{
    public function push(Request $request){
        //1. 入库操作不做了。
        //2. 推送
        $info=$request->get();
        if (empty($info)){
            return Util::show(config('code.error','fail'));
        }
        $team_info=[
            1=>[
                'name'=>'马刺',
                'logo'=>'/live/imgs/team1.png'
            ],
            4=>[
                'name'=>'火箭',
                'logo'=>'/live/imgs/team2.png'
            ],
        ];

        $data=[
            'type'=>intval($request->get('type')),
            'title'=>$team_info[$request->get('team_id')]['name'] ?? '直播员',
            'logo'=>$team_info[$request->get('team_id')]['logo']?? '',
            'content'=>$request->get('content')?? '',
            'image'=>$request->get('image') ?? ''
        ];
        $CodeData=[
            'method'=>'pushLive',
            'data'=>$data
        ];
        $ws=$_POST['http_server'];
        $ws->task($CodeData);
        return Util::show(config('code.success'),'ok');
    }
}