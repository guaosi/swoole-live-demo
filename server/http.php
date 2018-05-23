<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/14/014
 * Time: 17:30
 */
$http = new swoole_http_server('0.0.0.0', 9501);
$http->set([
    //这里设置是给静态资源用的，静态资源url获取需要通过http服务器
    'document_root' => '/mnt/hgfs/yaf/swoole.com/tp5/public/static/live',
    'enable_static_handler' => true,
    'worker_num' => 4
]);
$http->on('WorkerStart', function (swoole_server $server, int $worker_id) {
    //Container::get('app')->run()->send(); 会直接执行框架，然而这步的意义是热加载，载入框架(容器注入)
    require __DIR__ . '/../thinkphp/base.php';

});

$http->on('request', function ($request, $response) use ($http) {
//    require_once __DIR__ . '/../thinkphp/base.php';
    //因为swoole是常驻内存的，所有有的数据会一直存在，需要覆盖
    foreach ($request->server as $k => $v) {
        $_SERVER[strtoupper($k)] = $v;
    }
    foreach ($request->header as $k => $v) {
        $_SERVER[strtoupper($k)] = $v;
    }
    //因为swoole是常驻内存的，所以获得到的数据要保证每次都是最新的
    $_GET = [];
    if (isset($request->get)) {
        foreach ($request->get as $k => $v) {
            $_GET[$k] = $v;
        }
    }
    $_POST = [];
    if (isset($request->post)) {
        foreach ($request->post as $k => $v) {
            $_POST[$k] = $v;
        }
    }

    //将获得到的数据写到缓存区，避免内存泄漏
    ob_start();
    try {
        //直接执行框架(执行容器)
        think\Container::get('app')->run()->send();
    } catch (\Exception $exception) {
        //TODO
    }
    $res = ob_get_contents();
    ob_end_clean();

    //输出内容
   $response->end($res);
});
$http->start();