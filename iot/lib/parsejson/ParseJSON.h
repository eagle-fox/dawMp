#ifndef PARSEJSON_H
#define PARSEJSON_H

#include <Arduino.h> 

String parseJSON(const String& header, const String& value);
String parseJSONWiFiInfo();

#endif
