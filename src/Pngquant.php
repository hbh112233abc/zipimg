<?php
namespace bingher\zipimg;

/**
 * Pngquant压缩器
 */
class Pngquant extends Compress
{
    protected $handle = 'pngquant';

    /**
     * Windows环境的压缩器
     *
     * @return string 压缩器路径
     */
    public static function winHandle()
    {
        //返回windows环境下压缩器路径
        return __DIR__ . '/../lib/pngquant/pngquant.exe';
    }

    /**
     * 生成命令行
     *
     * @return string
     */
    public function parseCmd()
    {
        return sprintf(
            "%s --quality=%d-%d --output=%s --force - < %s",
            self::safePath($this->handle),
            $this->config['min_quality'],
            $this->config['max_quality'],
            self::safePath($this->output),
            self::safePath($this->input)
        );
    }

}
