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
$http->on('request',function ($request,$response){
    $response->header("Content-Type", "text/html; charset=utf-8");
    $response->cookie("guaosi","123",time()+1800);
    $response->end("<h1>Hello Swoole. #".rand(1000, 9999)."-".json_encode($request->get)."</h1>");
});
$http->start();