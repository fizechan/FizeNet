<?php /** @noinspection PhpComposerExtensionStubsInspection */

namespace fize\net;

/**
 * FTP管理类
 */
class Ftp
{

    /**
     * FTP 连接标示符
     * @var resource
     */
    private $_stream;

    /**
     * 构造函数
     * @param string $host 要连接的服务器
     * @param string $username 登录用户名
     * @param string $password 登录密码
     * @param int $port 端口号，默认21
     * @param int $timeout 超时时间，默认90(秒)
     * @param bool $ssl 是否为SSL-FTP连接
     */
    public function __construct($host, $username = null, $password = null, $port = 21, $timeout = 90, $ssl = false)
    {
        if ($ssl) {
            $this->sslConnect($host, $port, $timeout);
        } else {
            $this->connect($host, $port, $timeout);
        }
        if (!is_null($username)) {
            $this->login($username, $password);
        }
    }

    /**
     * 析构函数
     */
    public function __destruct()
    {
        if ($this->_stream && get_resource_type($this->_stream) == "FTP Buffer") {
            $this->close();
        }
    }

    /**
     * 格式化路径，解决中文乱码及中文路径无效问题
     * @todo 待测试修改
     * @param string $path 要格式化的路径
     * @return string
     */
    private function _pathFormat($path)
    {
        return iconv('UTF-8', 'GBK', $path);
    }

    /**
     * 为要上传的文件预分配空间
     * @param int $filesize 要分配的空间，以字节为单位。
     * @param string $result 如果提供此参数，那么服务器的响应 会以文本方式设置到 result 中。
     * @return bool
     */
    public function alloc($filesize, &$result = null)
    {
        return ftp_alloc($this->_stream, $filesize, $result);
    }

    /**
     * 切换到当前目录的父目录,即切换到上级目录
     * 经测试，windows环境下有出现返回false并出现警告，但实际切换成功的情况
     * @return bool
     */
    public function cdup()
    {
        return ftp_cdup($this->_stream);
    }

    /**
     * 改变当前目录
     * @param string $directory 目标目录。
     * @return bool
     */
    public function chdir($directory)
    {
        return ftp_chdir($this->_stream, $directory);
    }

    /**
     * 设置 FTP 服务器上的文件权限
     * 经测试，在windows环境下该方法无效，windows并没有针对FTP的权限之说
     * @param int $mode 要设置的权限值，八进制值。
     * @param string $filename 远程文件名称。
     * @return int 操作成功返回文件新的权限，操作失败返回 FALSE。
     */
    public function chmod($mode, $filename)
    {
        return ftp_chmod($this->_stream, $mode, $filename);
    }

    /**
     * 关闭当前FTP连接
     * @return bool
     */
    public function close()
    {
        return ftp_close($this->_stream);
    }

    /**
     * 建立一个新的FTP连接
     * @param string $host 要连接的服务器
     * @param int $port 端口号，默认21
     * @param int $timeout 超时时间，默认90(秒)
     */
    public function connect($host, $port = 21, $timeout = 90)
    {
        $this->_stream = ftp_connect($host, $port, $timeout);
    }

    /**
     * 删除 FTP 服务器上的一个文件
     * @param string $path 要删除的文件路径，可以是相对路径，也可以是绝对路径。
     * @return bool
     */
    public function delete($path)
    {
        return ftp_delete($this->_stream, $path);
    }

    /**
     * 请求运行一条 FTP 命令
     * @param string $command FTP 命令
     * @return bool
     */
    public function exec($command)
    {
        return ftp_exec($this->_stream, $command);
    }

    /**
     * 从 FTP 服务器上下载一个文件并保存到本地一个已经打开的文件中
     * @param resource $handle 本地已经打开的文件的句柄。
     * @param string $remote_file 远程文件的路径。
     * @param int $mode 传送模式参数， 必须是 (文本模式) FTP_ASCII  或 (二进制模式) FTP_BINARY  中的一个。
     * @param int $resumepos 远程文件开始下载的位置。
     * @param bool $nb 是否以非阻塞方式
     * @return mixed 如果非阻塞则返回FTP_FAILED  或 FTP_FINISHED  或 FTP_MOREDATA，否则返回下载结果
     */
    public function fget($handle, $remote_file, $mode, $resumepos = 0, $nb = false)
    {
        if ($nb) {
            return ftp_nb_fget($this->_stream, $handle, $remote_file, $mode, $resumepos);
        } else {
            return ftp_fget($this->_stream, $handle, $remote_file, $mode, $resumepos);
        }
    }

    /**
     * 上传一个已经打开的文件到 FTP 服务器
     * @param string $remote_file 远程文件路径。
     * @param resource $handle 打开的本地文件句柄，读取到文件末尾。
     * @param int $mode 传输模式只能为 (文本模式) FTP_ASCII  或 (二进制模式) FTP_BINARY  其中的一个。
     * @param int $startpos 远程文件上传的开始位置。
     * @param bool $nb 是否以非阻塞方式
     * @return mixed 如果非阻塞则返回FTP_FAILED  或 FTP_FINISHED  或 FTP_MOREDATA，否则返回上传结果
     */
    public function fput($remote_file, $handle, $mode, $startpos = 0, $nb = false)
    {
        if ($nb) {
            return ftp_nb_fput($this->_stream, $remote_file, $handle, $mode, $startpos);
        } else {
            return ftp_fput($this->_stream, $remote_file, $handle, $mode, $startpos);
        }
    }

    /**
     * 返回当前 FTP 连接的各种不同的选项设置
     * @param int $option 参数 option 选项
     * @return mixed
     */
    public function getOption($option)
    {
        return ftp_get_option($this->_stream, $option);
    }

    /**
     * 从 FTP 服务器上下载一个文件
     * @param string $local_file 文件本地的路径（如果文件已经存在，则会被覆盖）。
     * @param string $remote_file 文件的远程路径。
     * @param int $mode 传送模式。只能为 (文本模式) FTP_ASCII  或 (二进制模式) FTP_BINARY  中的其中一个。
     * @param int $resumepos 从远程文件的这个位置继续下载。
     * @param bool $nb 是否以非阻塞方式
     * @return mixed 如果非阻塞则返回FTP_FAILED  或 FTP_FINISHED  或 FTP_MOREDATA，否则返回下载结果
     */
    public function get($local_file, $remote_file, $mode, $resumepos = 0, $nb = false)
    {
        if ($nb) {
            return ftp_nb_get($this->_stream, $local_file, $remote_file, $mode, $resumepos);
        } else {
            return ftp_get($this->_stream, $local_file, $remote_file, $mode, $resumepos);
        }
    }

    /**
     * 登录 FTP 服务器
     * @param string $username 用户名
     * @param string $password 密码
     * @return bool
     */
    public function login($username, $password)
    {
        return ftp_login($this->_stream, $username, $password);
    }

    /**
     * 返回指定文件的最后修改时间戳
     * @param string $remote_file 文件路径
     * @return int
     */
    public function mdtm($remote_file)
    {
        return ftp_mdtm($this->_stream, $remote_file);
    }

    /**
     * 建立新目录
     * @param string $directory 新目录
     * @return mixed 如果成功返回新建的目录名，否则返回 FALSE。
     */
    public function mkdir($directory)
    {
        return ftp_mkdir($this->_stream, $directory);
    }

    /**
     * 返回当前非阻塞的传输状态
     * @return int 返回常量 FTP_FAILED  或 FTP_FINISHED  或 FTP_MOREDATA。
     */
    public function nbContinue()
    {
        return ftp_nb_continue($this->_stream);
    }

    /**
     * 返回给定目录的文件及文件夹名称列表
     * @param string $directory 指定要列表的目录，如果不指定则默认为当前目录
     * @return array
     */
    public function nlist($directory = null)
    {
        if (is_null($directory)) {
            $directory = $this->pwd();
        }
        return ftp_nlist($this->_stream, $directory);
    }

    /**
     * 是否打开被动模式
     * @param bool $pasv 是否打开被动模式
     * @return bool 成功时返回 TRUE ， 或者在失败时返回 FALSE。
     */
    public function pasv($pasv)
    {
        return ftp_pasv($this->_stream, $pasv);
    }

    /**
     * 上传文件到 FTP 服务器
     * @param string $remote_file 远程文件路径。
     * @param string $local_file 本地文件路径。
     * @param int $mode 传送模式，只能为 FTP_ASCII （文本模式）或 FTP_BINARY （二进制模式）,默认为二进制。
     * @param int $startpos 远程文件上传的开始位置。
     * @param bool $nb 是否以非阻塞方式
     * @return mixed 如果非阻塞则返回FTP_FAILED  或 FTP_FINISHED  或 FTP_MOREDATA，否则返回上传结果
     */
    public function put($remote_file, $local_file, $mode = FTP_BINARY, $startpos = 0, $nb = false)
    {
        $remote_file = $this->_pathFormat($remote_file);
        $local_file = $this->_pathFormat($local_file);
        if ($nb) {
            return ftp_nb_put($this->_stream, $remote_file, $local_file, $mode, $startpos);
        } else {
            return ftp_put($this->_stream, $remote_file, $local_file, $mode, $startpos);
        }
    }

    /**
     * 返回当前目录名
     * @return string
     */
    public function pwd()
    {
        return ftp_pwd($this->_stream);
    }

    /**
     * close的别名
     * @return bool
     */
    public function quit()
    {
        return ftp_quit($this->_stream);
    }

    /**
     *  向 FTP 服务器发送命令
     * 将服务器的响应以字符串数组的形式返回。 对于响应内容既不做解析处理， 也不检测命令是否执行成功。
     * @param string $command 要执行的命令。
     * @return array
     */
    public function raw($command)
    {
        return ftp_raw($this->_stream, $command);
    }

    /**
     * 返回指定目录下文件的详细列表
     * @param string $directory 目录路径。
     * @param bool $recursive 如果此参数为 TRUE ，实际执行的命令将会为 LIST -R。
     * @return array
     */
    public function rawlist($directory, $recursive = false)
    {
        return ftp_rawlist($this->_stream, $directory, $recursive);
    }

    /**
     * 更改 FTP 服务器上的文件或目录名
     * 使用此方法可以移动文件或者文件夹
     * @param string $oldname 原来的文件／目录名。
     * @param string $newname 新名字。
     * @return bool
     */
    public function rename($oldname, $newname)
    {
        return ftp_rename($this->_stream, $oldname, $newname);
    }

    /**
     * 删除 FTP 服务器上的一个目录
     * @param string $directory 要删除的目录
     * @param bool $force 如果该目录不为空，是否强制删除
     * @return bool
     */
    public function rmdir($directory, $force = false)
    {
        if ($force) {
            $list = $this->nlist($directory);
            if (is_array($list)) {
                foreach ($list as $path) {
                    if ($this->chdir($path)) {
                        $this->rmdir($path, true);
                    } else {
                        $this->delete($path);
                    }
                }
            }
        }
        return ftp_rmdir($this->_stream, $directory);
    }

    /**
     * 设置各种 FTP 运行时选项
     * @param int $option 选项标识
     * @param mixed $value 本参数取决于要修改哪个 option。
     * @return bool
     */
    public function setOption($option, $value)
    {
        return ftp_set_option($this->_stream, $option, $value);
    }

    /**
     * 向服务器发送 SITE 命令
     * @param string $command SITE 命令
     * @return bool
     */
    public function site($command)
    {
        return ftp_site($this->_stream, $command);
    }

    /**
     * 返回指定文件的大小
     * @param string $remote_file 远程文件路径。
     * @return int
     */
    public function size($remote_file)
    {
        return ftp_size($this->_stream, $remote_file);
    }

    /**
     * 打开一个到 host 的安全 FTP 连接（SSL-FTP）。
     * 注意：本函数有可能不存在，只有 PHP 构建时同时包含了 ftp 模块 和 OpenSSL 模块时， ftp_ssl_connect()  函数才可用。
     * @param string $host 要连接的服务器
     * @param int $port 端口号，默认21
     * @param int $timeout 超时时间，默认90(秒)
     */
    public function sslConnect($host, $port = 21, $timeout = 90)
    {
        $this->_stream = ftp_ssl_connect($host, $port, $timeout);
    }

    /**
     * 返回远程 FTP 服务器的操作系统类型
     * 非可靠判断，windows有可能返回UNIX
     * @return string
     */
    public function systype()
    {
        return ftp_systype($this->_stream);
    }
}