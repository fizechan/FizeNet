<?php

namespace Fize\Net;

use Exception;

/**
 * Socket 套接字
 */
class Socket
{

    /**
     * @var resource 当前套接字
     */
    private $socket;

    /**
     * 构造函数
     * @param resource $socket 指定当前socket
     */
    public function __construct($socket = null)
    {
        $this->socket = $socket;
    }

    /**
     * 析构函数
     */
    public function __destruct()
    {
    }

    /**
     * 返回当前套接字上的当前连接Socket对象
     *
     * 即为每个连接分发一个独立Socket对象
     * @return Socket
     * @throws Exception
     */
    public function accept(): Socket
    {
        $socket = socket_accept($this->socket);
        if ($socket === false) {
            throw new Exception(self::strerror($this->lastError()), $this->lastError());
        }
        return new Socket($socket);
    }

    /**
     * 给套接字绑定名字
     *
     * 如果套接字是 AF_INET族，那么 address 必须是一个四点分法的 IP 地址（例如 127.0.0.1 ）,该类型较为常用
     * 如果套接字是 AF_UNIX族，那么 address 是 Unix 套接字一部分（例如 /tmp/my.sock ）。
     * @param string $address 服务端地址
     * @param int    $port    指定端口
     * @return bool
     */
    public function bind(string $address, int $port = 0): bool
    {
        return socket_bind($this->socket, $address, $port);
    }

    /**
     * 清除套接字或者最后的错误代码上的错误
     */
    public function clearError()
    {
        socket_clear_error($this->socket);
    }

    /**
     * 关闭当前套接字资源
     */
    public function close()
    {
        socket_close($this->socket);
    }

    /**
     * 计算应分配用于接收辅助数据的缓冲区的大小。
     *
     * @notice 该方法由于官方文档不齐全，暂不建议使用
     * @param int $level
     * @param int $type
     * @return int
     */
    public static function cmsgSpace(int $level, int $type): int
    {
        return socket_cmsg_space($level, $type);
    }

    /**
     * 客户端开启一个套接字连接
     * @param string $address IPV4\IPV6\Unix套接字
     * @param int    $port    端口号
     * @return bool 成功时返回 TRUE ， 或者在失败时返回 FALSE
     */
    public function connect(string $address, int $port = 0): bool
    {
        return socket_connect($this->socket, $address, $port);
    }

    /**
     * 创建一个新的监听全部接口的套接字
     * @param int $port    监听端口
     * @param int $backlog 连接队列的最大长度
     * @return Socket
     * @throws Exception
     */
    public function createListen(int $port, int $backlog = 128): Socket
    {
        $socket = socket_create_listen($port, $backlog);
        if ($socket === false) {
            throw new Exception(self::strerror($this->lastError()), $this->lastError());
        }
        $this->socket = $socket;
        return $this;
    }

    /**
     * 创建一对无区别的套接字Socket对象，并将它们存储在数组$obj中
     *
     * 参数 `$type` :
     *   SOCK_STREAM/SOCK_DGRAM/SOCK_SEQPACKET/SOCK_RAW/SOCK_RDM
     * @param int   $domain   指定协议 AF_INET/AF_INET6/AF_UNIX
     * @param int   $type     套接字使用的类型
     * @param int   $protocol 定 domain 套接字下的具体协议
     * @param array $obj      注意该数组保存的是Socket对象而非socket资源
     * @return bool 成功时返回 TRUE ， 或者在失败时返回 FALSE 。
     */
    public static function createPair(int $domain, int $type, int $protocol, array &$obj): bool
    {
        $fd = [];
        $rst = socket_create_pair($domain, $type, $protocol, $fd);
        if ($rst) {
            foreach ($fd as $socket) {
                $obj[] = new Socket($socket);
            }
        }
        return $rst;
    }

    /**
     * 创建一个套接字
     *
     * 参数 `$type` :
     *   SOCK_STREAM/SOCK_DGRAM/SOCK_SEQPACKET/SOCK_RAW/SOCK_RDM
     * @param int $domain   指定协议 AF_INET/AF_INET6/AF_UNIX
     * @param int $type     套接字使用的类型
     * @param int $protocol 定 domain 套接字下的具体协议
     * @return Socket
     * @throws Exception
     */
    public function create(int $domain, int $type, int $protocol): Socket
    {
        $socket = socket_create($domain, $type, $protocol);
        if ($socket === false) {
            throw new Exception(self::strerror($this->lastError()), $this->lastError());
        }
        $this->socket = $socket;
        return $this;
    }

    /**
     * 获取当前套接字的套接字选项
     * @param int $level   指定协议级别
     * @param int $optname 选项名
     * @return array|int|false
     */
    public function getOption(int $level, int $optname)
    {
        return socket_get_option($this->socket, $level, $optname);
    }

    /**
     * 获取当前套接字的套接字选项
     *
     * 使用socket_get_option()的别名socket_getopt()
     * @param int $level   指定协议级别
     * @param int $optname 选项名
     * @return array|int|false
     */
    public function getOpt(int $level, int $optname)
    {
        return socket_getopt($this->socket, $level, $optname);
    }

    /**
     * 获取远端主机/端口或UNIX文件系统路径
     * @param string   $address 获取到的主机
     * @param int|null $port    获取到的端口
     * @return bool 成功时返回 TRUE ， 或者在失败时返回 FALSE
     */
    public function getPeerName(string &$address, int &$port = null): bool
    {
        return socket_getpeername($this->socket, $address, $port);
    }

    /**
     * 获取本地具体连接的主机/端口或UNIX文件系统路径
     * @param string   $addr 获取到的主机
     * @param int|null $port 获取到的端口
     * @return bool 成功时返回 TRUE ， 或者在失败时返回 FALSE
     */
    public function getSockName(string &$addr, int &$port = null): bool
    {
        return socket_getsockname($this->socket, $addr, $port);
    }

    /**
     * 将流封装成套接字
     * @param resource $stream 要封装的流
     * @return Socket
     * @throws Exception
     */
    public function importStream($stream): Socket
    {
        $socket = socket_import_stream($stream);
        if ($socket == false) {
            throw new Exception(self::strerror($this->lastError()), $this->lastError());
        }
        $this->socket = $socket;
        return $this;
    }

    /**
     * 获取最后的错误代码
     * @return int
     */
    public function lastError(): int
    {
        return socket_last_error($this->socket);
    }

    /**
     * 开始监听
     * @param int $backlog 连接队列的最大长度
     * @return bool 成功时返回 TRUE ， 或者在失败时返回 FALSE
     */
    public function listen(int $backlog = 0): bool
    {
        return socket_listen($this->socket, $backlog);
    }

    /**
     * 从套接字读取最大长度字节数
     * @param int $length 指定最大字节长度
     * @param int $type   读取、断行方式
     * @return string
     */
    public function read(int $length, int $type = PHP_BINARY_READ): string
    {
        return socket_read($this->socket, $length, $type);
    }

    /**
     * 从已连接的socket接收数据
     *
     * 参数 `$buf` :
     *   如果有错误发生，如链接被重置，数据不可用等等， buf 将被设为 NULL 。
     * 参数 `$flags` :
     *   flags的值可以为下列任意flag的组合。
     *   使用按位或运算符(|)来 组合不同的flag。
     *   MSG_OOB/MSG_PEEK/MSG_WAITALL/MSG_DONTWAIT
     * @param string $buf   从socket中获取的数据将被保存在由 buf 制定的变量中。
     * @param int    $len   长度最多为 len 字节的数据将被接收。
     * @param int    $flags 标识
     * @return int 返回一个数字，表示接收到的字节数。如果发生了错误，则返回 FALSE
     */
    public function recv(string &$buf, int $len, int $flags): int
    {
        return socket_recv($this->socket, $buf, $len, $flags);
    }

    /**
     * 从socket(导向连接也可以)接收数据
     *
     * 参数 `$buf` :
     *    如果有错误发生，如链接被重置，数据不可用等等， buf 将被设为 NULL 。
     * 参数 `$flags` :
     *   flags的值可以为下列任意flag的组合。
     *   使用按位或运算符(|)来 组合不同的flag。
     *   MSG_OOB/MSG_PEEK/MSG_WAITALL/MSG_DONTWAIT
     * @param string   $buf   从socket中获取的数据将被保存在由 buf 制定的变量中。
     * @param int      $len   长度最多为 len 字节的数据将被接收。
     * @param int      $flags 标识
     * @param string   $name  获取到的主机
     * @param int|null $port  获取到的端口
     * @return int 返回一个数字，表示接收到的字节数。如果发生了错误，则返回 FALSE
     */
    public function recvFrom(string &$buf, int $len, int $flags, string &$name, int &$port = null): int
    {
        return socket_recvfrom($this->socket, $buf, $len, $flags, $name, $port);
    }

    /**
     * 读取消息
     *
     * 该方法由于官方文档未编写，不建议使用
     * 参数 `$flags` :
     *   flags的值可以为下列任意flag的组合。
     *   使用按位或运算符(|)来 组合不同的flag。
     *   MSG_OOB/MSG_PEEK/MSG_WAITALL/MSG_DONTWAIT
     * @param array    $message 读取到的信息
     * @param int|null $flags   标识
     * @return int 返回一个数字，表示接收到的字节数。如果发生了错误，则返回 FALSE
     */
    public function recvMsg(array &$message, int $flags = null): int
    {
        return socket_recvmsg($this->socket, $message, $flags);
    }

    /**
     * socket多路选择
     *
     * 注意返回的是socket资源而非Socket对象
     * @param array|null $read    监听到的发生读取的socket资源
     * @param array|null $write   监听到的发生写入的socket资源
     * @param array|null $except  监听到的发生异常的socket资源
     * @param int        $tv_sec  服务端超时时间
     * @param int        $tv_usec 客服端超时时间
     * @return int 返回获取到的socket个数
     */
    public static function select(?array &$read, ?array &$write, ?array &$except, int $tv_sec, int $tv_usec = 0): int
    {
        return socket_select($read, $write, $except, $tv_sec, $tv_usec);
    }

    /**
     * 发送数据
     * @param string $buf   包含将要发送到远程主机的数据的缓冲区。
     * @param int    $len   将从缓冲区发送到远程主机的字节数量。
     * @param int    $flags MSG_OOB/MSG_EOR/MSG_EOF/MSG_DONTROUTE
     * @return int 成功时返回发送的字节数量，失败时返回false
     */
    public function send(string $buf, int $len, int $flags): int
    {
        return socket_send($this->socket, $buf, $len, $flags);
    }

    /**
     * 发送消息
     *
     * 该方法由于官方文档未编写，不建议使用
     * @param array $message
     * @param int   $flags
     * @return int 成功时返回发送的字节数量，失败时返回false
     */
    public function sendmsg(array $message, int $flags): int
    {
        return socket_sendmsg($this->socket, $message, $flags);
    }

    /**
     * 对socket(导向连接也可以)发送数据
     * @param string $buf   包含将要发送到远程主机的数据的缓冲区。
     * @param int    $len   将从缓冲区发送到远程主机的字节数量。
     * @param int    $flags MSG_OOB/MSG_EOR/MSG_EOF/MSG_DONTROUTE
     * @param string $addr  获取到的主机
     * @param int    $port  获取到的端口
     * @return int 成功时返回发送的字节数量，失败时返回false
     */
    public function sendto(string $buf, int $len, int $flags, string $addr, int $port = 0): int
    {
        return socket_sendto($this->socket, $buf, $len, $flags, $addr, $port);
    }

    /**
     * 设置为块模式
     * @return bool
     */
    public function setBlock(): bool
    {
        return socket_set_block($this->socket);
    }

    /**
     * 设置为非块模式
     * @return bool
     */
    public function setNonblock(): bool
    {
        return socket_set_nonblock($this->socket);
    }

    /**
     * 设置当前socket
     * @param int   $level   指定协议级别
     * @param int   $optname 选项名
     * @param mixed $optval  选项值
     * @return bool 成功时返回 TRUE ， 或者在失败时返回 FALSE
     */
    public function setOption(int $level, int $optname, $optval): bool
    {
        return socket_set_option($this->socket, $level, $optname, $optval);
    }

    /**
     * 设置当前socket
     *
     * 实际是socket_set_option的别名socket_setopt
     * @param int   $level   指定协议级别
     * @param int   $optname 选项名
     * @param mixed $optval  选项值
     * @return bool 成功时返回 TRUE ， 或者在失败时返回 FALSE
     */
    public function setOpt(int $level, int $optname, $optval): bool
    {
        return socket_setopt($this->socket, $level, $optname, $optval);
    }

    /**
     * 关闭socket的读取、写入等
     * @param int $how 0关闭读取、1关闭写入、2关闭全部
     * @return bool
     */
    public function shutdown(int $how = 2): bool
    {
        return socket_shutdown($this->socket, $how);
    }

    /**
     * 根据错误代码返回错误描述
     * @param int $errno socket错误代码
     * @return string
     */
    public static function strerror(int $errno): string
    {
        return socket_strerror($errno);
    }

    /**
     * 写入到socket
     * @param string $buffer 包含将要写入到socket的数据的缓冲区。
     * @param int    $length 指定要写入的最大字节长度
     * @return int 返回写入的字节长度
     */
    public function write(string $buffer, int $length = 0): int
    {
        return socket_write($this->socket, $buffer, $length);
    }
}
