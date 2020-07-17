<?php

namespace fize\net;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use fize\http\ClientSimple;

/**
 * Http 简易客户端
 *
 * 本简易客户端仅返回响应主体内容，如果需要返回完整响应体请使用ClientSimple
 */
class Http extends ClientSimple
{

    /**
     * GET 请求
     *
     * 参数 `$config` :
     *   ['cookie_dir' => *, 'time_out' => *, 'retries' => *]
     * @param string $uri     指定链接
     * @param array  $headers 附加的文件头
     * @param array  $opts    参数配置数组
     * @param array  $config  客户端配置
     * @return string 返回响应主体内容
     */
    public static function get($uri, array $headers = [], array $opts = [], array $config = [])
    {
        return self::send('GET', $uri, null, $headers, $opts, $config);
    }

    /**
     * POST 请求
     *
     * 参数 `$config` :
     *   ['cookie_dir' => *, 'time_out' => *, 'retries' => *]
     * @param string                                $uri     指定链接
     * @param string|resource|StreamInterface|array $body    请求体
     * @param array                                 $headers 设定请求头设置
     * @param array                                 $opts    参数配置数组
     * @param array                                 $config  客户端配置
     * @return string 返回响应主体内容
     */
    public static function post($uri, $body, array $headers = [], array $opts = [], array $config = [])
    {
        return self::send('POST', $uri, $body, $headers, $opts, $config);
    }

    /**
     * OPTIONS 请求
     *
     * 参数 `$config` :
     *   ['cookie_dir' => *, 'time_out' => *, 'retries' => *]
     * @param string $uri     指定链接
     * @param array  $headers 设定请求头设置
     * @param array  $opts    参数配置数组
     * @param array  $config  客户端配置
     * @return string 返回响应主体内容
     */
    public static function options($uri, array $headers = [], array $opts = [], array $config = [])
    {
        return self::send('OPTIONS', $uri, null, $headers, $opts, $config);
    }

    /**
     * HEAD 请求
     *
     * 参数 `$config` :
     *   ['cookie_dir' => *, 'time_out' => *, 'retries' => *]
     * @param string $uri     指定链接
     * @param array  $headers 设定请求头设置
     * @param array  $opts    参数配置数组
     * @param array  $config  客户端配置
     * @return string 返回响应主体内容
     */
    public static function head($uri, array $headers = [], array $opts = [], array $config = [])
    {
        return self::send('HEAD', $uri, null, $headers, $opts, $config);
    }

    /**
     * DELETE 请求
     *
     * 参数 `$config` :
     *   ['cookie_dir' => *, 'time_out' => *, 'retries' => *]
     * @param string $uri     指定链接
     * @param array  $headers 设定请求头设置
     * @param array  $opts    参数配置数组
     * @param array  $config  客户端配置
     * @return string 返回响应主体内容
     */
    public static function delete($uri, array $headers = [], array $opts = [], array $config = [])
    {
        return self::send('DELETE', $uri, null, $headers, $opts, $config);
    }

    /**
     * PATCH 请求
     *
     * 参数 `$config` :
     *   ['cookie_dir' => *, 'time_out' => *, 'retries' => *]
     * @param string $uri     指定链接
     * @param array  $headers 设定请求头设置
     * @param array  $opts    参数配置数组
     * @param array  $config  客户端配置
     * @return string 返回响应主体内容
     */
    public static function patch($uri, array $headers = [], array $opts = [], array $config = [])
    {
        return self::send('PATCH', $uri, null, $headers, $opts, $config);
    }

    /**
     * PUT 请求
     *
     * 参数 `$config` :
     *   ['cookie_dir' => *, 'time_out' => *, 'retries' => *]
     * @param string                                $uri     指定链接
     * @param string|resource|StreamInterface|array $body    请求体
     * @param array                                 $headers 设定请求头设置
     * @param array                                 $opts    参数配置数组
     * @param array                                 $config  客户端配置
     * @return string 返回响应主体内容
     */
    public static function put($uri, $body = '', array $headers = [], array $opts = [], array $config = [])
    {
        return self::send('PUT', $uri, $body, $headers, $opts, $config);
    }

    /**
     * TRACE 请求
     *
     * 参数 `$config` :
     *   ['cookie_dir' => *, 'time_out' => *, 'retries' => *]
     * @param string $uri     指定链接
     * @param array  $headers 设定请求头设置
     * @param array  $opts    参数配置数组
     * @param array  $config  客户端配置
     * @return string 返回响应主体内容
     */
    public static function trace($uri, array $headers = [], array $opts = [], array $config = [])
    {
        return self::send('TRACE', $uri, null, $headers, $opts, $config);
    }

    /**
     * MOVE 请求
     *
     * 参数 `$config` :
     *   ['cookie_dir' => *, 'time_out' => *, 'retries' => *]
     * @param string $uri     指定链接
     * @param array  $headers 设定请求头设置
     * @param array  $opts    参数配置数组
     * @param array  $config  客户端配置
     * @return string 返回响应主体内容
     */
    public static function move($uri, array $headers = [], array $opts = [], array $config = [])
    {
        return self::send('MOVE', $uri, null, $headers, $opts, $config);
    }

    /**
     * COPY 请求
     *
     * 参数 `$config` :
     *   ['cookie_dir' => *, 'time_out' => *, 'retries' => *]
     * @param string $uri     指定链接
     * @param array  $headers 设定请求头设置
     * @param array  $opts    参数配置数组
     * @param array  $config  客户端配置
     * @return string 返回响应主体内容
     */
    public static function copy($uri, array $headers = [], array $opts = [], array $config = [])
    {
        return self::send('COPY', $uri, null, $headers, $opts, $config);
    }

    /**
     * LINK 请求
     *
     * 参数 `$config` :
     *   ['cookie_dir' => *, 'time_out' => *, 'retries' => *]
     * @param string $uri     指定链接
     * @param array  $headers 设定请求头设置
     * @param array  $opts    参数配置数组
     * @param array  $config  客户端配置
     * @return string 返回响应主体内容
     */
    public static function link($uri, array $headers = [], array $opts = [], array $config = [])
    {
        return self::send('LINK', $uri, null, $headers, $opts, $config);
    }

    /**
     * UNLINK 请求
     *
     * 参数 `$config` :
     *   ['cookie_dir' => *, 'time_out' => *, 'retries' => *]
     * @param string $uri     指定链接
     * @param array  $headers 设定请求头设置
     * @param array  $opts    参数配置数组
     * @param array  $config  客户端配置
     * @return string 返回响应主体内容
     */
    public static function unlink($uri, array $headers = [], array $opts = [], array $config = [])
    {
        return self::send('UNLINK', $uri, null, $headers, $opts, $config);
    }

    /**
     * WRAPPED 请求
     *
     * 参数 `$config` :
     *   ['cookie_dir' => *, 'time_out' => *, 'retries' => *]
     * @param string $uri     指定链接
     * @param array  $headers 设定请求头设置
     * @param array  $opts    参数配置数组
     * @param array  $config  客户端配置
     * @return string 返回响应主体内容
     */
    public static function wrapped($uri, array $headers = [], array $opts = [], array $config = [])
    {
        return self::send('WRAPPED', $uri, null, $headers, $opts, $config);
    }

    /**
     * 发送请求
     *
     * 参数 `$config` :
     *    ['cookie_dir' => *, 'time_out' => *, 'retries' => *]
     * @param string                                $method  请求方式
     * @param string|UriInterface                   $uri     请求URI
     * @param string|resource|StreamInterface|array $body    请求体
     * @param array                                 $headers 报头信息
     * @param array                                 $opts    CURL选项
     * @param array                                 $config  客户端配置
     * @return string 返回响应主体内容
     */
    protected static function send($method, $uri, $body = null, array $headers = [], array $opts = [], array $config = [])
    {
        $response = parent::send($method, $uri, $body, $headers, $opts, $config);
        return (string)$response->getBody();
    }
}
