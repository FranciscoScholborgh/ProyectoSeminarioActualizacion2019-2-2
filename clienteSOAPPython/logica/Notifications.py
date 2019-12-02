from logica.Interfaces import Observer
from zeep import Client

class SensorStatusNoficator(Observer):

    def __init__(self):
        self.updateService = Client(wsdl='http://localhost/SemAct-2/watchDog.wsdl')
        
    def update(self, arg):
        try:
            sensorRef = arg[0]
            status = arg[1]
            print("FULL HD TETE", self.updateService.service.updateStatus(sensorRef,status))
        except:
            return False
		#self.updateService.service.updateStatus(sensorRef,status)
