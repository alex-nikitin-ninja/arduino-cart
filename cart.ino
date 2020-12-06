// stty -F /dev/ttyUSB0 cs8 9600 ignbrk -brkint -icrnl -imaxbel -opost -onlcr -isig -icanon -iexten -echo -echoe -echok -echoctl -echoke noflsh -ixon -crtscts
// echo "$(cat packet.txt)" > /dev/ttyUSB0
// cat -n /dev/ttyUSB0

#include <Servo.h>

struct Motor {
    int in1;
    int in2;
    int enable;
};

struct Cart {
    int motor;
    int wheel;
};

Motor M1 = { 5, 6, 3 };
Motor M2 = { 7, 8, 11 };

int servoPin = 9;
Servo Servo;

Cart C = { 0, 83 };

char charRead;
String commandString;

void setup() {
    Servo.attach(servoPin);
    pinMode(M1.in1, OUTPUT);
    pinMode(M1.in2, OUTPUT);
    pinMode(M2.in1, OUTPUT);
    pinMode(M2.in2, OUTPUT);
    Serial.begin(9600);
}

void loop() {
    executeCart();
    if (Serial.available()) {
        charRead = Serial.read();
        commandString += charRead;
        if (charRead == ';') {
            commandString = parseCommand(commandString);
            executeCart();
            // Serial.print("Status: " + String(C.motor) + "; " + String(C.wheel) + "\n");
            Serial.print("OK");
        }
    }
}

String parseCommand(String command) {
    command.trim();
    String property = "";
    String value = "";
    bool propertyDone = false;
    bool valueDone = false;
    for (int i = 0; i < command.length(); i++) {
        if (command[i] != ':' && command[i] != ';') {
            if (!propertyDone) {
                property += command[i];
            } else {
                value += command[i];
            }
        }
        if (command[i] == ':') {
            propertyDone = true;
        }
        if (command[i] == ';') {
            valueDone = true;
        }
        if (propertyDone && valueDone) {
            if (property == "M") {
                C.motor = value.toInt();
            }
            if (property == "W") {
                C.wheel = value.toInt();
            }
        }
    }
    return "";
}

void executeCart() {
    moveCart(M1, C.motor);
    moveCart(M2, C.motor);
    Servo.write(C.wheel);
}

void moveCart(Motor m, int pwm) {
    if (pwm >= 0) {
        digitalWrite(m.in1, LOW);
        digitalWrite(m.in2, HIGH);
    } else {
        digitalWrite(m.in1, HIGH);
        digitalWrite(m.in2, LOW);
    }
    analogWrite(m.enable, abs(pwm));
}
