<?php


use fize\net\Socket;
use PHPUnit\Framework\TestCase;

class TestSocket extends TestCase
{

    public function testAccept()
    {
        $ip = '127.0.0.1';
        $port = 1935;

        $sock = new Socket();
        $sock->create(AF_INET, SOCK_STREAM, SOL_TCP);
        $sock->bind($ip, $port);
        $sock->listen(4);

        $count = 0;
        do {
            $msgsock = $sock->accept();
            var_dump($msgsock);
            self::assertInstanceOf(Socket::class, $msgsock);
            $msg ="测试成功！\n";
            $msgsock->write($msg, strlen($msg));

            $buf = $msgsock->read(8192);
            $talkback = "收到的信息:$buf\n";
            echo $talkback;
            $msgsock->close();
            if(++$count >= 1){
                break;
            }
        } while (true);
        $sock->close();
    }

    public function testBind()
    {
        $ip = '127.0.0.1';
        $port = 1935;

        $sock = new Socket();
        $sock->create(AF_INET, SOCK_STREAM, SOL_TCP);
        $ret = $sock->bind($ip, $port);
        self::assertTrue($ret);
        $sock->listen(4);

        $count = 0;
        do {
            $msgsock = $sock->accept();
            $msg ="测试成功！\n";
            $msgsock->write($msg, strlen($msg));

            $buf = $msgsock->read(8192);
            $talkback = "收到的信息:$buf\n";
            echo $talkback;
            $msgsock->close();
            if(++$count >= 1){
                break;
            }
        } while (true);
        $sock->close();
    }

    public function testClearError()
    {
        $sock = new Socket();
        $sock->create(AF_INET, SOCK_STREAM, SOL_TCP);
        $sock->clearError();
        self::assertInstanceOf(Socket::class, $sock);
    }

    public function testClose()
    {
        $sock = new Socket();
        $sock->create(AF_INET, SOCK_STREAM, SOL_TCP);
        $sock->close();
        self::assertInstanceOf(Socket::class, $sock);
    }

    public function testCmsgSpace()
    {
        $rst = Socket::cmsgSpace(SOL_SOCKET, SO_TYPE);
        var_dump($rst);
        self::assertIsInt($rst);
    }

    public function testConnect()
    {
        $ip = '127.0.0.1';
        $port = 1935;
        $socket = new Socket();
        $socket->create(AF_INET, SOCK_STREAM, SOL_TCP);
        $rst = $socket->connect($ip, $port);
        self::assertTrue($rst);

        $in = "Ho\r\n";
        $in .= "first blood\r\n";
        $socket->write($in, strlen($in));
        while($out = $socket->read(8192)) {
            echo "接收服务器回传信息成功！\n";
            echo "接受的内容为:",$out;
        }
        echo "关闭SOCKET...\n";
        $socket->close();
        echo "关闭OK\n";
    }

    public function testCreateListen()
    {
        $socket = new Socket();
        $socket2 = $socket->createListen(999);
        self::assertInstanceOf(Socket::class, $socket);
        self::assertInstanceOf(Socket::class, $socket2);
    }

    public function testCreatePair()
    {
        $sockets = [];
        $domain = (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' ? AF_INET : AF_UNIX);
        Socket::createPair($domain, SOCK_STREAM, 0, $sockets);
        var_dump($sockets);
        self::assertIsArray($sockets);
    }

    public function testCreate()
    {
        $sock = new Socket();
        $sock->create(AF_INET, SOCK_STREAM, SOL_TCP);
        self::assertInstanceOf(Socket::class, $sock);
    }

    public function testGetOption()
    {
        $sock = new Socket();
        $sock->create(AF_INET, SOCK_STREAM, SOL_TCP);
        $value = $sock->getOption(SOL_SOCKET, SO_LINGER);
        var_dump($value);
        self::assertNotNull($value);
    }

    public function testGetOpt()
    {
        $sock = new Socket();
        $sock->create(AF_INET, SOCK_STREAM, SOL_TCP);
        $value = $sock->getOpt(SOL_SOCKET, SO_LINGER);
        var_dump($value);
        self::assertNotNull($value);
    }

    public function testGetPeerName()
    {
        $ip = '127.0.0.1';
        $port = 1935;
        $socket = new Socket();
        $socket->create(AF_INET, SOCK_STREAM, SOL_TCP);
        $socket->connect($ip, $port);
        $socket->getPeerName($address, $port);
        var_dump($address);
        self::assertNotNull($address);
        var_dump($port);
        self::assertNotNull($port);
        $socket->close();
    }

    public function testGetSockName()
    {
        $ip = '127.0.0.1';
        $port = 1935;
        $socket = new Socket();
        $socket->create(AF_INET, SOCK_STREAM, SOL_TCP);
        $socket->connect($ip, $port);
        $socket->getSockName($address, $port);
        var_dump($address);
        self::assertNotNull($address);
        var_dump($port);
        self::assertNotNull($port);
        $socket->close();
    }

    public function testImportStream()
    {
        $socket = new Socket();
        $stream = stream_socket_server("udp://0.0.0.0:58380", $errno, $errstr, STREAM_SERVER_BIND);
        $socket->importStream($stream);
        self::assertInstanceOf(Socket::class, $socket);
    }

    public function testLastError()
    {
        $socket = new Socket();
        $le = $socket->lastError();
        var_dump($le);
        self::assertIsInt($le);
    }

    public function testListen()
    {
        $ip = '127.0.0.1';
        $port = 1935;
        $sock = new Socket();
        $sock->create(AF_INET, SOCK_STREAM, SOL_TCP);
        $sock->bind($ip, $port);
        $ret = $sock->listen(4);
        self::assertTrue($ret);
        $sock->close();
    }

    public function testRead()
    {
        $ip = '127.0.0.1';
        $port = 1935;
        $socket = new Socket();
        $socket->create(AF_INET, SOCK_STREAM, SOL_TCP);
        $rst = $socket->connect($ip, $port);
        self::assertTrue($rst);

        $in = "Ho\r\n";
        $in .= "first blood\r\n";
        $socket->write($in, strlen($in));
        while($out = $socket->read(8192)) {
            echo "接收服务器回传信息成功！\n";
            echo "接受的内容为:",$out;
        }
        echo "关闭SOCKET...\n";
        $socket->close();
        echo "关闭OK\n";
    }

    public function testRecv()
    {
        $ip = '127.0.0.1';
        $port = 1935;
        $socket = new Socket();
        $socket->create(AF_INET, SOCK_STREAM, SOL_TCP);
        $rst = $socket->connect($ip, $port);
        self::assertTrue($rst);

        $in = "Ho\r\n";
        $in .= "first blood\r\n";
        $socket->write($in, strlen($in));

        $buf = '';
        $bytes = $socket->recv($buf, 2048, MSG_WAITALL);
        echo "Read $bytes bytes from socket_recv(). Closing socket...";
        echo "接受的内容为:", $buf;
        echo "关闭SOCKET...\n";
        $socket->close();
        echo "关闭OK\n";
    }

    public function testRecvFrom()
    {
        $ip = '127.0.0.1';
        $port = 1935;
        $socket = new Socket();
        $socket->create(AF_INET, SOCK_STREAM, SOL_TCP);
        $rst = $socket->connect($ip, $port);
        self::assertTrue($rst);

        $in = "Ho\r\n";
        $in .= "first blood\r\n";
        $socket->write($in, strlen($in));

        $buf = '';
        $iip = '';
        $iport = null;
        $bytes = $socket->recvFrom($buf, 2048, MSG_WAITALL, $iip, $iport);
        echo "Read $bytes bytes from socket_recv(). Closing socket...";
        echo "接受的内容为:", $buf;
        echo "关闭SOCKET...\n";
        $socket->close();
        echo "关闭OK\n";
        var_dump($iip);
        var_dump($iport);
    }

    public function testRecvMsg()
    {
        $ip = '127.0.0.1';
        $port = 1935;
        $socket = new Socket();
        $socket->create(AF_INET, SOCK_STREAM, SOL_TCP);
        $rst = $socket->connect($ip, $port);
        self::assertTrue($rst);

        $in = "Ho\r\n";
        $in .= "first blood\r\n";
        $socket->write($in, strlen($in));

        $message = ['controllen' => 2048];
        $bytes = $socket->recvMsg($message, MSG_WAITALL);
        echo "Read $bytes bytes from recvMsg Closing socket...";
        $socket->close();
        var_dump($message);
        self::assertIsArray($message);
    }

    public function testSelect()
    {
        $r = [];
        $w = [];
        $e = null;
        Socket::select($r, $w, $e, 0);
        var_dump($r);
        var_dump($w);
        self::assertIsArray($r);
        self::assertIsArray($w);
    }

    public function testSend()
    {
        $ip = '127.0.0.1';
        $port = 1935;
        $socket = new Socket();
        $socket->create(AF_INET, SOCK_STREAM, SOL_TCP);
        $rst = $socket->connect($ip, $port);
        self::assertTrue($rst);

        $in = "Ho\r\n";
        $in .= "first blood\r\n";
        $socket->send($in, strlen($in), MSG_DONTROUTE);
        while($out = $socket->read(8192)) {
            echo "接收服务器回传信息成功！\n";
            echo "接受的内容为:",$out;
        }
        echo "关闭SOCKET...\n";
        $socket->close();
        echo "关闭OK\n";
    }

    public function testSendmsg()
    {
        $ip = '127.0.0.1';
        $port = 1935;
        $socket = new Socket();
        $socket->create(AF_INET, SOCK_STREAM, SOL_TCP);
        $rst = $socket->connect($ip, $port);
        self::assertTrue($rst);
        $message = [
            "Ho",
            "first blood"
        ];
        $bytes = $socket->sendmsg($message, 0);
        var_dump($bytes);
        self::assertIsInt($bytes);
    }

    public function testSendto()
    {
        $ip = '127.0.0.1';
        $port = 1935;
        $socket = new Socket();
        $socket->create(AF_INET, SOCK_STREAM, SOL_TCP);
        $socket->connect($ip, $port);

        $in = "Ho\r\n";
        $in .= "first blood\r\n";
        $bytes = $socket->sendto($in, strlen($in), MSG_DONTROUTE, $ip, $port);
        var_dump($bytes);
        self::assertIsInt($bytes);
        while($out = $socket->read(8192)) {
            echo "接收服务器回传信息成功！\n";
            echo "接受的内容为:",$out;
        }
        echo "关闭SOCKET...\n";
        $socket->close();
        echo "关闭OK\n";
    }

    public function testSetBlock()
    {
        $socket = new Socket();
        $socket->create(AF_INET, SOCK_STREAM, SOL_TCP);
        $rst = $socket->setBlock();
        self::assertTrue($rst);
    }

    public function testSetNonblock()
    {
        $socket = new Socket();
        $socket->create(AF_INET, SOCK_STREAM, SOL_TCP);
        $rst = $socket->setNonblock();
        self::assertTrue($rst);
    }

    public function testSetOption()
    {
        $socket = new Socket();
        $socket->create(AF_INET, SOCK_STREAM, SOL_TCP);
        $rst = $socket->setOption(SOL_SOCKET, SO_REUSEADDR, 1);
        self::assertTrue($rst);
    }

    public function testSetOpt()
    {
        $socket = new Socket();
        $socket->create(AF_INET, SOCK_STREAM, SOL_TCP);
        $rst = $socket->setOpt(SOL_SOCKET, SO_REUSEADDR, 1);
        self::assertTrue($rst);
    }

    public function testShutdown()
    {
        $ip = '127.0.0.1';
        $port = 1935;

        $sock = new Socket();
        $sock->create(AF_INET, SOCK_STREAM, SOL_TCP);
        $sock->bind($ip, $port);
        $sock->listen(4);

        $count = 0;
        do {
            $msgsock = $sock->accept();
            var_dump($msgsock);
            self::assertInstanceOf(Socket::class, $msgsock);
            $msg ="测试成功！\n";
            $msgsock->write($msg, strlen($msg));

            $buf = $msgsock->read(8192);
            $talkback = "收到的信息:$buf\n";
            echo $talkback;
            $rst = $msgsock->shutdown();
            self::assertTrue($rst);
            if(++$count >= 1){
                break;
            }
        } while (true);
        $sock->close();
    }

    public function testStrerror()
    {
        $strerr = Socket::strerror(SOCKET_NO_DATA);
        var_dump($strerr);
        self::assertIsString($strerr);
    }

    public function testWrite()
    {
        $ip = '127.0.0.1';
        $port = 1935;

        $sock = new Socket();
        $sock->create(AF_INET, SOCK_STREAM, SOL_TCP);
        $sock->bind($ip, $port);
        $sock->listen(4);

        $count = 0;
        do {
            $msgsock = $sock->accept();
            var_dump($msgsock);
            self::assertInstanceOf(Socket::class, $msgsock);
            $msg ="测试成功！\n";
            $msgsock->write($msg, strlen($msg));

            $buf = $msgsock->read(8192);
            $talkback = "收到的信息:$buf\n";
            echo $talkback;
            $msgsock->close();
            if(++$count >= 1){
                break;
            }
        } while (true);
        $sock->close();
    }
}
