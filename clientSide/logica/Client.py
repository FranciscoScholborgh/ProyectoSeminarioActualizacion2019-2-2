from __future__ import annotations
from abc import ABCMeta, abstractmethod
from zeep import Client
from logica.Interfaces import Subject
import socket, Pyro4, time


class ClientNotifier(metaclass=ABCMeta):

    @abstractmethod
    def run(self):
        pass

    @abstractmethod
    def shutdown(self):
        pass

class SOAPClientNotifier(ClientNotifier, Subject):

    def __init__(self):
        self.activated = False
        self.estado_anterior = "LOCKED"
        self.nofitiferService = Client(wsdl='http://localhost/SemAct-2/sensorStatus.wsdl')
        self.observers: List[Observer] = []
        
    def attach(self, observer: Observer):
        self.observers.append(observer)

    def detach(self, observer: Observer):
        self.observers.remove(observer)

    def notify(self):
        for observer in self.observers:
            observer.update(self.estado_anterior)

    def run(self):
        self.activated = True
        while (self.activated):
            try:
                command = self.nofitiferService.service.getSensorStatus("MC-38")
                if(command != self.estado_anterior):      
                    if(command == "BREAK IN"):
                        self.estado_anterior = command
                        self.notify()
                    elif(command == "LOCKED"):
                        self.estado_anterior = command
                        self.notify()
                    else:
                        self.estado_anterior = command
                        self.notify()
            except:
                command == "SHUTDOWN"
                self.estado_anterior = command
                self.notify()
            time.sleep(0.25)

    def shutdown(self):
        self.activated = False