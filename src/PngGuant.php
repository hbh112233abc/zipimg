<?php
namespace bingher\zipimg;

/**
 * Pngguant压缩器
 */
class PngGuant extends Compress
{
    protected $handle = 'pngguant';

    /**
     * Windows环境的压缩器
     *
     * @return string 压缩器路径
     */
    public static function winHandle()
    {
        //返回windows环境下压缩器路径
        return __DIR__ . '/../lib/pngguant/pngguant.exe';
    }

    /**
     * 生成命令行
     *
     * @return string
     */
    public function parseCmd()
    {
        return sprintf(
            "%s --quality=%d-%d --output=%s - < %s",
            $this->handle,
            $this->config['min_quality'],
            $this->config['max_quality'],
            escapeshellarg($this->output),
            escapeshellarg($this->input)
        );
    }

}
