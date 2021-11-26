<?php

namespace Tests;

use Fize\Net\Ftp;
use PHPUnit\Framework\TestCase;

class TestFtp extends TestCase
{

    public function test__construct()
    {
        $ftp = new Ftp('192.168.56.101');
        var_dump($ftp);
        self::assertInstanceOf(Ftp::class, $ftp);
    }

    public function test__destruct()
    {
        $ftp = new Ftp('192.168.56.101');
        self::assertInstanceOf(Ftp::class, $ftp);
        unset($ftp);
    }

    public function testAlloc()
    {
        $ftp = new Ftp('192.168.56.101', 'ftp01', '123456');
        $rst = $ftp->alloc(100, $result);
        var_dump($result);
        self::assertTrue($rst);
    }

    public function testCdup()
    {
        $ftp = new Ftp('192.168.56.101', 'ftp01', '123456');
        $ftp->chdir('中文目录');
        $ftp->chdir('中文目录2');
        $rst = $ftp->cdup();
        self::assertTrue($rst);
    }

    public function testChdir()
    {
        $ftp = new Ftp('192.168.56.101', 'ftp01', '123456');
        $rst = $ftp->chdir('中文目录');
        self::assertTrue($rst);
    }

    public function testChmod()
    {
        $ftp = new Ftp('192.168.56.101', 'ftp01', '123456');
        $ii = $ftp->chmod(0777, '中文目录');
        var_dump($ii);
        self::assertIsInt($ii);
    }

    public function testClose()
    {
        $ftp = new Ftp('192.168.56.101', 'ftp01', '123456');
        $rst = $ftp->close();
        self::assertTrue($rst);
    }

    public function testConnect()
    {
        $ftp = new Ftp();
        $ftp->connect('192.168.56.101', 'ftp01', '123456');
        $rst = $ftp->close();
        self::assertTrue($rst);
    }

    public function testDelete()
    {
        $ftp = new Ftp('192.168.56.101', 'ftp01', '123456');
        $ftp->chdir('中文目录3');
        $rst = $ftp->delete('测试.txt');
        self::assertTrue($rst);
    }

    public function testExec()
    {
        $command = 'CHMOD 0600 /localfile.txt';
        $ftp = new Ftp('192.168.56.101', 'ftp01', '123456');
        $ftp->pasv(true);
        $rst = $ftp->exec($command);
        self::assertTrue($rst);
    }

    public function testFget()
    {
        $ftp = new Ftp('192.168.56.101', 'ftp01', '123456');
        $ftp->pasv(true);
        $local_file = dirname(__DIR__) . '/temp/localfile.txt';
        $fhandle = fopen($local_file, 'w');
        $ftp->chdir('中文目录');
        $rst = $ftp->fget($fhandle, '测试222.txt');
        self::assertTrue($rst);
        fclose($fhandle);
        self::assertFileExists($local_file);
    }

    public function testFput()
    {
        $ftp = new Ftp('192.168.56.101', 'ftp01', '123456');
        $ftp->pasv(true);
        $local_file = dirname(__DIR__) . '/temp/localfile.txt';
        $fhandle = fopen($local_file, 'r');
        $rst = $ftp->fput('localfile.txt', $fhandle);
        self::assertTrue($rst);
    }

    public function testGetOption()
    {
        $ftp = new Ftp('192.168.56.101', 'ftp01', '123456');
        $ftp->pasv(true);
        $val = $ftp->getOption(FTP_TIMEOUT_SEC);
        var_dump($val);
        self::assertIsInt($val);
    }

    public function testGet()
    {
        $ftp = new Ftp('192.168.56.101', 'ftp01', '123456');
        $ftp->pasv(true);
        $local_file = dirname(__DIR__) . '/temp/localfile2.txt';
        $rst = $ftp->get($local_file, 'localfile.txt');
        self::assertTrue($rst);
    }

    public function testLogin()
    {
        $ftp = new Ftp('192.168.56.101');
        $rst = $ftp->login('ftp01', '123456');
        self::assertTrue($rst);
    }

    public function testMdtm()
    {
        $ftp = new Ftp('192.168.56.101', 'ftp01', '123456');
        $ftp->pasv(true);
        $mdtm = $ftp->mdtm('localfile.txt');
        var_dump($mdtm);
        self::assertIsInt($mdtm);
    }

    public function testMkdir()
    {
        $ftp = new Ftp('192.168.56.101', 'ftp01', '123456');
        $ftp->chdir('中文目录');
        $name = $ftp->mkdir('中文目录2');
        var_dump($name);
        self::assertIsString($name);
    }

    public function testNbContinue()
    {
        $ftp = new Ftp('192.168.56.101', 'ftp01', '123456');
        $ftp->pasv(true);
        $local_file = dirname(__DIR__) . '/temp/localfile3.txt';
        $ret = $ftp->nbGet($local_file, "localfile.txt", FTP_BINARY);
        while ($ret == FTP_MOREDATA) {

            // Continue downloading...
            $ret = $ftp->nbContinue();
            self::assertIsInt($ret);
        }
        if ($ret != FTP_FINISHED) {
            echo "There was an error downloading the file...";
        }
    }

    public function testNbFget()
    {
        $ftp = new Ftp('192.168.56.101', 'ftp01', '123456');
        $ftp->pasv(true);
        $local_file = dirname(__DIR__) . '/temp/localfile4.txt';
        $fhandle = fopen($local_file, 'w');
        $ret = $ftp->nbFget($fhandle, "localfile.txt", FTP_BINARY);
        var_dump($ret);
        self::assertIsInt($ret);
    }

    public function testNbFput()
    {
        $ftp = new Ftp('192.168.56.101', 'ftp01', '123456');
        $ftp->pasv(true);
        $local_file = dirname(__DIR__) . '/temp/localfile.txt';
        $fhandle = fopen($local_file, 'r');
        $rst = $ftp->nbFput('localfile5.txt', $fhandle);
        var_dump($rst);
        self::assertIsInt($rst);
    }

    public function testNbGet()
    {
        $ftp = new Ftp('192.168.56.101', 'ftp01', '123456');
        $ftp->pasv(true);
        $local_file = dirname(__DIR__) . '/temp/localfile6.txt';
        $ret = $ftp->nbGet($local_file, "localfile.txt", FTP_BINARY);
        var_dump($ret);
        self::assertIsInt($ret);
    }

    public function testNbPut()
    {
        $ftp = new Ftp('192.168.56.101', 'ftp01', '123456');
        $ftp->pasv(true);
        $local_file = dirname(__DIR__) . '/temp/localfile.txt';
        $rst = $ftp->nbPut('localfile7.txt', $local_file);
        var_dump($rst);
        self::assertIsInt($rst);
    }

    public function testNlist()
    {
        $ftp = new Ftp('192.168.56.101', 'ftp01', '123456');
        $ftp->pasv(true);
        $ftp->chdir('中文目录');
        $nlist = $ftp->nlist();
        var_dump($nlist);
        self::assertIsArray($nlist);
    }

    public function testPasv()
    {
        $ftp = new Ftp('192.168.56.101', 'ftp01', '123456');
        $rst = $ftp->pasv(true);
        self::assertTrue($rst);
    }

    public function testPut()
    {
        $ftp = new Ftp('192.168.56.101', 'ftp01', '123456');
        $ftp->pasv(true);
        $local_file = dirname(__DIR__) . '/temp/localfile.txt';
        $rst = $ftp->put('localfile8.txt', $local_file);
        var_dump($rst);
        self::assertTrue($rst);
    }

    public function testPwd()
    {
        $ftp = new Ftp('192.168.56.101', 'ftp01', '123456');
        $ftp->pasv(true);
        $pwd1 = $ftp->pwd();
        var_dump($pwd1);
        $ftp->chdir('中文目录');
        $pwd2 = $ftp->pwd();
        var_dump($pwd2);
        self::assertNotEquals($pwd1, $pwd2);
    }

    public function testQuit()
    {
        $ftp = new Ftp('192.168.56.101', 'ftp01', '123456');
        $ftp->pasv(true);
        $rst = $ftp->quit();
        self::assertTrue($rst);
    }

    public function testRaw()
    {
        $ftp = new Ftp('192.168.56.101', 'ftp01', '123456');
        $rst = $ftp->raw('PASV');
        var_dump($rst);
        self::assertIsArray($rst);
    }

    public function testRawlist()
    {
        $ftp = new Ftp('192.168.56.101', 'ftp01', '123456');
        $ftp->pasv(true);
        $rl = $ftp->rawlist('/中文目录', true);
        var_dump($rl);
        self::assertIsArray($rl);
        $rl = $ftp->rawlist('/中文目录');
        var_dump($rl);
    }

    public function testRename()
    {
        $ftp = new Ftp('192.168.56.101', 'ftp01', '123456');
        $ftp->pasv(true);
        $rst = $ftp->rename('localfile8.txt', '/中文目录/localfile9.txt');
        self::assertTrue($rst);
    }

    public function testRmdir()
    {
        $ftp = new Ftp('192.168.56.101', 'ftp01', '123456');
        $ftp->pasv(true);
//        $rst = $ftp->rmdir('中文目录');  // 非空会报错
//        self::assertFalse($rst);
        $rst = $ftp->rmdir('中文目录', true);
        self::assertTrue($rst);
    }

    public function testSetOption()
    {
        $ftp = new Ftp('192.168.56.101', 'ftp01', '123456');
        $ftp->pasv(true);
        $rst = $ftp->setOption(FTP_TIMEOUT_SEC, 100);
        self::assertTrue($rst);
    }

    public function testSite()
    {
        $ftp = new Ftp('192.168.56.101', 'ftp01', '123456');
        $ftp->pasv(true);
        $command = 'CHMOD 0600 /localfile.txt';
        $rst = $ftp->site($command);
        self::assertTrue($rst);
    }

    public function testSize()
    {
        $ftp = new Ftp('192.168.56.101', 'ftp01', '123456');
        $ftp->pasv(true);
        $size = $ftp->size('/localfile.txt');
        var_dump($size);
        self::assertIsInt($size);
    }

    public function testSslConnect()
    {
        $ftp = new Ftp();
        $ftp->sslConnect('192.168.56.101', 'ftp01', '123456');
        $rst = $ftp->close();
        self::assertTrue($rst);
    }

    public function testSystype()
    {
        $ftp = new Ftp('192.168.56.101', 'ftp01', '123456');
        $ftp->pasv(true);
        $systype = $ftp->systype();
        var_dump($systype);
        self::assertIsString($systype);
    }
}
