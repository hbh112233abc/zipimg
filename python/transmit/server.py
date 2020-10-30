#!/usr/bin/python
# -*- coding: utf-8 -*-
__author__ = 'hbh112233abc@163.com'

import json
import socket

from thrift.transport import TSocket, TTransport
from thrift.protocol import TBinaryProtocol
from thrift.server import TServer

from .Transmit import *
from .ttypes import *


class Server:
    def __init__(self, port=8000, host='0.0.0.0'):
        self.port = port
        self.host = host

    def run(self):
        processor = Processor(self)
        transport = TSocket.TServerSocket(self.host, self.port)
        tfactory = TTransport.TBufferedTransportFactory()
        pfactory = TBinaryProtocol.TBinaryProtocolFactory()
        server = TServer.TThreadPoolServer(processor, transport, tfactory,
                                           pfactory)
        print(f'start python server...{self.host}:{self.port}')
        server.serve()

    def invoke(self, func, data):
        if not getattr(self, func):
            raise f'{func} not found'

        params = json.loads(data)
        return getattr(self, func)(**params)


if __name__ == '__main__':
    Server().run()
