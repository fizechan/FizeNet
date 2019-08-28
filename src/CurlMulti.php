<?php

namespace fize\net;

/**
 * 多进程CURL操作类
 * @package fize\net
 */
class CurlMulti
{
    /**
     * 当前的cURL批处理句柄
     * @var resource
     */
    private $mh;

    /**
     * 已添加的单独CURL对象句柄
     * @var array
     */
    private $handles = [];

    /**
     * 析构函数
     */
    public function __construct()
    {
        $this->mh = $this->init();
    }

    /**
     * 析构，关闭连接
     */
    public function __destruct()
    {
        if ($this->mh && get_resource_type($this->mh) == "curl_multi") {
            $this->close();
        }
    }

    /**
     * 向当前批处理会话中添加一个CURL对象作为句柄
     * @param Curl $ch 要添加进去的CURL对象
     * @return int
     */
    public function addHandle(Curl $ch)
    {
        $this->handles[] = $ch;
        return curl_multi_add_handle($this->mh, $ch->getHandle());
    }

    /**
     * 以数组形式向当前批处理会话中添加多个CURL对象作为句柄
     * @param array $chs 元素为CURL对象的数组
     * @return bool
     */
    public function addHandles(array $chs)
    {
        $no_err = true;
        foreach ($chs as $ch) {
            $errcode = $this->addHandle($ch);
            if ($errcode != 0) {
                $no_err = false;
                break;
            }
        }
        return $no_err;
    }

    /**
     * 获取当前已添加的单独CURL对象句柄
     * @return array
     */
    public function getHandles()
    {
        return $this->handles;
    }

    /**
     * 关闭当前批处理会话
     */
    public function close()
    {
        curl_multi_close($this->mh);
    }

    /**
     * 处理在栈中的每一个句柄
     * @param int $still_running 一个用来判断操作是否仍在执行的标识的引用。
     * @return int
     */
    public function exec(&$still_running)
    {
        return curl_multi_exec($this->mh, $still_running);
    }

    /**
     * 如果设置了 CURLOPT_RETURNTRANSFER ，则返回获取的输出的文本流
     * @param Curl $ch Curl对象，该对象必须执行后才有输出
     * @return string
     */
    public static function getcontent(Curl $ch)
    {
        return curl_multi_getcontent($ch->getHandle());
    }

    /**
     * 获取当前解析的cURL的相关传输信息
     * @param int $msgs_in_queue 仍在队列中的消息数量。
     * @return array
     */
    public function infoRead(&$msgs_in_queue = null)
    {
        return curl_multi_info_read($this->mh, $msgs_in_queue);
    }

    /**
     * 返回一个新cURL批处理句柄
     * @return resource
     */
    private function init()
    {
        return curl_multi_init();
    }

    /**
     * 移除curl批处理句柄资源中的某个句柄资源,成功时返回一个cURL句柄，失败时返回 FALSE 。
     * @param Curl $ch 要移除的Curl对象
     * @return int
     */
    public function removeHandle(Curl $ch)
    {
        return curl_multi_remove_handle($this->mh, $ch->getHandle());
    }

    /**
     * 以数组形式对当前批处理会话中移除多个CURL对象句柄，成功时返回true，失败返回false
     * @param array $chs 要移除的Curl对象组成的数组
     * @return bool
     */
    public function removeHandles(array $chs)
    {
        $no_err = true;
        foreach ($chs as $ch) {
            $handle = $this->removeHandle($ch);
            if ($handle === false) {
                $no_err = false;
                break;
            }
        }
        return $no_err;
    }

    /**
     * 等待所有cURL批处理中的活动连接,如果失败返回-1
     * @param float $timeout 设定超时秒数
     * @return int
     */
    public function select($timeout = 1.0)
    {
        return curl_multi_select($this->mh, $timeout);
    }

    /**
     * 为当前并行处理设置一个选项
     * @param int $option 常量 CURLMOPT_* 之一。
     * @param mixed $value 将要设置给 option 的值。
     * @return bool
     */
    public function setopt($option, $value)
    {
        return curl_multi_setopt($this->mh, $option, $value);
    }

    /**
     * 根据错误码返回错误描述
     * @param int $errornum 返回的错误码
     * @return string
     */
    public static function strerror($errornum)
    {
        return curl_multi_strerror($errornum);
    }
}