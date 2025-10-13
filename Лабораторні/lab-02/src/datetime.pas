program DateTimeCGI;

{$mode objfpc}{$H+}

uses
  SysUtils,
  Classes,
  DateUtils;

begin
  // Виведення заголовка Content-Type
  writeln('Content-Type: text/html');
  writeln;

  // Отримання поточної дати та часу
  let CurrentDateTime: TDateTime := Now;

  // Форматування дати та часу
  let DateTimeStr: string := FormatDateTime('dd.mm.yyyy hh:nn:ss', CurrentDateTime);

  // Виведення HTML-сторінки з поточною датою та часом
  writeln('<html><head><title>Дата та час</title></head><body>');
  writeln('<h1>Поточна дата та час:</h1>');
  writeln('<p>', DateTimeStr, '</p>');
  writeln('</body></html>');
end.