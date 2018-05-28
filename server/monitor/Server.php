<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/28/028
 * Time: 14:45
 */
class Server{
    const PORT=9501;
    public static $flag=0; //用于判断是否已经发送了提醒 0是为发送，1是已发送
    public function monitor(){
        $shell="netstat -anp 2>/dev/null |grep ".self::PORT."|grep LISTEN|wc -l";
        $result=shell_exec($shell);
        if ($result!=1) {
            //代表出错 发送短信给管理员
            //todo
            Server::$flag=1;
            echo 'Error'.PHP_EOL;
        }
    }
}
swoole_timer_tick(2000,function ($tick_id){
    if(Server::$flag==0){
        (new Server())->monitor();
    }else{
        //取消定时任务
        swoole_timer_clear($tick_id);
    }
});
