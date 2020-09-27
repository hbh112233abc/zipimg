<?php
namespace bingher\zipimg;

/**
 * 压缩图片类
 */
class Image
{
    /**
     * 运行实例:用来保存当前类的实例
     * 1. 为什么必须是静态的?因为静态成员属于类,并被类所有实例所共享
     * 2. 为什么必须是私有的?不允许外部直接访问,仅允许通过类方法控制方法
     * 3. 为什么要有初始值null,因为类内部访问接口需要检测实例的状态,判断是否需要实例化
     *
     * @var object
     */
    private static $_instance = null;

    /**
     * 运行平台 win | unix
     *
     * @var string
     */
    protected $platform = 'win';

    /**
     * 压缩驱动
     *
     * @var object
     */
    protected $driver = null;

    /**
     * 图片后缀
     *
     * @var string
     */
    protected $ext = '';

    /**
     * 保存用户的自定义配置参数
     *
     * @var array
     */
    private $_config = [];

    /**
     * 构造器
     *
     * @param array $config 配置参数
     *
     * @return self::$_instance
     */
    public function __construct(array $config = [])
    {
        $this->_config = array_merge($this->_config, $config);
    }

    /**
     * 图片压缩
     *
     * @param string $input  输入文件路径
     * @param string $output 输出文件路径
     *
     * @return string 输出文件
     */
    public function zip($input, $output = '')
    {
        $ext = strtolower(pathinfo($input, PATHINFO_EXTENSION));
        if ($ext == 'png') {
            $this->dirver = new Pngguant($this->_config);
        }
        if (in_array($ext, ['tga', 'bmp', 'ppm', 'jpeg'])) {
            $this->dirver = new Mozjpeg($this->_config);
        } else {
            $this->driver = new Guetzli($this->_config);
        }
        return $this->driver->zip($input, $output);
    }

    /**
     * 克隆方法私有化:禁止从外部克隆对象
     *
     * @return void
     */
    private function __clone()
    {
        //私有化无法克隆
    }

    /**
     * 获取实例
     * 因为用静态属性返回类实例,而只能在静态方法使用静态属性
     * 所以必须创建一个静态方法来生成当前类的唯一实例
     *
     * @return self::$_instance
     */
    public static function getInstance()
    {
        //检测当前类属性$instance是否已经保存了当前类的实例
        if (self::$_instance == null) {
            //如果没有,则创建当前类的实例
            self::$_instance = new self();
        }
        //如果已经有了当前类实例,就直接返回,不要重复创建类实例
        return self::$_instance;
    }

    /**
     * 设置配置项
     *
     * @param string $index 配置键名
     * @param mix    $value 配置项值
     *
     * @return void
     */
    public function set($index, $value)
    {
        $this->_config[$index] = $value;
    }

    /**
     * 读取配置项
     *
     * @param string $index 配置键名
     *
     * @return void
     */
    public function get($index)
    {
        return $this->_config[$index];
    }
}
