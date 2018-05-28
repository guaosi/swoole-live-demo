<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/28/028
 * Time: 13:57
 */
namespace app\common\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;

class Server extends Command
{
    protected function configure()
    {
        $this->setName('server')
            ->addArgument('type', Argument::OPTIONAL, "input start or stop to control Server")
            ->setDescription('to control Http and WebSocket Server');
    }

    protected function execute(Input $input, Output $output)
    {
        $type = trim($input->getArgument('type'));
        if ($type=='start'){
            $shell="nohup php ".__DIR__.'/../../../server/Ws.php >/dev/null 2>&1 &';
            shell_exec($shell);
            $output->writeln("The Http and WebSocket Server has started");
        }else if($type=='stop'){
            $shell1="kill -9 $(netstat -nlp | grep :9501 | awk "."'{print $7}'".' | awk -F"/"'." '{ print $1 }') ";
            shell_exec($shell1);
            $output->writeln("The Http and WebSocket Server has stop");
        }else if($type=='monitor'){
            $shell="nohup php ".__DIR__.'/../../../server/monitor/Server.php >/dev/null 2>&1 &';
            shell_exec($shell);
            $output->writeln("The Monitor has started");
        }else{
            $output->writeln("Input Error");
        }
    }
}