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
     - [安装guetzli](https://github.com/google/guetzli)
     - [安装mozjpeg](https://blog.liuguofeng.com/p/4644)
