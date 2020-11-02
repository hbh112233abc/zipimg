#!/usr/bin/python
# -*- coding: utf-8 -*-
__author__ = 'hbh112233abc@163.com'

# 安装插件 pip install watchdog

import sys
import os
import getopt
import time

from watchdog.observers import Observer
from watchdog.events import *

from zipimg_server import ZipimgServer, is_win


class AutoZipimg(FileSystemEventHandler):
    def __init__(self, app_path=''):
        super().__init__()
        self.zipimg = ZipimgServer()
        if not app_path and is_win():
            raise Exception('windows env must set app_path')
        if not os.path.isdir(app_path):
            raise Exception(f'app_path not exists:{app_path}')

        self.zipimg.config(**{'win_lib_path': app_path})

    def on_moved(self, event):
        if event.is_directory:
            print("directory moved from {0} to {1}".format(
                event.src_path, event.dest_path))
        else:
            print("file moved from {0} to {1}".format(
                event.src_path, event.dest_path))

    def on_created(self, event):
        if event.is_directory:
            print("directory created:{0}".format(event.src_path))
        else:
            print("file created:{0}".format(event.src_path))
            try:
                ext = os.path.splitext(event.src_path)[-1]
                if ext not in self.zipimg.ZIP_EXT:
                    return
                time.sleep(3)
                print('auto zipimg')
                res = self.zipimg.zip(event.src_path)
            except Exception as e:
                print(str(e))

    def on_deleted(self, event):
        if event.is_directory:
            print("directory deleted:{0}".format(event.src_path))
        else:
            print("file deleted:{0}".format(event.src_path))

    def on_modified(self, event):
        if event.is_directory:
            print("directory modified:{0}".format(event.src_path))
        else:
            print("file modified:{0}".format(event.src_path))


def check_params():
    watch_dir = ''
    app_path = ''
    try:
        argv = sys.argv[1:]
        opts, args = getopt.getopt(argv, "hw:a:", ["watch_dir=", "app_path="])
    except getopt.GetoptError:
        print('auto_zipimg.py -w <watch_dir> -a <app_path>')
        sys.exit(2)
    for opt, arg in opts:
        if opt == '-h':
            print('auto_zipimg.py -w <watch_dir> -a <app_path>')
            sys.exit()
        if opt in ("-w", "--watch_dir"):
            watch_dir = arg
        elif opt in ("-a", "--app_path"):
            app_path = arg
    if not watch_dir:
        print('watch_dir must')
        sys.exit(2)
    if not app_path and is_win():
        print('app_path must')
        sys.exit(2)

    return watch_dir, app_path


def main():
    watch_dir, app_path = check_params()
    observer = Observer()
    event_handler = AutoZipimg(app_path=app_path)
    observer.schedule(event_handler, watch_dir, True)
    observer.start()
    print('auto zipimg start...')
    print('watch dir:', watch_dir)
    try:
        while True:
            time.sleep(1)
    except KeyboardInterrupt:
        observer.stop()
    observer.join()


if __name__ == "__main__":
    # python .\auto_zipimg.py -w ../test/output -a ../lib/
    main()
