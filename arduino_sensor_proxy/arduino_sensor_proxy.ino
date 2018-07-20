// Uses the adafruit dht library
// https://github.com/adafruit/DHT-sensor-library
#include "DHT.h"
 
#define DHTPIN 5     // what pin we're connected to
#define DHTTYPE DHT22   // DHT 22  (AM2302)


DHT dht(DHTPIN, DHTTYPE);

void setup() {
  //Serial Port begin
  Serial.begin (9600);
  
  dht.begin();
  
}

double Celcius2Fahrenheit(double celsius)
{
  return 1.8 * celsius + 32;
}

void read_dht()
{
  float h = dht.readHumidity();
  // Read temperature as Celsius
  float t = Celcius2Fahrenheit(dht.readTemperature());

  Serial.print("Humidity: "); 
  Serial.print(h);
  Serial.print(" %\t");
  Serial.print("Temperature: "); 
  Serial.println(t);
  
}

int read_analog(int pin)
{
  int value = analogRead(pin);
  Serial.print("Analog pin: ");
  Serial.print(pin);
  Serial.print(" Value: ");
  Serial.println(value);
}
 
void loop()
{
 
 while (Serial.available() > 0) {
  int incomingByte = Serial.read();
  if (incomingByte == 't') read_dht();
  if (incomingByte == '0') read_analog(A0);
  if (incomingByte == '1') read_analog(A1);
  if (incomingByte == '2') read_analog(A2);
  if (incomingByte == '3') read_analog(A3);
  if (incomingByte == '4') read_analog(A4);
  if (incomingByte == '5') read_analog(A5);
  if (incomingByte == '6') read_analog(A6);
  if (incomingByte == '7') read_analog(A7);
 }
}
