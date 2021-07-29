<?php
use fize\net\Http;

require_once './../../vendor/autoload.php';

$response = Http::get('https://www.baidu.com');
$content = $response->getBody()->getContents();
echo $content;
