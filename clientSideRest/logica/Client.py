from __future__ import annotations
from abc import ABCMeta, abstractmethod
from logica.Interfaces import Subject
import requests, json, time

class ClientNotifier(metaclass=ABCMeta):

    @abstractmethod
    def run(self):
        pass

    @abstractmethod
    def shutdown(self):
        pass

class RestClientNotifier(ClientNotifier, Subject):

    def __init__(self):
        self.activated = False
        self.estado_anterior = "LOCKED"
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
                command = requests.get('http://localhost/SemAct-2/rest.php?device=MC-38')
                jsonData = json.loads(command.text)
                status = jsonData['status']
                if(status != self.estado_anterior):      
                    if(command == "BREAK IN"):
                        self.estado_anterior = status
                        self.notify()
                    elif(command == "LOCKED"):
                        self.estado_anterior = status
                        self.notify()
                    else:
                        self.estado_anterior = status
                        self.notify()
            except:
                command == "SHUTDOWN"
                self.estado_anterior = command
                self.notify()
            time.sleep(0.25)

    def shutdown(self):
        self.activated = False