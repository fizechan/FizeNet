<?php

namespace fize\net;

/**
 * FTP管理
 */
class Ftp
{

    /**
     * @var resource FTP 连接标示符
     */
    private $ftp;

    /**
     * 构造函数
     * @param string $host     要连接的服务器
     * @param string $username 登录用户名
     * @param string $password 登录密码
     * @param int    $port     端口号，默认21
     * @param int    $timeout  超时时间，默认90(秒)
     * @param bool   $ssl      是否为SSL-FTP连接
     */
    public function __construct(string $host, string $username = null, string $password = null, int $port = 21, int $timeout = 90, bool $ssl = false)
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
        if ($this->ftp && get_resource_type($this->ftp) == "FTP Buffer") {
            $this->close();
        }
    }

    /**
     * 为要上传的文件预分配空间
     * @param int         $filesize 要分配的空间，以字节为单位。
     * @param string|null $result   如果提供此参数，那么服务器的响应 会以文本方式设置到 result 中。
     * @return bool
     */
    public function alloc(int $filesize, string &$result = null): bool
    {
        return ftp_alloc($this->ftp, $filesize, $result);
    }

    /**
     * 切换到当前目录的父目录,即切换到上级目录
     *
     * 经测试，windows环境下有出现返回false并出现警告，但实际切换成功的情况
     * @return bool
     */
    public function cdup()
    {
        return ftp_cdup($this->ftp);
    }

    /**
     * 改变当前目录
     * @param string $directory 目标目录。
     * @return bool
     */
    public function chdir(string $directory): bool
    {
        return ftp_chdir($this->ftp, $directory);
    }

    /**
     * 设置 FTP 服务器上的文件权限
     *
     * 经测试，在windows环境下该方法无效，windows并没有针对FTP的权限之说
     * @param int    $mode     要设置的权限值，八进制值。
     * @param string $filename 远程文件名称。
     * @return int|false 操作成功返回文件新的权限，操作失败返回 FALSE。
     */
    public function chmod(int $mode, string $filename): int
    {
        return ftp_chmod($this->ftp, $mode, $filename);
    }

    /**
     * 关闭当前FTP连接
     * @return bool
     */
    private function close(): bool
    {
        return ftp_close($this->ftp);
    }

    /**
     * 建立一个新的FTP连接
     * @param string $host    要连接的服务器
     * @param int    $port    端口号，默认21
     * @param int    $timeout 超时时间，默认90(秒)
     */
    private function connect(string $host, int $port = 21, int $timeout = 90)
    {
        $this->ftp = ftp_connect($host, $port, $timeout);
    }

    /**
     * 删除 FTP 服务器上的一个文件
     * @param string $filename 要删除的文件。
     * @return bool
     */
    public function delete(string $filename): bool
    {
        return ftp_delete($this->ftp, $filename);
    }

    /**
     * 请求运行一条 FTP 命令
     *
     * @notice 并没有得到广泛的支持，请谨慎使用该方法。
     * @param string $command FTP 命令
     * @return bool
     */
    public function exec(string $command): bool
    {
        return ftp_exec($this->ftp, $command);
    }

    /**
     * 从 FTP 服务器上下载一个文件并保存到本地一个已经打开的文件中
     * @param resource $handle      本地已经打开的文件的句柄。
     * @param string   $remote_file 远程文件的路径。
     * @param int      $mode        传送模式参数， 必须是 (文本模式) FTP_ASCII  或 (二进制模式) FTP_BINARY  中的一个。
     * @param int      $resumepos   远程文件开始下载的位置。
     * @return bool 返回下载结果
     */
    public function fget($handle, string $remote_file, int $mode = FTP_BINARY, int $resumepos = 0): bool
    {
        return ftp_fget($this->ftp, $handle, $remote_file, $mode, $resumepos);
    }

    /**
     * 上传一个已经打开的文件到 FTP 服务器
     * @param string   $remote_file 远程文件路径。
     * @param resource $handle      打开的本地文件句柄，读取到文件末尾。
     * @param int      $mode        传输模式只能为 (文本模式) FTP_ASCII  或 (二进制模式) FTP_BINARY  其中的一个。
     * @param int      $startpos    远程文件上传的开始位置。
     * @return bool 返回上传结果
     */
    public function fput(string $remote_file, $handle, int $mode = FTP_BINARY, int $startpos = 0): bool
    {
        return ftp_fput($this->ftp, $remote_file, $handle, $mode, $startpos);
    }

    /**
     * 返回当前 FTP 连接的各种不同的选项设置
     * @param int $option 参数 option 选项
     * @return bool|int
     */
    public function getOption(int $option)
    {
        return ftp_get_option($this->ftp, $option);
    }

    /**
     * 从 FTP 服务器上下载一个文件
     * @param string $local_file  文件本地的路径（如果文件已经存在，则会被覆盖）。
     * @param string $remote_file 文件的远程路径。
     * @param int    $mode        传送模式。只能为 (文本模式) FTP_ASCII  或 (二进制模式) FTP_BINARY  中的其中一个。
     * @param int    $resumepos   从远程文件的这个位置继续下载。
     * @return bool 返回下载结果
     */
    public function get(string $local_file, string $remote_file, int $mode = FTP_BINARY, int $resumepos = 0): bool
    {
        return ftp_get($this->ftp, $local_file, $remote_file, $mode, $resumepos);
    }

    /**
     * 登录 FTP 服务器
     * @param string $username 用户名
     * @param string $password 密码
     * @return bool
     */
    private function login(string $username, string $password)
    {
        return ftp_login($this->ftp, $username, $password);
    }

    /**
     * 返回指定文件的最后修改时间戳
     * @param string $remote_file 文件路径
     * @return int 失败时返回-1
     */
    public function mdtm(string $remote_file): int
    {
        return ftp_mdtm($this->ftp, $remote_file);
    }

    /**
     * 建立新目录
     * @param string $directory 新目录
     * @return string|false 如果成功返回新建的目录名，否则返回 FALSE。
     */
    public function mkdir(string $directory)
    {
        return ftp_mkdir($this->ftp, $directory);
    }

    /**
     * 连续获取／发送文件
     * @return int 返回常量 FTP_FAILED  或 FTP_FINISHED  或 FTP_MOREDATA。
     */
    public function nbContinue(): int
    {
        return ftp_nb_continue($this->ftp);
    }

    /**
     * 以非阻塞方式下载一个文件并保存到本地一个已经打开的文件中
     * @param resource $handle      本地已经打开的文件的句柄。
     * @param string   $remote_file 远程文件的路径。
     * @param int      $mode        传送模式参数， 必须是 (文本模式) FTP_ASCII  或 (二进制模式) FTP_BINARY  中的一个。
     * @param int      $resumepos   远程文件开始下载的位置。
     * @return int 返回FTP_FAILED  或 FTP_FINISHED  或 FTP_MOREDATA
     */
    public function nbFget($handle, string $remote_file, int $mode = FTP_BINARY, int $resumepos = 0)
    {
        return ftp_nb_fget($this->ftp, $handle, $remote_file, $mode, $resumepos);
    }

    /**
     * 以非阻塞方式上传一个已经打开的文件到 FTP 服务器
     * @param string   $remote_file 远程文件路径。
     * @param resource $handle      打开的本地文件句柄，读取到文件末尾。
     * @param int      $mode        传输模式只能为 (文本模式) FTP_ASCII  或 (二进制模式) FTP_BINARY  其中的一个。
     * @param int      $startpos    远程文件上传的开始位置。
     * @return int 返回FTP_FAILED  或 FTP_FINISHED  或 FTP_MOREDATA
     */
    public function nbFput(string $remote_file, $handle, int $mode = FTP_BINARY, int $startpos = 0)
    {
        return ftp_nb_fput($this->ftp, $remote_file, $handle, $mode, $startpos);
    }

    /**
     * 以非阻塞方式从 FTP 服务器上下载一个文件
     * @param string $local_file  文件本地的路径（如果文件已经存在，则会被覆盖）。
     * @param string $remote_file 文件的远程路径。
     * @param int    $mode        传送模式。只能为 (文本模式) FTP_ASCII  或 (二进制模式) FTP_BINARY  中的其中一个。
     * @param int    $resumepos   从远程文件的这个位置继续下载。
     * @return int 返回FTP_FAILED  或 FTP_FINISHED  或 FTP_MOREDATA
     */
    public function nbGet(string $local_file, string $remote_file, int $mode = FTP_BINARY, int $resumepos = 0)
    {
        return ftp_nb_get($this->ftp, $local_file, $remote_file, $mode, $resumepos);
    }

    /**
     * 以非阻塞方式上传文件到 FTP 服务器
     * @param string $remote_file 远程文件路径。
     * @param string $local_file  本地文件路径。
     * @param int    $mode        传送模式，只能为 FTP_ASCII （文本模式）或 FTP_BINARY （二进制模式）,默认为二进制。
     * @param int    $startpos    远程文件上传的开始位置。
     * @return int 如果非阻塞则返回FTP_FAILED  或 FTP_FINISHED  或 FTP_MOREDATA
     */
    public function nbPut(string $remote_file, string $local_file, int $mode = FTP_BINARY, int $startpos = 0)
    {
        return ftp_nb_put($this->ftp, $remote_file, $local_file, $mode, $startpos);
    }

    /**
     * 返回给定目录的文件及文件夹名称列表
     * @param string|null $directory 指定要列表的目录，如果不指定则默认为当前目录
     * @return array 键值为FTP绝对路径
     */
    public function nlist(string $directory = null)
    {
        if (is_null($directory)) {
            $directory = $this->pwd();
        }
        return ftp_nlist($this->ftp, $directory);
    }

    /**
     * 是否打开被动模式
     * @param bool $pasv 是否打开被动模式
     * @return bool 成功时返回 TRUE ， 或者在失败时返回 FALSE。
     */
    public function pasv(bool $pasv): bool
    {
        return ftp_pasv($this->ftp, $pasv);
    }

    /**
     * 上传文件到 FTP 服务器
     * @param string $remote_file 远程文件路径。
     * @param string $local_file  本地文件路径。
     * @param int    $mode        传送模式，只能为 FTP_ASCII （文本模式）或 FTP_BINARY （二进制模式）,默认为二进制。
     * @param int    $startpos    远程文件上传的开始位置。
     * @return bool 返回上传结果
     */
    public function put(string $remote_file, string $local_file, int $mode = FTP_BINARY, int $startpos = 0)
    {
        return ftp_put($this->ftp, $remote_file, $local_file, $mode, $startpos);
    }

    /**
     * 返回当前目录名
     * @return string
     */
    public function pwd(): string
    {
        return ftp_pwd($this->ftp);
    }

    /**
     * close的别名
     * @return bool
     * @deprecated 考虑移除该方法
     */
    public function quit(): bool
    {
        return ftp_quit($this->ftp);
    }

    /**
     *  向 FTP 服务器发送命令
     *
     * 将服务器的响应以字符串数组的形式返回。 对于响应内容既不做解析处理， 也不检测命令是否执行成功。
     * @param string $command 要执行的命令。
     * @return string[]
     */
    public function raw(string $command): array
    {
        return ftp_raw($this->ftp, $command);
    }

    /**
     * 返回指定目录下文件的详细列表
     * @param string $directory 目录路径。
     * @param bool   $recursive 如果此参数为 TRUE ，实际执行的命令将会为 LIST -R。
     * @return array 输出结构不会被解析
     */
    public function rawlist(string $directory, bool $recursive = false): array
    {
        return ftp_rawlist($this->ftp, $directory, $recursive);
    }

    /**
     * 更改 FTP 服务器上的文件或目录名
     *
     * 使用此方法可以移动文件或者文件夹
     * @param string $oldname 原来的文件／目录名。
     * @param string $newname 新名字。
     * @return bool
     */
    public function rename(string $oldname, string $newname): bool
    {
        return ftp_rename($this->ftp, $oldname, $newname);
    }

    /**
     * 删除 FTP 服务器上的一个目录
     * @param string $directory 要删除的目录
     * @param bool   $force     如果该目录不为空，是否强制删除
     * @return bool
     */
    public function rmdir(string $directory, bool $force = false): bool
    {
        if ($force) {
            $pwd = $this->pwd();
            $list = $this->rawlist($directory);
            foreach ($list as $row) {
                $line = explode(' ', $row);
                $name = $line[count($line) - 1];
                if (substr($directory[0], 0, 1) == '/') {  // 绝对路径
                    $full_path = $directory. '/' . $name;
                } else {
                    if ($pwd == '/') {
                        $full_path = '/' . $directory . '/' . $name;
                    } else {
                        $full_path = $pwd . '/' . $directory . '/' . $name;
                    }
                }
                if (substr($line[0], 0, 1) == 'd') {  // 是目录
                    $this->rmdir($full_path, true);
                } else {
                    $this->delete($full_path);
                }
            }
        }
        return ftp_rmdir($this->ftp, $directory);
    }

    /**
     * 设置各种 FTP 运行时选项
     * @param int      $option 选项标识
     * @param bool|int $value  本参数取决于要修改哪个 option。
     * @return bool
     */
    public function setOption(int $option, $value)
    {
        return ftp_set_option($this->ftp, $option, $value);
    }

    /**
     * 向服务器发送 SITE 命令
     *
     * @notice 非标准化方法，请谨慎使用该方法。
     * @param string $command SITE 命令
     * @return bool
     */
    public function site(string $command): bool
    {
        return ftp_site($this->ftp, $command);
    }

    /**
     * 返回指定文件的大小
     * @param string $remote_file 远程文件路径。
     * @return int
     */
    public function size(string $remote_file): int
    {
        return ftp_size($this->ftp, $remote_file);
    }

    /**
     * 打开一个到 host 的安全 FTP 连接（SSL-FTP）。
     *
     * 注意：本函数有可能不存在，只有 PHP 构建时同时包含了 ftp 模块 和 OpenSSL 模块时， ftp_ssl_connect()  函数才可用。
     * @param string $host    要连接的服务器
     * @param int    $port    端口号，默认21
     * @param int    $timeout 超时时间，默认90(秒)
     */
    private function sslConnect(string $host, int $port = 21, int $timeout = 90)
    {
        $this->ftp = ftp_ssl_connect($host, $port, $timeout);
    }

    /**
     * 返回远程 FTP 服务器的操作系统类型
     *
     * 非可靠判断，windows有可能返回UNIX
     * @return string
     */
    public function systype(): string
    {
        return ftp_systype($this->ftp);
    }
}
