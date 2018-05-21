<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/14/014
 * Time: 17:30
 */
$http=new swoole_http_server('0.0.0.0',9501);
$http->set([
    'document_root'=>'/mnt/hgfs/yaf/swoole.com/tp5/public/static/live',
    'enable_static_handler'=>true,
    'worker_num'=>4
]);
$http->on('WorkerStart',function(swoole_server $server, int $worker_id){
    //Container::get('app')->run()->send(); 会直接执行框架，然而这步的意义是热加载，载入框架
   require __DIR__ . '/../thinkphp/base.php';

});

$http->on('request',function ($request,$response) use ($http){
    if (isset($request->server)){
        foreach ($request->server as $k => $v){
             $_SERVER[strtoupper($k)]=$v;
        }
    }
    if (isset($request->header)){
        foreach ($request->header as $k => $v){
            $_SERVER[strtoupper($k)]=$v;
        }
    }
    $_GET=[];
    if (isset($request->get)){
        foreach ($request->get as $k => $v){
            $_GET[$k]=$v;
        }
    }
    $_POST=[];
    if (isset($request->post)){
        foreach ($request->post as $k => $v){
            $_POST[$k]=$v;
        }
    }
    ob_start();
    try{
        think\Container::get('app')->run()->send();
    }catch (\Exception $exception){
        //TODO
    }
    $res=ob_get_contents();
    ob_end_clean();
    $response->end($res);
});
$http->start();