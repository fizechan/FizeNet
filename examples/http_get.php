<?php
use fize\net\Http;

require_once './../vendor/autoload.php';

$http = new Http('./data/cookie/baidu');
$content = $http->get('https://www.baidu.com');

echo $content;