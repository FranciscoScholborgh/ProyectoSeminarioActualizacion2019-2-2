from logica.Interfaces import Observer
import requests, json

class SensorStatusNoficator(Observer):

    def __init__(self):
        self.serverURL = "http://localhost/SemAct-2/rest.php"
        
    def update(self, arg):
        sensorRef = arg[0]
        status = arg[1]
        param = '{"device" : "'+str(sensorRef)+'" , "status" : "'+str(status)+'"}'
        sensorInfo = json.loads(param)
        r = requests.put(self.serverURL, json= sensorInfo)
        print(r.text)

