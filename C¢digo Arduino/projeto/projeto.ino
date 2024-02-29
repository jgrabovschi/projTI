#include <WiFi101.h>
#include <ArduinoHttpClient.h>
#include <DHT.h>
#include <NTPClient.h>
#include <WiFiUdp.h>  //Pré-instalada com o Arduino IDE
#include <TimeLib.h>

// Grupo
// Configurações de rede WiFi
const char SSID[] = "labs_lca";
const char PASS_WIFI[] = "robot1cA!ESTG";

// Servidor IoT
const char URL[] = "10.79.12.144";
const int PORTO = 80;

// Endpoints
const String API_PATH = "/test/api/api.php";

// Request headers
const String CONTENT_TYPE = "application/x-www-form-urlencoded";

// Clients
WiFiClient CLIENT_WIFI;
HttpClient HTTP_CLIENT = HttpClient(CLIENT_WIFI, URL, PORTO);

#define DHTPIN 0           // Pin Digital onde está ligado o sensor
#define DHTTYPE DHT11      // Tipo de sensor DHT
DHT dht(DHTPIN, DHTTYPE);  // Instanciar e declarar a class DHT

String valorChamas;

WiFiUDP clienteUDP;
//Servidor de NTP do IPLeiria: ntp.ipleiria.pt
//Fora do IPLeiria servidor: 0.pool.ntp.org
char NTP_SERVER[] = "0.pool.ntp.org";
NTPClient clienteNTP(clienteUDP, NTP_SERVER, 3600);

const int buzzer = 7;  //the pin of the active buzzer

//rgb
const int r = 11;
const int g = 10;
const int b = 9;


void setup() {
  // Configuração do baud rate
  Serial.begin(115200);

  // Espera até o serial estar ligado
  while (!Serial) {}

  // Conectar dispositivo à rede
  Serial.print("Connecting WiFi...");
  WiFi.begin(SSID, PASS_WIFI);
  while (WiFi.status() != WL_CONNECTED) {
    Serial.print(".");
    delay(500);
  }
  Serial.println("Connected!");

  // Mostra informação da rede a que está ligado
  Serial.println("------ WiFi Info ------");
  Serial.print("IP: ");
  Serial.println((IPAddress)WiFi.localIP());
  Serial.print("Subnet: ");
  Serial.println((IPAddress)WiFi.subnetMask());
  Serial.print("Default Gateway: ");
  Serial.println((IPAddress)WiFi.gatewayIP());
  Serial.print("Signal (RSSI): ");
  Serial.println(WiFi.RSSI());
  Serial.println("-----------------------");

  // Define o pino LED_BUILTIN como OUTPUT
  pinMode(LED_BUILTIN, OUTPUT);
  pinMode(buzzer, OUTPUT);  //initialize the buzzer pin as an output

  pinMode(r, OUTPUT);
  pinMode(g, OUTPUT);
  pinMode(b, OUTPUT);

  pinMode(LED_BUILTIN, OUTPUT);
  dht.begin();
}

void loop() {
    
  Serial.println(dht.readTemperature());
  Serial.println(dht.readHumidity());

  String enviaNome = "temperatura";
  String enviaValor = (String)dht.readTemperature();
  String enviaDivisao = "quarto";

  char datahora[20];
  update_time(datahora);
  Serial.print("Data Atual: ");
  Serial.println(datahora);

  post2API(enviaNome, enviaValor, datahora, enviaDivisao);

  enviaNome = "humidade";
  enviaValor = (String)dht.readHumidity();

  datahora[20];
  update_time(datahora);
  Serial.print("Data Atual: ");
  Serial.println(datahora);

  post2API(enviaNome, enviaValor, datahora, enviaDivisao);

  update_time(datahora);
  Serial.println(getChamas());
  if (getChamas() == "Nao Presentes") {
    Serial.println("desligado");
    digitalWrite(buzzer, LOW);
    post2API("alarme", "Desligado", datahora, "sala");
  } else {
    Serial.println("ligado");
    digitalWrite(buzzer, HIGH);
    post2API("alarme", "Ligado", datahora, "sala");
  }

  update_time(datahora);
  Serial.println(getPhoto());
  if (getPhoto() == "presente") {
    Serial.println("desligado");
    analogWrite(r,0);
    analogWrite(g,0);
    analogWrite(b,0);
    post2API("candeeiro", "Deligado", datahora, "quarto");
  } else {
    Serial.println("ligado");
    analogWrite(r,255);
    analogWrite(g,255);
    analogWrite(b,255);
    post2API("candeeiro", "Ligado", datahora, "quarto");
  }

  update_time(datahora);
  if (getLuz() == "Ligada") {
    digitalWrite(LED_BUILTIN, HIGH);
  } else {
    digitalWrite(LED_BUILTIN, LOW);}

  // Delay
  Serial.println("Delay 5 seconds");
  delay(5000);
}

void post2API(String nome, String valor, String hora, String divisao) {
  //String URLPath = "/test/api/api.php"; //altere o grupo
  //String contentType = "application/x-www-form-urlencoded";

  String body = "nome=" + nome + "&valor=" + valor + "&hora=" + hora + "&divisao=" + divisao;
  Serial.println(body);

  HTTP_CLIENT.post(API_PATH, CONTENT_TYPE, body);

  while (HTTP_CLIENT.connected()) {
    if (HTTP_CLIENT.available()) {
      int responseStatusCode = HTTP_CLIENT.responseStatusCode();
      String responseBody = HTTP_CLIENT.responseBody();
      Serial.println("Status Code: " + String(responseStatusCode) + "; Resposta: " + responseBody);
    }
  }
}

void update_time(char *datahora) {
  clienteNTP.update();
  unsigned long epochTime = clienteNTP.getEpochTime();
  sprintf(datahora, "%02d-%02d-%02d %02d:%02d:%02d", year(epochTime), month(epochTime), day(epochTime), hour(epochTime), minute(epochTime), second(epochTime));
}

String getChamas() {

  HTTP_CLIENT.get(API_PATH + "?nome=chamas&divisao=sala");
  String responseBody;
  while (HTTP_CLIENT.connected()) {
    if (HTTP_CLIENT.available()) {
      int responseStatusCode = HTTP_CLIENT.responseStatusCode();
      responseBody = HTTP_CLIENT.responseBody();
      Serial.println("Status Code: " + String(responseStatusCode) + "; Resposta(chamas): " + responseBody);
    }
  }

  return responseBody;
}

String getPhoto() {

  HTTP_CLIENT.get(API_PATH + "?nome=luz presente&divisao=quarto");
  String responseBody;
  while (HTTP_CLIENT.connected()) {
    if (HTTP_CLIENT.available()) {
      int responseStatusCode = HTTP_CLIENT.responseStatusCode();
      responseBody = HTTP_CLIENT.responseBody();
      Serial.println("Status Code: " + String(responseStatusCode) + "; Resposta(presencaLuz): " + responseBody);
    }
  }

  return responseBody;
}

String getLuz() {

  HTTP_CLIENT.get(API_PATH + "?nome=luz&divisao=quarto");
  String responseBody;
  while (HTTP_CLIENT.connected()) {
    if (HTTP_CLIENT.available()) {
      int responseStatusCode = HTTP_CLIENT.responseStatusCode();
      responseBody = HTTP_CLIENT.responseBody();
      Serial.println("Status Code: " + String(responseStatusCode) + "; Resposta(presencaLuz): " + responseBody);
    }
  }

  return responseBody;
}