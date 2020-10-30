<?php
namespace bingher\zipimg;

class Tinypng extends Compress
{
    /**
     * 请求APP_KEY列表
     *
     * @var array
     */
    protected $appKeys = [];

    /**
     * 接口每月最大请求数量(默认:500)
     *
     * @var int
     */
    protected $maxCountPerMonth = 500;

    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->appKey = $config['tiny_app_key'];
        if (empty($config['tiny_app_key'])) {
            throw new \InvalidArgumentException('tiny_app_key is required');
        }
        if (is_string($config['tiny_app_key'])) {
            $this->appKeys = [$config['tiny_app_key']];
        } else {
            $this->appKeys = $config['tiny_app_key'];
        }
        if (!empty($config['tiny_max_count_month'])) {
            $this->maxCountPerMonth = intval($config['tiny_max_count_month']);
        }
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

        foreach ($this->appKeys as $key) {
            try {
                \Tinify\setKey($key);
                $source = \Tinify\fromFile($this->input);
                $source->toFile($this->output);
                // $compressionsThisMonth = \Tinify\compressionCount();
                return $this->output;
            } catch (\Tinify\Exception $e) {
                if (strpos('monthly limit', $e->getMessage()) !== false) {
                    $expireKeys[] = $key;
                    $endTime      = strtotime(date('Y-m-d')) + 86400;
                    $nowTime      = time();
                    $expireTime   = $endTime - $nowTime;
                    cache('tinypng_expire_keys', $expireKeys, $expireTime);
                    continue;
                }
                break;
            }
        }
        return false;
    }
}
