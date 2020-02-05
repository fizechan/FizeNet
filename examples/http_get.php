<?php
use fize\net\Http;

require_once './../vendor/autoload.php';

$content = Http::get('https://www.baidu.com');

echo $content;
