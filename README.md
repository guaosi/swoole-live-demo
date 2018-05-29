Swoole+Thinkphp5.1 制作赛事图文直播
===============
> 🚀 基于`Swoole`加速Thinkphp，`Swoole`代替`PHP-FPM`起飞
## 特性

- 内置Http/WebSocket服务器

- 常驻内存

- 实时推送

- 在线聊天室

- 异步的任务队列

- 毫秒级定时任务

- 平滑Reload

- 支持Thinkphp5.1

- 简单，开箱即用


> ThinkPHP5的运行环境要求PHP5.6以上。


## 要求

| 依赖 | 说明 |
| -------- | -------- |
| PHP| `>= 7.1` |
| Swoole| `>= 1.7.19` `推荐最新的稳定版` `从2.0.12开始不再支持PHP5` |
| Thinkphp| `>= 5.1` |
| nginx |用于网址代理解析|
| redis | 用于存放登陆用户fd(可以用swoole的server的自带成员connections) |
| 集成环境[可选的] | LNMP`>=1.4` |


## 注意
没有使用数据库，很多数据都是自己写的，需要自己去添加完善。
不带后台，权限之类的都要自己去完善。
无法使用协程。

## 安装

1. 通过[Github](https://github.com/guaosi/swoole-live-demo),fork到自己的项目下
```
git clone git@github.com:<你的用户名>/swoole-live-demo.git
```
2. 在config目录下创建alisms.php文件
```
return [
    'accessKeyId'=>'阿里大于的KeyId',
    'accessKeySecret'=>'阿里大于的KeySecret',
    'SignName'=>'短信模板名称',
    'TemplateCode'=>'短信模板ID'
];
```

## 运行
> `php think server {start|stop|reload|monitor}`

| 命令 | 说明 |
| --------- | --------- |
| `start` | 启动内置的HTTP和WebSocket服务器，展示已启动的进程列表 *ps -ef&#124;grep live_master* |
| `stop` | 停止HTTP和WebSocket服务器 |
| `reload` | 平滑重启所有worker进程，这些worker进程内包含你的业务代码和框架(Thinkphp)代码，不会重启master/manger进程,   |
| `monitor` | 自动监听live_master进程是否正常运行,若错误会自动发送报警信息到指定的设备 |

## Nginx配置
基于LNMP1.4进行配置,vhost中的虚拟站点.conf文件
1. 让静态文件与php的路由模式都由swoole进行处理
```
server
    {
        listen 80;
        #listen [::]:80;
        server_name swoole.com ;
        index index.html index.htm index.php default.html default.htm default.php;
        root  /mnt/hgfs/yaf/swoole.com/tp5/public;

        include none.conf;
        #error_page   404   /404.html;

        # Deny access to PHP files in specific directory
        #location ~ /(wp-content|uploads|wp-includes|images)/.*\.php$ { deny all; }
        location ~ /.well-known {
            allow all;
        }

        location ~ /\.
        {
            deny all;
        }
        location ~ .*\.(js|css|gif|jpg|jpeg|png|bmp|swf|html)?$
        {
          if (!-e $request_filename) {
             proxy_pass http://127.0.0.1:9501;
        }
        break;
        }

        location / {
        proxy_http_version 1.1;
        proxy_set_header Connection "keep-alive";
        proxy_set_header X-Real-IP $remote_addr;
        if ($uri = /) {
         proxy_pass http://127.0.0.1:9501;
        }
        if (!-e $request_filename) {
             rewrite ^(.*)$ /index.php?s=$1;
             proxy_pass http://127.0.0.1:9501;
             break;
        }
      }
        access_log  /mnt/hgfs/yaf/swoole.com/swoole.com.log;
}
```
2. nginx处理静态文件，php的路由模式由swoole进行处理(推荐)
```
server
    {
        listen 80;
        #listen [::]:80;
        server_name swoole.com ;
        index index.html index.htm index.php default.html default.htm default.php;
        root  /mnt/hgfs/yaf/swoole.com/tp5/public;

        include none.conf;

        #error_page   404   /404.html;

        # Deny access to PHP files in specific directory

        #location ~ /(wp-content|uploads|wp-includes|images)/.*\.php$ { deny all; }

        location ~ /.well-known {
            allow all;
        }
        location ~ /\.
        {
            deny all;
        }
        location ~ .*\.(js|css|gif|jpg|jpeg|png|bmp|swf|html)?$
        {
          if (!-e $request_filename) {
             rewrite ^(.*)$ /static/$1;
        }
        break;
        }

        location / {
        proxy_http_version 1.1;
        proxy_set_header Connection "keep-alive";
        proxy_set_header X-Real-IP $remote_addr;
        if ($uri = /) {
         proxy_pass http://127.0.0.1:9501;
        }
        if (!-e $request_filename) {
             rewrite ^(.*)$ /index.php?s=$1;
             proxy_pass http://127.0.0.1:9501;
             break;
        }
      }
        access_log  /mnt/hgfs/yaf/swoole.com/swoole.com.log;
}
```
3. 负载均衡
```
upstream swoole_http{
    server ip1 weight=1;
    server ip2 weight=2;
}
server{
    ...
    location / {
        ...
        if ($uri = /) {
         proxy_pass http://swoole_http;
        }
        if (!-e $request_filename) {
            rewrite ^(.*)$ /index.php?s=$1;
             proxy_pass http://swoole_http;
             break;
        }
        ...
      }
    ...
}
```
## 执行原理
当url达到nginx后，根据conf里定义的root指定目录里面查找请求的资源文件是否存在,如果存在,则无需进行下面的判断，直接返回给客户端。如果不存在，则进入判断，将请求转发到swoole的HTTP服务器进行处理,而不是继续再交给PHP-FPM进行处理。

## 在你的项目中使用swoole_server实例
```
$server=$_POST['http_server']; //获取swoole_server实例
```
## 在你的项目中使用异步任务
在application/common/lib/task/Task.php中编写处理方法
```
 public function sendSms($data,$serv){
     //$data 是传递过来的数据,$serv是swoole_server实例
     // 直接 $data['phone'] $data['cide']即可
 }
```
在业务逻辑中直接抛送异步任务即可
```
$server=$_POST['http_server']; //获取swoole_server实例
$CodeData=[
        'method'=>'sendSms', //对应Task.php中编写处理方法名称
        'data'=>[
                'phone'=>$phoneNum,
                'code'=>$code
        ]
    ];
$http->task($CodeData);  //抛送异步任务
```