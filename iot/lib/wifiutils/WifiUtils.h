#ifndef WIFI_UTILS_H
#define WIFI_UTILS_H

#include <WiFi.h>
#include <Arduino.h>


void showWifiIntense();
void showTxPower();

int getWifiIntense();
int getTxPower();
std::string getGatewayAddress();
std::string getMacAddress();
bool checkWifiConnection();

#endif

