from PyQt5.QtWidgets import QMainWindow, QApplication
from PyQt5.QtGui import QIcon
from PyQt5.QtCore import QSize, QDir
from PyQt5 import uic
import sys
from logica.Arduino import ArduinoDetector
from logica.Notifications import SensorStatusNoficator
import threading, time, os, inspect

class GUI(QMainWindow):

    def __init__(self):
        super(GUI, self).__init__()
        uic.loadUi('vista/watchDog.ui', self)
        self.stateBtn.clicked.connect(self.runWatchDog)
        self.show()  

    def setting_watchDog(self):
        self.stateBtn.setEnabled(False)
        ardDetector = ArduinoDetector.getInstance()
        self.arduino = ardDetector.detectarPrototipo()
        if(self.arduino is not None):
            self.stateBtn.clicked.disconnect(self.runWatchDog)
            self.stateLbl.setText("Estado Servidor: Activo")
            self.stateBtn.setText("Stop")
            icon = QIcon(os.path.dirname(os.path.abspath(inspect.getfile(inspect.currentframe()))) + QDir.separator() +  "vista/stopicon.png")
            self.stateBtn.setIcon(icon)
            self.stateBtn.setIconSize(QSize(30, 30))
            self.stateBtn.clicked.connect(self.stopWatchDog)
            serviceUpdater = SensorStatusNoficator()
            self.arduino.attach(serviceUpdater)
            t = threading.Thread(target=self.arduino.start_reading, daemon=True)
            t.start()
        else:
            print("No ha sido encontrado el dispositivo de alarma")
        time.sleep(1)
        self.stateBtn.setEnabled(True)

    def shutdown_watchDog(self):
        self.arduino.stop_reading()
        self.stateBtn.clicked.disconnect(self.stopWatchDog)
        self.stateBtn.setEnabled(False)
        self.stateLbl.setText("Estado Servidor: Inactivo")
        self.stateBtn.setText("Run")
        icon = QIcon(os.path.dirname(os.path.abspath(inspect.getfile(inspect.currentframe()))) + QDir.separator() +  "vista/runicon.png")
        self.stateBtn.setIcon(icon)
        self.stateBtn.setIconSize(QSize(30, 30))
        time.sleep(1)
        self.stateBtn.setEnabled(True)
        self.stateBtn.clicked.connect(self.runWatchDog)

    def runWatchDog(self):
        t = threading.Thread(target=self.setting_watchDog, daemon=True)
        t.start()

    def stopWatchDog(self):
        print("STOPPED")
        t = threading.Thread(target=self.shutdown_watchDog, daemon=True)
        t.start()
        
app = QApplication(sys.argv)
gui = GUI()
app.exec_()