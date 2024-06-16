/*
 * This ESP32 code is created by esp32io.com
 *
 * This ESP32 code is released in the public domain
 *
 * For more detail (instruction and wiring diagram), visit https://esp32io.com/tutorials/esp32-gps
 */

#include <TinyGPS++.h>
#include <Adafruit_GFX.h>
#include <HardwareSerial.h>

#define GPS_BAUDRATE 9600  // The default baudrate of NEO-6M is 9600

TinyGPSPlus gps;  // the TinyGPS++ object
HardwareSerial SerialGPS(1);  // Use hardware serial 1 for GPS

void setup() {
  Serial.begin(115200);
  SerialGPS.begin(GPS_BAUDRATE, SERIAL_8N1, 16, 17);  // RX=16, TX=17

  Serial.println(F("ESP32 - GPS module"));
}

void loop() {
  while (SerialGPS.available() > 0) {
    gps.encode(SerialGPS.read());
  }

  if (gps.location.isUpdated()) {
    Serial.print(F("Location: "));
    Serial.print(gps.location.lat(), 6);
    Serial.print(F(", "));
    Serial.println(gps.location.lng(), 6);
  }

  if (gps.date.isUpdated()) {
    Serial.print(F("Date: "));
    Serial.print(gps.date.day());
    Serial.print(F("/"));
    Serial.print(gps.date.month());
    Serial.print(F("/"));
    Serial.println(gps.date.year());
  }

  if (gps.time.isUpdated()) {
    Serial.print(F("Time: "));
    if (gps.time.hour() < 10) Serial.print(F("0"));
    Serial.print(gps.time.hour());
    Serial.print(F(":"));
    if (gps.time.minute() < 10) Serial.print(F("0"));
    Serial.print(gps.time.minute());
    Serial.print(F(":"));
    if (gps.time.second() < 10) Serial.print(F("0"));
    Serial.println(gps.time.second());
  }

  if (gps.altitude.isUpdated()) {
    Serial.print(F("Altitude: "));
    Serial.print(gps.altitude.meters());
    Serial.println(F(" meters"));
  }

  if (gps.speed.isUpdated()) {
    Serial.print(F("Speed: "));
    Serial.print(gps.speed.kmph());
    Serial.println(F(" km/h"));
  }

  if (gps.course.isUpdated()) {
    Serial.print(F("Course: "));
    Serial.print(gps.course.deg());
    Serial.println(F(" degrees"));
  }

  if (gps.satellites.isUpdated()) {
    Serial.print(F("Satellites: "));
    Serial.println(gps.satellites.value());
  }

  if (gps.hdop.isUpdated()) {
    Serial.print(F("HDOP: "));
    Serial.println(gps.hdop.hdop());
  }

  if (millis() > 5000 && gps.charsProcessed() < 10) {
    Serial.println(F("No GPS data received: check wiring"));
  }

  delay(1000);  // Delay to avoid flooding the serial output
}
