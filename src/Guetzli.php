<?php
namespace bingher\zipimg;

class Guetzli extends Compress
{
    protected $handle = 'guetzli';

    /**
     * Windows环境的压缩器
     *
     * @return string 压缩器路径
     */
    public static function winHandle()
    {
        //返回windows环境下压缩器路径
        return __DIR__ . '/../lib/guetzli/guetzli.exe';
    }

    /**
     * 生成命令行
     *
     * @return string
     */
    public function parseCmd()
    {
        $quality = max(84, $this->config['max_quality']);
        return sprintf(
            '%s --quality %d %s %s',
            self::safePath($this->handle),
            $quality,
            self::safePath($this->input),
            self::safePath($this->output)
        );
    }
}
