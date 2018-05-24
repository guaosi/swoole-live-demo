<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/23/023
 * Time: 15:46
 */
namespace app\common\lib\redis;
class Predis{
    //单例模式
    private static $instance=null;
    public $redis=null;
    private function __construct()
    {
            $this->redis=new \Redis();
            $status=$this->redis->connect(config('redis.host'),config('redis.port'),config('redis.timeout'));
            if ($status===false){
                throw new \Exception('redis connect error');
            }
    }
    public static function getInstance(){
    if (self::$instance==null){
        self::$instance=new self();
    }
    return self::$instance;
    }
    public function set($key,$value,$time=0){
        if (!$key){
            return '';
        }
        if (is_array($value)){
            $value=json_encode($value);
        }
        if ($time==0){
            //set
            return $this->redis->set($key,$value);
        }else{
            //setex
            return $this->redis->setex($key,$time,$value);
        }
    }
    public function get($key){
        if (!$key){
            return '';
        }
        return $this->redis->get($key);
    }
}