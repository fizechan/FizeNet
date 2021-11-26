<?php
use Fize\Net\Http;

require_once './../../vendor/autoload.php';

$response = Http::get('https://www.baidu.com');
$content = $response->getBody()->getContents();
echo $content;
