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
