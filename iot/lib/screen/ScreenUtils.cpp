#include <Wire.h>
#include <Adafruit_GFX.h>
#include <Adafruit_SSD1306.h>

#define SCREEN_WIDTH 128 // Ancho de la pantalla OLED en píxeles
#define SCREEN_HEIGHT 64 // Alto de la pantalla OLED en píxeles

#define OLED_RESET -1       // Pin de reinicio (o -1 si se comparte el pin de reinicio de Arduino)
#define SCREEN_ADDRESS 0x3C // Dirección I2C de la pantalla (consulta la hoja de datos)

Adafruit_SSD1306 display(SCREEN_WIDTH, SCREEN_HEIGHT, &Wire, OLED_RESET);


// Display 1 line of screen
void sendStartMessage()
{
    display.begin(SSD1306_SWITCHCAPVCC, SCREEN_ADDRESS); // Inicializa la pantalla OLED
    display.clearDisplay();

    display.setTextSize(1);
    display.setTextColor(SSD1306_WHITE);
    display.setCursor(0, 0); // X - Y axis
    display.println("ESP32 Encendido!");
    display.display(); // Mostrar en pantalla
}


// Display 2 line of screen
void showMessageOnNextLine(const char *message)
{

    display.setTextSize(1);
    display.setTextColor(SSD1306_WHITE);
    display.setCursor(0, 10);
    display.println(message);

    display.display();
}

// Display 3 line of screen
void showMessageOnNextLine2(const char *message)
{
    display.setTextSize(1);
    display.setTextColor(SSD1306_WHITE);
    display.setCursor(0, 20);
    display.println(message);

    display.display();
}

// Display 4 line of screen 
void showMessageOnNextLine3(const char *message)
{
    display.setTextSize(1);
    display.setTextColor(SSD1306_WHITE);
    display.setCursor(10, 55);
    display.println(message);

    display.display();
}

