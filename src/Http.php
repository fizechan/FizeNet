<?php

namespace fize\net;

use CURLFile;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use fize\http\Client;
use fize\http\Request;
use fize\http\Response;

/**
 * Http 客户端
 */
class Http
{

    /**
     * 禁止实例化
     */
    private function __construct()
    {
    }

    /**
     * GET 请求
     * @param string $uri         指定链接
     * @param bool   $return_body 是否返回主体内容
     * @param array  $headers     附加的文件头
     * @param array  $opts        参数配置数组
     * @param array  $config      客户端配置 ['cookie_dir' => *, 'time_out' => *, 'retries' => *]
     * @return Response|string $return_body 为 true 时返回响应主体内容，否则返回响应对象
     */
    public static function get($uri, $return_body = false, array $headers = [], array $opts = [], array $config = [])
    {
        return self::send('GET', $uri, null, $return_body, $headers, $opts, $config);
    }

    /**
     * POST 请求
     * @param string                                     $uri         指定链接
     * @param string|null|resource|StreamInterface|array $body        请求体
     * @param bool                                       $return_body 是否返回主体内容
     * @param array                                      $headers     设定请求头设置
     * @param array                                      $opts        参数配置数组
     * @param array                                      $config      客户端配置 ['cookie_dir' => *, 'time_out' => *, 'retries' => *]
     * @return Response|string $return_body 为 true 时返回响应主体内容，否则返回响应对象
     */
    public static function post($uri, $body, $return_body = false, array $headers = [], array $opts = [], array $config = [])
    {
        return self::sendPostFields('POST', $uri, $body, $return_body, $headers, $opts, $config);
    }

    /**
     * OPTIONS 请求
     * @param string $uri         指定链接
     * @param bool   $return_body 是否返回主体内容
     * @param array  $headers     设定请求头设置
     * @param array  $opts        参数配置数组
     * @param array  $config      客户端配置 ['cookie_dir' => *, 'time_out' => *, 'retries' => *]
     * @return Response|string $return_body 为 true 时返回响应主体内容，否则返回响应对象
     */
    public static function options($uri, $return_body = false, array $headers = [], array $opts = [], array $config = [])
    {
        return self::send('OPTIONS', $uri, null, $return_body, $headers, $opts, $config);
    }

    /**
     * HEAD 请求
     * @param string $uri         指定链接
     * @param bool   $return_body 是否返回主体内容
     * @param array  $headers     设定请求头设置
     * @param array  $opts        参数配置数组
     * @param array  $config      客户端配置 ['cookie_dir' => *, 'time_out' => *, 'retries' => *]
     * @return Response|string $return_body 为 true 时返回响应主体内容，否则返回响应对象
     */
    public static function head($uri, $return_body = false, array $headers = [], array $opts = [], array $config = [])
    {
        return self::send('HEAD', $uri, null, $return_body, $headers, $opts, $config);
    }

    /**
     * DELETE 请求
     * @param string $uri         指定链接
     * @param bool   $return_body 是否返回主体内容
     * @param array  $headers     设定请求头设置
     * @param array  $opts        参数配置数组
     * @param array  $config      客户端配置 ['cookie_dir' => *, 'time_out' => *, 'retries' => *]
     * @return Response|string $return_body 为 true 时返回响应主体内容，否则返回响应对象
     */
    public static function delete($uri, $return_body = false, array $headers = [], array $opts = [], array $config = [])
    {
        return self::send('DELETE', $uri, null, $return_body, $headers, $opts, $config);
    }

    /**
     * PATCH 请求
     * @param string $uri         指定链接
     * @param bool   $return_body 是否返回主体内容
     * @param array  $headers     设定请求头设置
     * @param array  $opts        参数配置数组
     * @param array  $config      客户端配置 ['cookie_dir' => *, 'time_out' => *, 'retries' => *]
     * @return Response|string $return_body 为 true 时返回响应主体内容，否则返回响应对象
     */
    public static function patch($uri, $return_body = false, array $headers = [], array $opts = [], array $config = [])
    {
        return self::send('PATCH', $uri, null, $return_body, $headers, $opts, $config);
    }

    /**
     * PUT 请求
     * @param string                                     $uri         指定链接
     * @param string|null|resource|StreamInterface|array $body        请求体
     * @param bool                                       $return_body 是否返回主体内容
     * @param array                                      $headers     设定请求头设置
     * @param array                                      $opts        参数配置数组
     * @param array                                      $config      客户端配置 ['cookie_dir' => *, 'time_out' => *, 'retries' => *]
     * @return Response|string $return_body 为 true 时返回响应主体内容，否则返回响应对象
     */
    public static function put($uri, $body = '', $return_body = false, array $headers = [], array $opts = [], array $config = [])
    {
        return self::sendPostFields('PUT', $uri, $body, $return_body, $headers, $opts, $config);
    }

    /**
     * TRACE 请求
     * @param string $uri         指定链接
     * @param bool   $return_body 是否返回主体内容
     * @param array  $headers     设定请求头设置
     * @param array  $opts        参数配置数组
     * @param array  $config      客户端配置 ['cookie_dir' => *, 'time_out' => *, 'retries' => *]
     * @return Response|string $return_body 为 true 时返回响应主体内容，否则返回响应对象
     */
    public static function trace($uri, $return_body = false, array $headers = [], array $opts = [], array $config = [])
    {
        return self::send('TRACE', $uri, null, $return_body, $headers, $opts, $config);
    }

    /**
     * MOVE 请求
     * @param string $uri         指定链接
     * @param bool   $return_body 是否返回主体内容
     * @param array  $headers     设定请求头设置
     * @param array  $opts        参数配置数组
     * @param array  $config      客户端配置 ['cookie_dir' => *, 'time_out' => *, 'retries' => *]
     * @return Response|string $return_body 为 true 时返回响应主体内容，否则返回响应对象
     */
    public static function move($uri, $return_body = false, array $headers = [], array $opts = [], array $config = [])
    {
        return self::send('MOVE', $uri, null, $return_body, $headers, $opts, $config);
    }

    /**
     * COPY 请求
     * @param string $uri         指定链接
     * @param bool   $return_body 是否返回主体内容
     * @param array  $headers     设定请求头设置
     * @param array  $opts        参数配置数组
     * @param array  $config      客户端配置 ['cookie_dir' => *, 'time_out' => *, 'retries' => *]
     * @return Response|string $return_body 为 true 时返回响应主体内容，否则返回响应对象
     */
    public static function copy($uri, $return_body = false, array $headers = [], array $opts = [], array $config = [])
    {
        return self::send('COPY', $uri, null, $return_body, $headers, $opts, $config);
    }

    /**
     * LINK 请求
     * @param string $uri         指定链接
     * @param bool   $return_body 是否返回主体内容
     * @param array  $headers     设定请求头设置
     * @param array  $opts        参数配置数组
     * @param array  $config      客户端配置 ['cookie_dir' => *, 'time_out' => *, 'retries' => *]
     * @return Response|string $return_body 为 true 时返回响应主体内容，否则返回响应对象
     */
    public static function link($uri, $return_body = false, array $headers = [], array $opts = [], array $config = [])
    {
        return self::send('LINK', $uri, null, $return_body, $headers, $opts, $config);
    }

    /**
     * UNLINK 请求
     * @param string $uri         指定链接
     * @param bool   $return_body 是否返回主体内容
     * @param array  $headers     设定请求头设置
     * @param array  $opts        参数配置数组
     * @param array  $config      客户端配置 ['cookie_dir' => *, 'time_out' => *, 'retries' => *]
     * @return Response|string $return_body 为 true 时返回响应主体内容，否则返回响应对象
     */
    public static function unlink($uri, $return_body = false, array $headers = [], array $opts = [], array $config = [])
    {
        return self::send('UNLINK', $uri, null, $return_body, $headers, $opts, $config);
    }

    /**
     * WRAPPED 请求
     * @param string $uri         指定链接
     * @param bool   $return_body 是否返回主体内容
     * @param array  $headers     设定请求头设置
     * @param array  $opts        参数配置数组
     * @param array  $config      客户端配置 ['cookie_dir' => *, 'time_out' => *, 'retries' => *]
     * @return Response|string $return_body 为 true 时返回响应主体内容，否则返回响应对象
     */
    public static function wrapped($uri, $return_body = false, array $headers = [], array $opts = [], array $config = [])
    {
        return self::send('WRAPPED', $uri, null, $return_body, $headers, $opts, $config);
    }

    /**
     * 简易 HTTP 客户端
     * @param string                               $method      请求方式
     * @param string|UriInterface                  $uri         请求URI
     * @param string|null|resource|StreamInterface $body        请求体
     * @param bool                                 $return_body 是否返回主体内容
     * @param array                                $headers     报头信息
     * @param array                                $opts        CURL选项
     * @param array                                $config      客户端配置 ['cookie_dir' => *, 'time_out' => *, 'retries' => *]
     * @return Response|string $return_body 为 true 时返回响应主体内容，否则返回响应对象
     */
    private static function send($method, $uri, $body = null, $return_body = false, array $headers = [], array $opts = [], array $config = [])
    {
        $cookie_dir = isset($config['cookie_dir']) ? $config['cookie_dir'] : null;
        $time_out = isset($config['time_out']) ? $config['time_out'] : 30;
        $retries = isset($config['retries']) ? $config['retries'] : 1;
        $client = new Client($cookie_dir, $time_out, $retries);
        if ($opts) {
            $client->setOptions($opts);
        }
        $request = new Request($method, $uri, $body, $headers);
        $response = $client->sendRequest($request);
        if ($return_body === false) {
            return $response;
        }
        return (string)$response->getBody();
    }

    /**
     * 发送带请求体数据
     * @param string                               $method      请求方式
     * @param string|UriInterface                  $uri         请求URI
     * @param string|null|resource|StreamInterface $body        请求体
     * @param bool                                 $return_body 是否返回主体内容
     * @param array                                $headers     报头信息
     * @param array                                $opts        CURL选项
     * @param array                                $config      客户端配置 ['cookie_dir' => *, 'time_out' => *, 'retries' => *]
     * @return Response|string $return_body 为 true 时返回响应主体内容，否则返回响应对象
     */
    private static function sendPostFields($method, $uri, $body = null, $return_body = false, array $headers = [], array $opts = [], array $config = [])
    {
        if (is_string($body)) {
            $strPOST = $body;
        } else {
            if (self::isUploadFile($body)) {
                $strPOST = $body;  //需要POST上传文件时直接传递数组
            } else {
                $strPOST = http_build_query($body);
            }
        }
        if (is_array($body)) {
            $add_opts = [
                CURLOPT_POSTFIELDS => $strPOST
            ];
            if (self::isUploadFile($body)) {
                $add_opts[CURLOPT_UPLOAD] = true;
            }
            $opts = $opts + $add_opts;
            $body = null;  //使用CURL直接传递body
        }
        return self::send($method, $uri, $body, $return_body, $headers, $opts, $config);
    }

    /**
     * 判断上传的东西是否包含文件上传
     * @param mixed $body 请求体
     * @return bool
     */
    private static function isUploadFile($body)
    {
        if (!is_array($body)) {
            return false;
        }
        foreach ($body as $val) {
            if ($val instanceof CURLFile) {
                return true;
            }
        }
        return false;
    }
}
