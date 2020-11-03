#!/usr/bin/python
# -*- coding: utf-8 -*-
__author__ = 'hbh112233abc@163.com'

import os
import json
import platform
import shutil

import envoy

from transmit.server import Server


def is_win():
    """判断是否windows环境

    Returns:
        bool: 是否win环境
    """
    if platform.system().lower() == 'windows':
        return True
    return False


class ZipimgServer(Server):
    ZIP_EXT = ['.png', '.jpg', '.jpeg', '.tga', '.bmp', '.ppm']
    DEFAULT_CONFIG = {
        'win_lib_path': './vendor/bingher/zipimg/lib/',
        'backup': True,
        'max_quality': 80,
        'min_quality': 60,
    }

    def config(self, **kw):
        ZipimgServer.DEFAULT_CONFIG.update(kw)

    def zip(self, in_img, out_img=''):
        """压缩图片

        Args:
            in_img (str): 源文件
            out_img (str, optional): 压缩后文件. Defaults to ''.

        Raises:
            Exception: 格式检测结果

        Returns:
            bool: 压缩结果
        """
        ext = os.path.splitext(in_img)[-1].lower()
        if ext not in ZipimgServer.ZIP_EXT:
            raise Exception('不支持的图片格式')
        config = ZipimgServer.DEFAULT_CONFIG
        if not out_img:
            out_img = in_img

        bk_img = in_img
        if config.get('backup', True):
            bk_img = self.backup(in_img)

        if ext == '.png':
            print('use pngquant')
            driver = Pngquant(**config)

        elif ext in ['.tga', '.bmp', '.ppm', '.jpeg', '.jpg']:
            print('use mozjpeg')
            driver = Mozjpeg(**config)
        else:
            print('use guetzli')
            driver = Guetzli(**config)
        old_size = os.path.getsize(in_img)
        res = driver.zip(bk_img, out_img)
        new_size = os.path.getsize(out_img)

        print(
            f'zip result:{round(old_size/1024,2)}KB->{round(new_size/1024,2)}KB')
        print(f'zip rate:{round(new_size/old_size*100,2)}%')
        return out_img

    def backup(self, in_img, bk_img=''):
        """图片备份

        Args:
            in_img (str): 源文件
            bk_img (str, optional): 备份文件. Defaults to ''.
        Return:
            bool: 备份结果
        """
        if not bk_img:
            bk_img = in_img + '.bk'
        shutil.copy(in_img, bk_img)
        print('backup_image:', bk_img)
        return bk_img


class ZipDriver():
    def __init__(self, **kw):
        self.config = kw

    @staticmethod
    def safePath(path):
        return path.replace('\\', '/')

    def run(self, cmd):
        print(cmd)
        result = envoy.run(cmd)
        print(result.status_code, result.std_out, result.std_err)
        return result.status_code


class Pngquant(ZipDriver):
    def __init__(self, **kw):
        super().__init__(**kw)
        self.app = 'pngquant'
        if is_win():
            win_lib_path = kw.get('win_lib_path',
                                  './vendor/bingher/zipimg/lib/')
            self.app = os.path.join(win_lib_path, 'pngquant', 'pngquant.exe')
            self.app = self.safePath(self.app)

    def zip(self, in_img, out_img):
        in_img = self.safePath(in_img)
        out_img = self.safePath(out_img)
        cmd = '{} --quality={}-{} --output="{}" --force "{}"'.format(
            self.app, self.config.get('min_quality', 60),
            self.config.get('max_quality', 80), out_img, in_img)
        return self.run(cmd)


class Guetzli(ZipDriver):
    def __init__(self, **kw):
        super().__init__(**kw)
        self.app = 'guetzli'
        if is_win():
            win_lib_path = kw.get('win_lib_path',
                                  './vendor/bingher/zipimg/lib/')
            self.app = os.path.join(win_lib_path, 'guetzli', 'guetzli.exe')
            self.app = self.safePath(self.app)

    def zip(self, in_img, out_img):
        in_img = self.safePath(in_img)
        out_img = self.safePath(out_img)
        quality = max(84, self.config.get('max_quality', 84))
        cmd = '{} --quality {} "{}" "{}"'.format(self.app, quality, in_img,
                                                 out_img)
        return self.run(cmd)


class Mozjpeg(ZipDriver):
    def __init__(self, **kw):
        super().__init__(**kw)
        self.app = 'cjpeg'
        if is_win():
            win_lib_path = kw.get('win_lib_path',
                                  './vendor/bingher/zipimg/lib/')
            self.app = os.path.join(win_lib_path, 'mozjpeg',
                                    'cjpeg-static.exe')
            self.app = self.safePath(self.app)

    def zip(self, in_img, out_img):
        in_img = self.safePath(in_img)
        out_img = self.safePath(out_img)
        cmd = '{} -quality {} -outfile "{}" "{}"'.format(
            self.app, self.config.get('max_quality', 80), out_img, in_img)
        return self.run(cmd)


if __name__ == "__main__":
    zip_server = ZipimgServer()
    zip_server.config(**{'win_lib_path': '../lib/'})

    # in_img = '../test/input/1.jpg'
    # out_img = '../test/output/1_test.jpg'
    # zip_server.zip(in_img, out_img)

    # in_img = '../test/input/3.png'
    # out_img = '../test/output/3.png'
    # zip_server.zip(in_img, out_img)

    zip_server.run()
