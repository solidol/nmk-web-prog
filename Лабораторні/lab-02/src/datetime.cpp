#include <iostream>
#include <ctime>

int main() {
    // Виведення заголовка Content-Type
    std::cout << "Content-Type: text/html\r\n\r\n";

    // Отримання поточного часу
    time_t currentTime;
    struct tm* timeInfo;
    time(&currentTime);
    timeInfo = localtime(&currentTime);

    // Виведення HTML-сторінки з поточною датою та часом
    std::cout << "<html><head><title>Дата та час</title></head><body>";
    std::cout << "<h1>Поточна дата та час:</h1>";
    std::cout << "<p>" << asctime(timeInfo) << "</p>";
    std::cout << "</body></html>";

    return 0;
}
