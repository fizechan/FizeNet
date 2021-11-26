<?php
use Fize\Net\Socket;

require_once './../../vendor/autoload.php';

$ip = '127.0.0.1';
$port = 1935;
$socket = new Socket();
$socket->create(AF_INET, SOCK_STREAM, SOL_TCP);
$socket->connect($ip, $port);

$in = "Ho\r\n";
$in .= "first blood\r\n";
$out = '';
$socket->write($in, strlen($in));
while($out = $socket->read(8192)) {
    echo "接收服务器回传信息成功！\n";
    echo "接受的内容为:",$out;
}
echo "关闭SOCKET...\n";
$socket->close();
echo "关闭OK\n";