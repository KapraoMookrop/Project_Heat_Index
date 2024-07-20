#include <DHT.h>
#define DHTPIN 4        // GPIO ที่เชื่อมต่อกับ DHT22
#define DHTTYPE DHT22   // กำหนดประเภทเซนเซอร์เป็น DHT22

DHT dht(DHTPIN, DHTTYPE);

// กำหนดพินสำหรับแต่ละเซกเมนต์ของจอ 7-segment
const int segmentPins[] = {5, 6, 7, 8, 9, 10, 11, 12}; // a, b, c, d, e, f, g, d
const int digitPins[] = {13, 14, 15, 16}; // ขาของหลัก 1, 2, 3, 4

const byte digitPatterns[10] = {
  // gfedcba
  0b11000000, // 0
  0b11111001, // 1
  0b10100100, // 2
  0b10110000, // 3
  0b10011001, // 4
  0b10010010, // 5
  0b10000010, // 6
  0b11111000, // 7
  0b10000000, // 8
  0b10010000  // 9
};

const byte letterPatterns[3] = {
  // gfedcba
  0b10000110, // C
  0b10001100, // H
  0b11000000  // I
};

void setup() {
  Serial.begin(115200);
  dht.begin();

  // ตั้งค่าแต่ละพินเป็น OUTPUT
  for (int i = 0; i < 8; i++) {
    pinMode(segmentPins[i], OUTPUT);
  }
  for (int i = 0; i < 4; i++) {
    pinMode(digitPins[i], OUTPUT);
  }
}

void loop() {
  float temperature = dht.readTemperature(); // อุณหภูมิเป็นเซลเซียส
  float humidity = dht.readHumidity();       // ความชื้นสัมพัทธ์
  float heatIndex = computeHeatIndex(temperature, humidity); // คำนวณ Heat Index

  // แสดงค่าอุณหภูมิ
  displayValue('C', temperature);
  delay(2000);

  // แสดงค่าความชื้น
  displayValue('H', humidity);
  delay(2000);

  // แสดงค่า Heat Index
  displayValue('I', heatIndex);
  delay(2000);
}

void displayValue(char label, float value) {
  int displayNumber = (int)(value * 10); // เปลี่ยนค่าเป็นหน่วยทศนิยม

  // แสดงตัวอักษรที่ตำแหน่งหลักแรก
  for (int i = 0; i < 4; i++) {
    digitalWrite(digitPins[i], i == 0 ? HIGH : LOW); // ส่ง HIGH เพื่อเปิด
  }
  showLetter(label);

  // แสดงค่า 3 หลักที่เหลือ
  for (int i = 1; i < 4; i++) {
    int digitValue = displayNumber % 10;
    displayNumber /= 10;

    for (int j = 0; j < 4; j++) {
      digitalWrite(digitPins[j], j == i ? HIGH : LOW); // ส่ง HIGH เพื่อเปิด
    }

    // แสดงผลแต่ละเซกเมนต์
    for (int j = 0; j < 7; j++) {
      digitalWrite(segmentPins[j], digitPatterns[digitValue] & (1 << j) ? HIGH : LOW); // ส่ง HIGH เพื่อเปิด
    }

    // เพิ่มจุดทศนิยมที่ตำแหน่งที่สอง
    if (i == 2) {
      digitalWrite(segmentPins[7], HIGH); // เปิดจุดทศนิยม
    } else {
      digitalWrite(segmentPins[7], LOW); // ปิดจุดทศนิยม
    }

    delay(5); // หน่วงเวลาเล็กน้อยเพื่อให้แสดงผลหลักถัดไป
  }
}

void showLetter(char letter) {
  byte pattern;

  switch (letter) {
    case 'C':
      pattern = letterPatterns[0];
      break;
    case 'H':
      pattern = letterPatterns[1];
      break;
    case 'I':
      pattern = letterPatterns[2];
      break;
    default:
      pattern = 0;
      break;
  }

  for (int i = 0; i < 7; i++) {
    digitalWrite(segmentPins[i], pattern & (1 << i) ? HIGH : LOW); // ส่ง HIGH เพื่อเปิด
  }
}

float computeHeatIndex(float temperature, float humidity) {
  return -8.78469475556 + 1.61139411 * temperature + 2.33854883889 * humidity + -0.14611605 * temperature * humidity
         + -0.012308094 * pow(temperature, 2) + -0.0164248277778 * pow(humidity, 2)
         + 0.002211732 * pow(temperature, 2) * humidity + 0.00072546 * temperature * pow(humidity, 2)
         + -0.000003582 * pow(temperature, 2) * pow(humidity, 2);
}
