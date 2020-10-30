<?php
namespace bingher\zipimg;

/**
 * 压缩器抽象类
 */
abstract class Compress
{
    /**
     * 压缩器处理模块
     *
     * @var string
     */
    protected $handle;

    /**
     * 配置项
     *
     * @var array
     */
    protected $config = [
        'min_quality' => 60, //图片压缩质量
        'max_quality' => 80, //图片压缩质量
        'backup'      => false, //是否备份图片
        'backup_dir'  => '', //图片备份目录,未配置与源文件同目录
    ];

    /**
     * 输入文件
     *
     * @var string
     */
    protected $input = '';

    /**
     * 输出文件
     *
     * @var string
     */
    protected $output = '';

    /**
     * 构造器
     *
     * @param array $config 配置项
     *
     * @return void
     */
    public function __construct(array $config = [])
    {
        $this->config = array_merge($this->config, $config);
        if (static::isWin()) {
            $this->handle = static::winHandle();
        }
    }

    /**
     * 是否windows环境
     * PHP_OS的值一般可以为:
     * CYGWIN_NT-5.1,
     * Darwin,
     * FreeBSD,
     * HP-UX,
     * IRIX64,
     * Linux,
     * NetBSD,
     * OpenBSD,
     * SunOS,
     * Unix,
     * WIN32,
     * WINNT,
     * Windows
     *
     * @return string 平台名称(win | no-win)
     */
    public static function isWin()
    {
        if (strpos(strtolower(PHP_OS), 'win') === 0) {
            return true;
        }
        return false;
    }

    /**
     * Windows环境的压缩器
     *
     * @return string 压缩器路径
     */
    public static function winHandle()
    {
        //返回windows环境下压缩器路径
    }

    /**
     * 设置图片质量
     *
     * @param int $max 最高质量
     * @param int $min 最低质量(默认60)
     *
     * @return self
     */
    public function quality(int $max, int $min = 0)
    {
        $this->config['max_quality'] = $max;
        if ($min > 0) {
            $this->config['min_quality'] = $max;
        }
        return $this;
    }

    /**
     * 是否备份源文件
     *
     * @param bool   $flag 备份标识:bool表示开关
     * @param string $dir  备份目录
     *
     * @return self
     */
    public function backup(bool $flag = true, string $dir = '')
    {
        $this->config['backup'] = $flag;

        if (!empty($dir)) {
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            $this->config['backup_dir'] = $dir;
        }
        return $this;
    }

    /**
     * 输入文件设置
     *
     * @param string $file 输入文件路径
     *
     * @return self
     */
    public function input($file)
    {
        $this->input = $file;
        return $this;
    }

    /**
     * 输出文件设置
     *
     * @param string $file 输出文件路径
     *
     * @return self
     */
    public function output($file)
    {
        $this->output = $file;
        return $this;
    }

    /**
     * 检查输入文件
     *
     * @return void
     */
    public function checkInput()
    {
        if (!is_file($this->input)) {
            throw new \Exception('input file not exists');
        }
        if (empty($this->output)) {
            $this->output = $this->input;
        }
    }

    /**
     * 生成命令行
     *
     * @return string
     */
    public function parseCmd()
    {
        return sprintf(
            '%s %s %s',
            $this->handle,
            $this->input,
            $this->output
        );
    }

    /**
     * 路径全转为/分隔符
     *
     * @param string $path 文件路径
     *
     * @return string       转换后路径
     */
    public static function safePath($path)
    {
        $path = str_replace('\\', '/', $path);
        return escapeshellarg($path);
    }

    /**
     * 备份文件
     *
     * @return void
     */
    public function backupSrcFile()
    {
        if (!$this->config['backup']) {
            //未开启备份
            return;
        }
        $backupDir  = $this->config['backup_dir'];
        $backupName = basename($this->input);
        if (empty($backupDir)) {
            $backupDir  = dirname($this->input);
            $backupName = $backupName . '.bk';
        }
        $backupFile = $backupDir . DIRECTORY_SEPARATOR . $backupName;
        if (is_file($backupFile)) {
            //已经有备份文件了
            return;
        }
        copy($this->input, $backupFile);
    }

    /**
     * 压缩操作
     *
     * @param string $input 输入文件路径
     *
     * @return string        输出文件路径
     */
    public function zip($input = '')
    {
        if (!empty($input)) {
            $this->input = $input;
        }
        $this->checkInput();

        $this->backupSrcFile();

        $cmd = $this->parseCmd();
        shell_exec($cmd);
        return $this->output;
    }
}
