<?php
use fize\net\Socket;

require_once './../../vendor/autoload.php';

$ip = '127.0.0.1';
$port = 1935;

$sock = new Socket();
$sock->create(AF_INET, SOCK_STREAM, SOL_TCP);
$ret = $sock->bind($ip, $port);
$ret = $sock->listen(4);

$count = 0;
do {
    $msgsock = $sock->accept();
    $msg ="测试成功！\n";
    $msgsock->write($msg, strlen($msg));

    $buf = $msgsock->read(8192);
    $talkback = "收到的信息:$buf\n";
    echo $talkback;
    $msgsock->close();
    if(++$count >= 5){
        break;
    }
} while (true);
$sock->close();
echo "socket 已关闭\n";