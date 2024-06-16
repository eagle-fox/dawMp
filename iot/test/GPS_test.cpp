#include <TinyGPS++.h>
#include <Adafruit_GFX.h>
#include <HardwareSerial.h>
#include <BluetoothSerial.h>

// Configuración de pines para el GPS
const int RXPin = 16, TXPin = 17;
const uint32_t GPSBaud = 9600;

// Crear instancia de HardwareSerial para el GPS
HardwareSerial ss(1);

// Crear instancia de BluetoothSerial
BluetoothSerial SerialBT;

void setup() {
  // Configuración del monitor serie a 115200 baudios
  Serial.begin(115200);
  // Configuración del puerto serie para el GPS
  ss.begin(GPSBaud, SERIAL_8N1, RXPin, TXPin);

  // Inicialización del Bluetooth
  SerialBT.begin("ESP32GPS"); // Nombre del dispositivo Bluetooth
  Serial.println("Bluetooth iniciado, esperando conexión...");
}

void loop() {
  // Verificar si hay datos disponibles desde el GPS
  while (ss.available() > 0) {
    // Leer una línea completa
    String nmeaLine = ss.readStringUntil('\n');
    
    // Mostrar la línea NMEA en el monitor serie
    Serial.println(nmeaLine);

    // Enviar la línea NMEA por Bluetooth
    SerialBT.println(nmeaLine);
  }

  delay(1000); // Espera de 1 segundo antes de la próxima lectura
}