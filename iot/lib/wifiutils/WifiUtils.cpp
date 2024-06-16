#include "WifiUtils.h"

//            INSTRUCTIONS            //
// Excellent: RSSI greater than -50 dBm
// Good: RSSI between -50 dBm and -60 dBm
// Acceptable: RSSI between -60 dBm and -70 dBm
// Weak: RSSI less than -70 dBm
void showWifiIntense()
{
  int rssi = WiFi.RSSI();
  Serial.print("Signal strength WiFi (RSSI): ");
  Serial.print(rssi);
  Serial.println(" dBm");
}

//            INSTRUCTIONS              //
// 82: for a transmitting power of +20 dBm
// 78: for a transmit power of +17 dBm
// 74: for a transmitting power of +14 dBm
// 70: for a transmit power of +11 dBm
// 68: for a transmit power of +8 dBm
// 64: for a transmit power of +5 dBm
// 60: for a transmit power of +2 dBm
// 58: for a transmitting power of -1 dBm
// 56: for a transmit power of -2 dBm
void showTxPower()
{
  int txPower = WiFi.getTxPower();
  Serial.print("Transmission power WiFi: ");
  Serial.print(txPower);
  Serial.println(" dBm");
}


int getWifiIntense(){
  int rssi = WiFi.RSSI();
  return rssi;
}

int getTxPower(){
  int txPower = WiFi.getTxPower();
  return txPower;
}

// Get the GatewayAddress of the WiFi connection
std::string getGatewayAddress()
{
  IPAddress gateway = WiFi.gatewayIP();
  char str[16];
  snprintf(str, sizeof(str), "%d.%d.%d.%d", gateway[0], gateway[1], gateway[2], gateway[3]);
  return std::string(str);
}

// Get the MacAddress
std::string getMacAddress()
{
  byte mac[6];
  WiFi.macAddress(mac);

  char macStr[18]; // Enough space for MAC address in format XX:XX:XX:XX:XX:XX and null terminator
  snprintf(macStr, sizeof(macStr), "%02X:%02X:%02X:%02X:%02X:%02X",
           mac[0], mac[1], mac[2], mac[3], mac[4], mac[5]);
  return std::string(macStr);
}



bool checkWifiConnection()
{
  return WiFi.status() == WL_CONNECTED;
}
