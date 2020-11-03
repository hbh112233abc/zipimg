# ZipImg 一个PHP图片压缩利器

### 安装
```
composer require bingher/zipimg
```

### 使用
```
$input = 'path-of-image';
$zipimg = new \bingher\zipimg\Image();
$zipimg->zip($input);

# 助手函数
zipimg($input);
```

### 注意事项

- `php.ini`需要开启`shell_exec`函数

- windows环境 已经内置了压缩用的exe程序(32位),理论上直接用就行

- linux环境 需要编译安装`pngguant`,`guetzli`,`mozjpeg`

     - [安装pngguant](https://pngquant.org/install.html)
     ```
     # centos
     # libpng >= 1.6
     yum install libpng-devel
     git clone --recursive https://github.com/kornelski/pngquant.git
     cd pngquant
     ./configure --prefix=/usr/local/pngquant
     make && make install
     ln -s /usr/local/pngquant/bin/pngquant /usr/local/bin/pngquant
     ```

     - [安装guetzli](https://github.com/google/guetzli)
     ```
     #centos
     yum install libpng-devel
     yum install gcc gcc-c++
     mkdir guetzli && cd guetzli
     wget https://github.com/google/guetzli/archive/v1.0.1.tar.gz
     tar -zxvf v1.0.1.tar.gz
     cd guetzli-1.0.1
     make && make install
     ln -s 安装目录/bin/Release/guetzli /usr/local/bin/guetzli
     ```

     - 安装mozjpeg
     [官方教程](https://github.com/mozilla/mozjpeg/blob/master/BUILDING.txt)
     [参考教程](https://blog.liuguofeng.com/p/4644)
     ```
     # centos 7
     yum -y install build-essential nasm

     # centos 8 安装nasm
     # 到https://www.nasm.us/pub/nasm/releasebuilds下载相关rpm包
     wget https://www.nasm.us/pub/nasm/releasebuilds/2.15/linux/nasm-2.15-0.fc31.x86_64.rpm
     yum localinstall nasm-2.15-0.fc31.x86_64.rpm

     cd ~
     wget -O "mozjpeg-3.3.1.tar.gz" https://github.com/mozilla/mozjpeg/archive/v3.3.1.tar.gz
     tar -zxvf mozjpeg-v3.3.1.tar.gz
     cd mozjpeg-3.3.1
     autoreconf -fiv
     ./configure --prefix=/usr/local/mozjpeg
     make && make install
     ln -s /usr/local/mozjpeg/bin/cjpeg /usr/local/bin/cjpeg
     ```

### python服务

#### 1. php客户端远程调用python服务端进行图片压缩

> 由于生产环境php一般会禁用执行命令行等危险函数,可以考虑用开启python服务端,然后php进行调用实现图片压缩

- 进入`vendor/bingher/zipimg/python`目录
- 安装python库
```
pip install -r requirement.txt
```
- 开启服务
```
python zipimg_server.py
```
- 远程调用
```
<?php
require_once '../vendor/autoload.php';

use bingher\transmit\Client;

$client = new Client('127.0.0.1', 8000);
$params = [
    'in_img'  => __DIR__ . '/input/1.jpg',
    'out_img' => __DIR__ . '/output/1.test.jpg',
];
$result = $client->zip($params);
var_dump($result);

# 助手函数
$input = __DIR__ . '/input/1.jpg';
$result = remote_zipimg($input);
var_dump($result);
```

#### 2. python自动监控压缩图片
>
```
python ./auto_zipimg.py -w ../test/output -a ../lib/ -p
```
|参数|必须|说明|
|-|-|-|
|`-w` 或 `--watch-dir`|Y|监控目录
|`-a` 或 `--app-path`|N|windows环境下如果移动了`auto_zipimg.py`需要指定压缩软件的目录为`vendor/bingher/zipimg/lib`|
|`-p`|N|表示运行后会对监控目录原先的图片文件进行遍历压缩一遍|
