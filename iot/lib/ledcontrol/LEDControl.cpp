#include "LEDControl.h"
#include <Arduino.h>

void outputLED(int pinLED, int timeDelay) {
  pinMode(pinLED, OUTPUT);
  digitalWrite(pinLED, HIGH);
  
  delay(timeDelay);
  digitalWrite(pinLED, LOW);
}


void switchLED(int pinLED, bool status) {
  pinMode(pinLED, OUTPUT);
  if (status)
  {
    digitalWrite(pinLED, HIGH); 
    return;
  }
  digitalWrite(pinLED, LOW); 
} 