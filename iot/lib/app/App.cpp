#include <WiFi.h>
#include <HTTPClient.h>
#include "App.h"
#include "HTTPClientController.h"
#include "ParseJSON.h"
#include "WifiUtils.h"
#include "HTTPClientController.h"
#include <Pangodream_18650_CL.h>
#include "ScreenUtils.h"
#include "GPSController.h"

#define BUZZER_PIN 14

Pangodream_18650_CL BL; // Declaración de la instancia de la clase Pangodream_18650_CL

const char *ssid = "testSSID";
const char *password = "testPassword";

const int pinLED = 2;
const int pinLED_2 = 5;
const int pinLED_3 = 12;
const int pinLED_4 = 13;
const int pinBateria = 35;
const int pinButton = 4;

const char *serverEndpoint = "http://192.168.1.20:3000/ruta";
String hostname = "EagleFox Collar";

void setupApp()
{
  Serial.begin(115200);
  sendStartMessage();

  int attempts = 0;

  // Conectar a WiFi
  WiFi.setHostname(hostname.c_str());
  WiFi.begin(ssid, password);

  const int MAX_ATTEMPTS = 13;

  while (WiFi.status() != WL_CONNECTED)
  {
    delay(1000);
    Serial.print("WiFi Connection attempt: ");
    Serial.println(++attempts);

    if (attempts >= MAX_ATTEMPTS)
    {
      Serial.println("Maximum number of attempts reached. Exiting WiFi connection loop.");
      break;
    }
  }

  if (WiFi.status() == WL_CONNECTED)
  {
    Serial.println("WiFi connected!");
    char wifiMessage[30];
    snprintf(wifiMessage, sizeof(wifiMessage), "WiFi: %s", ssid);
    showMessageOnNextLine(wifiMessage);
  }
  else
  {
    Serial.println("Failed to connect to WiFi after maximum attempts.");
    char wifiMessage[30];
    snprintf(wifiMessage, sizeof(wifiMessage), "WiFi: %s", "Failed...");
    showMessageOnNextLine(wifiMessage);
  }

  Serial.println("WiFi Connection Successfully!");

  digitalWrite(BUZZER_PIN, HIGH); // Establecer el pin en HIGH
  delay(1000);

  digitalWrite(BUZZER_PIN, LOW); // Establecer el pin en LOW
  delay(1000);

  showWifiIntense();
  showTxPower();
  Serial.println(String("Network Gateway: ") + WiFi.gatewayIP().toString());
  Serial.println(String("MAC: ") + WiFi.macAddress());

  Serial.print("IP Address: ");
  Serial.println(WiFi.localIP());

  switchLED(pinLED, true);
  switchLED(pinLED_2, true);

  pinMode(pinButton, INPUT_PULLUP);


  showMessageOnNextLine2("GPS:No data available");
  showMessageOnNextLine3("ID: 606a698a9bbfe6");
}

void loopApp()
{
  if (digitalRead(pinButton) == LOW)
  {
    // sendDataServer(parseJSON("data", WiFi.localIP().toString()));
    delay(1000);
  }

  delay(1000);
}