#!/usr/bin/python
# -*- coding: utf-8 -*-
__author__ = 'hbh112233abc@163.com'

import sys
import json

from thrift import Thrift
from thrift.transport import TSocket, TTransport
from thrift.protocol import TBinaryProtocol

from .Transmit import Client


class Client:
    def __init__(self, host='127.0.0.1', port=8000):
        self.host = host
        self.port = port
        self.transport = TSocket.TSocket(self.host, self.port)
        self.transport = TTransport.TBufferedTransport(self.transport)
        protocol = TBinaryProtocol.TBinaryProtocol(self.transport)
        self.client = Client(protocol)

    def __enter__(self):
        self.transport.open()
        return self

    def exec(self, func, data):
        json_string = json.dumps(data)
        res = self.client.invoke(func, json_string)
        print(res)
        return res

    def __exit__(self, exc_type, exc_value, trace):
        self.transport.close()


def main():
    with Client('127.0.0.1', 8000) as client:
        func = 'sayMsg'
        data = {'msg': 'huangbh is good boy'}
        client.exec(func, data)


if __name__ == '__main__':
    main()
