<?php

use fize\net\Http;
use PHPUnit\Framework\TestCase;

class TestHttp extends TestCase
{

    public function testGet()
    {
        $headers = [
            'accept' => 'application/json'
        ];
        $content = Http::get('https://httpbin.org/get', $headers);
        var_dump($content);
        self::assertIsString($content);
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
        $content = Http::post('http://api.fanyi.baidu.com/api/trans/vip/translate', $body, $headers);
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
