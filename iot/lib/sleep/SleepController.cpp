#include <esp_sleep.h>
#include "SleepController.h"

const int sleepModeButtonPin = 0;

void sleepMode()
{
    pinMode(sleepModeButtonPin, INPUT_PULLUP);
    esp_sleep_enable_ext0_wakeup((gpio_num_t)sleepModeButtonPin, LOW);
    Serial.println("Entrando en modo de bajo consumo...");
    esp_deep_sleep_start();
}