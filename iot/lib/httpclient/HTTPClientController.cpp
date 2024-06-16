#include "HTTPClientController.h"
#include "LEDControl.h"
#include "ParseJSON.h"

#include <Arduino.h>
#include <WiFi.h>
#include <HTTPClient.h>



// Send data to the server ENDPOINT
bool sendDataServer(const String &dataSendEndpoint)
{
  HTTPClient http;
  http.begin(serverEndpoint);
  http.addHeader("Content-Type", "application/json");

  String jsonPayload = String(dataSendEndpoint);
  int httpResponseCode = http.POST(jsonPayload);

  bool success = false;

  if (httpResponseCode > 0)
  {
    outputLED(pinLED_3, 1000);
    success = true;
  }
  else
  {
    outputLED(pinLED_4, 1000);
    success = false;
  }
  http.end();
  return success;
}


void connectionTest() {
  Serial.println("=== Connection Test ===");
  int successPost = 0;
  for (int i = 0; i < 10; i++){
    if (sendDataServer(parseJSON("data", WiFi.localIP().toString())))
    {
      successPost++;
    }

    Serial.print(successPost); 
    Serial.print("/");         
    Serial.println(i + 1);

    delay(1000);
  }
}