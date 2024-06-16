#include "ParseJSON.h"

#include <Arduino.h>

String parseJSON(const String &header, const String &value)
{
  return String("{\"") + header + "\":\"" + value + "\"}";
}

String parseJSONWiFiInfo(int tx, String gateway, String mac, int rssi)
{
    String jsonString = "{";
    jsonString += "\"tx\": \"" + String(tx) + "\", ";
    jsonString += "\"gateway\": \"" + gateway + "\", ";
    jsonString += "\"mac\": \"" + mac + "\", ";
    jsonString += "\"rssi\": \"" + String(rssi) + "\"";
    jsonString += "}";

    return jsonString;
}