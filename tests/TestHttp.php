<?php


use fize\net\Http;
use fize\http\Response;
use PHPUnit\Framework\TestCase;

class TestHttp extends TestCase
{

    public function testGetLastErrCode()
    {
        $content = Http::get('https://www.g-medal.com/1.html');
        var_dump($content);
        $errcode = Http::getLastErrCode();
        var_dump($errcode);
        self::assertNotEmpty($errcode);
    }

    public function testGetLastErrMsg()
    {
        $content = Http::get('https://www.g-medal.com/1.html');
        var_dump($content);
        $errmsg = Http::getLastErrMsg();
        var_dump($errmsg);
        self::assertNotEmpty($errmsg);
    }

    public function testGetLastResponse()
    {
        Http::get('https://www.g-medal.com/1.html');
        $response = Http::getLastResponse();
        var_dump($response);
        self::assertInstanceOf(Response::class, $response);
    }

    public function testConfig()
    {
        Http::config(null, 60, 3);
        self::assertTrue(true);
    }

    public function testSend()
    {
        $headers = [
            'accept' => 'application/json'
        ];
        $content = Http::send('DELETE', 'https://httpbin.org/delete', null, $headers);
        echo $content;
        self::assertNotEmpty($content);
    }

    public function testGet()
    {
        $headers = [
            'accept' => 'application/json'
        ];
        $content = Http::get('https://httpbin.org/get', $headers);
        echo $content;
        self::assertNotEmpty($content);
    }

    public function testPost()
    {
        $headers = [
            'accept' => 'application/json'
        ];
        $body = [
            'kkk' => '中文测试',
            'KK2' => '133'
        ];
        $content = Http::post('https://httpbin.org/post', $body, $headers);
        echo $content;
        self::assertNotEmpty($content);
    }

    public function testOptions()
    {
        $headers = [
            'accept' => 'application/json'
        ];
        $content = Http::options('https://httpbin.org/options', $headers);
        echo $content;
        self::assertNotEmpty($content);
    }

    public function testDelete()
    {

    }



    public function testWrapped()
    {

    }

    public function testUnlink()
    {

    }

    public function testTrace()
    {

    }

    public function testLink()
    {

    }

    public function testMove()
    {

    }

    public function testCopy()
    {

    }

    public function testHead()
    {

    }

    public function testPatch()
    {

    }

    public function testPut()
    {

    }
}
