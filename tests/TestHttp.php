<?php

use fize\net\Http;
use fize\http\Response;
use PHPUnit\Framework\TestCase;

class TestHttp extends TestCase
{

    public function testGet()
    {
        $headers = [
            'accept' => 'application/json'
        ];
        $response = Http::get('https://httpbin.org/get', false, $headers);
        var_dump($response);
        echo $response->getBody();
        self::assertInstanceOf(Response::class, $response);
    }

    public function testPost()
    {
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ];
        $body = [
            'q' => '定制化翻译API语言方向目前只支持中文和英文。',
            'from' => 'zh',
            'to' => 'en',
            'appid' => '20160118000009064',
            'salt' => '123456',
            'sign' => '9ac0dad8ab7abafc710bf5a9a8516e51'
        ];
        $content = Http::post('http://api.fanyi.baidu.com/api/trans/vip/translate', $body, true, $headers);
        echo $content;
        self::assertNotEmpty($content);
    }

    public function testOptions()
    {
        $headers = [
            'accept' => 'application/json'
        ];
        $content = Http::options('https://httpbin.org/options', true, $headers);
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
