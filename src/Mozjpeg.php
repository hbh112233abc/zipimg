<?php
namespace bingher\zipimg;

/**
 * Mozjpeg压缩器
 */
class Mozjpeg extends Compress
{
    protected $handle = 'cjpeg';

    /**
     * Windows环境的压缩器
     *
     * @return string 压缩器路径
     */
    public static function winHandle()
    {
        //返回windows环境下压缩器路径
        return __DIR__ . '/../lib/mozjpeg/cjpeg-static.exe';
    }

    /**
     * 生成命令行
     *
     * @return string
     */
    public function parseCmd()
    {
        return sprintf(
            '%s -quality %d -outfile %s %s',
            self::safePath($this->handle),
            $this->config['max_quality'],
            self::safePath($this->output),
            self::safePath($this->input)
        );
    }
}
