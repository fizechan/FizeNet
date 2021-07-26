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
        $response = Http::get('https://httpbin.org/get', $headers);
        $content = $response->getBody()->getContents();
        var_dump($content);
        self::assertIsString($content);
    }
}
