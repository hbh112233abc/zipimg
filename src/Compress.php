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
     * @return void
     */
    public function quality(int $max, int $min = 0)
    {
        $this->config['max_quality'] = $max;
        if ($min > 0) {
            $this->config['min_quality'] = $max;
        }
    }

    /**
     * 输入文件设置
     *
     * @param string $file 输入文件路径
     *
     * @return void
     */
    public function input($file)
    {
        $this->input = $file;
    }

    /**
     * 输出文件设置
     *
     * @param string $file 输出文件路径
     *
     * @return void
     */
    public function output($file)
    {
        $this->output = $file;
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

        $cmd = $this->parseCmd();

        $result = shell_exec($cmd);
        if (!$result) {
            throw new \Exception('compressed failed. Is ' . __CLASS__ . ' installed?');
        }

        return $this->output;
    }
}
