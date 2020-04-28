<?php

namespace fize\net;

use CURLFile;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use fize\http\Client;
use fize\http\ClientException;
use fize\http\Request;
use fize\http\Response;

/**
 * Http 客户端
 */
class Http
{

    /**
     * @var int 错误代码
     */
    private static $errCode = 0;

    /**
     * @var string 错误描述
     */
    private static $errMsg = "";

    /**
     * @var Response 响应
     */
    private static $response;

    /**
     * @var string COOKIE保存文件夹
     */
    private static $cookieDir = null;

    /**
     * @var int 连接超时秒数
     */
    private static $timeOut = 30;

    /**
     * @var int 重试次数
     */
    private static $retries = 1;

    /**
     * 禁止实例化
     */
    private function __construct()
    {
    }

    /**
     * 获取最后的错误代码
     * @return int
     */
    public static function getLastErrCode()
    {
        return self::$errCode;
    }

    /**
     * 获取最后的错误描述
     * @return string
     */
    public static function getLastErrMsg()
    {
        return self::$errMsg;
    }

    /**
     * 获取最后的响应对象
     * @return Response
     */
    public static function getLastResponse()
    {
        return self::$response;
    }

    /**
     * 设置公共参数
     * @param string  $cookie_dir 指定保存COOKIE文件的路径，默认null表示不使用COOKIE
     * @param int     $time_out   设定超时时间,默认30秒
     * @param integer $retries    curl重试次数
     */
    public static function config($cookie_dir = null, $time_out = 30, $retries = 1)
    {
        self::$cookieDir = $cookie_dir;
        self::$timeOut = $time_out;
        self::$retries = $retries;
    }

    /**
     * 简易 HTTP 客户端
     * @param string                               $method  请求方式
     * @param string|UriInterface                  $uri     请求URI
     * @param string|null|resource|StreamInterface $body    请求体
     * @param array                                $headers 报头信息
     * @param array                                $opts    CURL选项
     * @return string|false 返回响应内容，失败是返回false
     */
    public static function send($method, $uri, $body = null, array $headers = [], array $opts = [])
    {
        $client = new Client(self::$cookieDir, self::$timeOut, self::$retries);
        if ($opts) {
            $client->setOptions($opts);
        }
        $request = new Request($method, $uri, $body, $headers);
        try {
            $response = $client->sendRequest($request);
            self::$response = $response;
            if ($response->getStatusCode() != 200) {
                self::$errCode = $response->getStatusCode();
                self::$errMsg = $response->getReasonPhrase();
                return false;
            }
            return (string)$response->getBody();
        } catch (ClientException $e) {
            self::$errCode = $e->getCode();
            self::$errMsg = $e->getMessage();
            return false;
        }
    }

    /**
     * GET 请求
     * @param string $uri     指定链接
     * @param array  $headers 附加的文件头
     * @param array  $opts    参数配置数组
     * @return string|false 返回响应内容，失败是返回false
     */
    public static function get($uri, array $headers = [], array $opts = [])
    {
        return self::send('GET', $uri, null, $headers, $opts);
    }

    /**
     * POST 请求
     * @param string                                     $uri     指定链接
     * @param string|null|resource|StreamInterface|array $body    请求体
     * @param array                                      $headers 设定请求头设置
     * @param array                                      $opts    参数配置数组
     * @return string|false 返回响应内容，失败是返回false
     */
    public static function post($uri, $body, array $headers = [], array $opts = [])
    {
        return self::sendPostFields('POST', $uri, $body, $headers, $opts);
    }

    /**
     * OPTIONS 请求
     * @param string $uri     指定链接
     * @param array  $headers 设定请求头设置
     * @param array  $opts    参数配置数组
     * @return string|false 返回响应内容，失败是返回false
     */
    public static function options($uri, array $headers = [], array $opts = [])
    {
        return self::send('OPTIONS', $uri, null, $headers, $opts);
    }

    /**
     * HEAD 请求
     * @param string $uri     指定链接
     * @param array  $headers 设定请求头设置
     * @param array  $opts    参数配置数组
     * @return string|false 返回响应内容，失败是返回false
     */
    public static function head($uri, array $headers = [], array $opts = [])
    {
        return self::send('HEAD', $uri, null, $headers, $opts);
    }

    /**
     * DELETE 请求
     * @param string $uri     指定链接
     * @param array  $headers 设定请求头设置
     * @param array  $opts    参数配置数组
     * @return string|false 返回响应内容，失败是返回false
     */
    public static function delete($uri, array $headers = [], array $opts = [])
    {
        return self::send('DELETE', $uri, null, $headers, $opts);
    }

    /**
     * PATCH 请求
     * @param string $uri     指定链接
     * @param array  $headers 设定请求头设置
     * @param array  $opts    参数配置数组
     * @return string|false 返回响应内容，失败是返回false
     */
    public static function patch($uri, array $headers = [], array $opts = [])
    {
        return self::send('PATCH', $uri, null, $headers, $opts);
    }

    /**
     * PUT 请求
     * @param string                                     $uri     指定链接
     * @param string|null|resource|StreamInterface|array $body    请求体
     * @param array                                      $headers 设定请求头设置
     * @param array                                      $opts    参数配置数组
     * @return string|false 返回响应内容，失败是返回false
     */
    public static function put($uri, $body = '', array $headers = [], array $opts = [])
    {
        return self::sendPostFields('PUT', $uri, $body, $headers, $opts);
    }

    /**
     * TRACE 请求
     * @param string $uri     指定链接
     * @param array  $headers 设定请求头设置
     * @param array  $opts    参数配置数组
     * @return string|false 返回响应内容，失败是返回false
     */
    public static function trace($uri, array $headers = [], array $opts = [])
    {
        return self::send('TRACE', $uri, null, $headers, $opts);
    }

    /**
     * MOVE 请求
     * @param string $uri     指定链接
     * @param array  $headers 设定请求头设置
     * @param array  $opts    参数配置数组
     * @return string|false 返回响应内容，失败是返回false
     */
    public static function move($uri, array $headers = [], array $opts = [])
    {
        return self::send('MOVE', $uri, null, $headers, $opts);
    }

    /**
     * COPY 请求
     * @param string $uri     指定链接
     * @param array  $headers 设定请求头设置
     * @param array  $opts    参数配置数组
     * @return string|false 返回响应内容，失败是返回false
     */
    public static function copy($uri, array $headers = [], array $opts = [])
    {
        return self::send('COPY', $uri, null, $headers, $opts);
    }

    /**
     * LINK 请求
     * @param string $uri     指定链接
     * @param array  $headers 设定请求头设置
     * @param array  $opts    参数配置数组
     * @return string|false 返回响应内容，失败是返回false
     */
    public static function link($uri, array $headers = [], array $opts = [])
    {
        return self::send('LINK', $uri, null, $headers, $opts);
    }

    /**
     * UNLINK 请求
     * @param string $uri     指定链接
     * @param array  $headers 设定请求头设置
     * @param array  $opts    参数配置数组
     * @return string|false 返回响应内容，失败是返回false
     */
    public static function unlink($uri, array $headers = [], array $opts = [])
    {
        return self::send('UNLINK', $uri, null, $headers, $opts);
    }

    /**
     * WRAPPED 请求
     * @param string $uri     指定链接
     * @param array  $headers 设定请求头设置
     * @param array  $opts    参数配置数组
     * @return string|false 返回响应内容，失败是返回false
     */
    public static function wrapped($uri, array $headers = [], array $opts = [])
    {
        return self::send('WRAPPED', $uri, null, $headers, $opts);
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

    /**
     * 发送带请求体数据
     * @param string                               $method  请求方式
     * @param string|UriInterface                  $uri     请求URI
     * @param string|null|resource|StreamInterface $body    请求体
     * @param array                                $headers 报头信息
     * @param array                                $opts    CURL选项
     * @return string|false 返回响应内容，失败是返回false
     */
    private static function sendPostFields($method, $uri, $body = null, array $headers = [], array $opts = [])
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
        return self::send($method, $uri, $body, $headers, $opts);
    }
}
