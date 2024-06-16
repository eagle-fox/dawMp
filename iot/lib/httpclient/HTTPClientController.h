#ifndef HTTPCLIENTCONTROLLER_H
#define HTTPCLIENTCONTROLLER_H


#include <Arduino.h>
#include <HTTPClient.h>
#include <WiFi.h>
#include "LEDControl.h"
#include "ParseJSON.h"

extern const char *serverEndpoint;
extern const int pinLED_3;
extern const int pinLED_4;

bool sendDataServer(const String &dataSendEndpoint);
void connectionTest();

#endif