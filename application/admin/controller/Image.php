<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/24/024
 * Time: 15:46
 */
namespace app\admin\controller;
use app\common\lib\Util;
use think\Request;

class Image{
    public function index(Request $request){
        $file=$request->file('file');
        if ($file){
            $info=$file->move('../public/static/uploads');
            if ($info){
                $data=[
                    'image'=>config('host.host').config('host.dir').$info->getSaveName()
                ];
                return Util::show(config('code.success'),'OK',$data);
            }else{
                return Util::show(config('code.error'),'error');
            }

        }

    }
}