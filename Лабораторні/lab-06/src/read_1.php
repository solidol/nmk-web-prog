<!DOCTYPE html>
<html>
<head>
    <title>Читання текстового файлу</title>
</head>
<body>
    <h1>Вміст текстового файлу</h1>
    <div>
        <?php
        $filename = 'example.txt';

        if (file_exists($filename)) {
            $file = fopen($filename, 'r'); // Відкриття файлу для читання

            if ($file) {
                while (($line = fgets($file)) !== false) {
                    echo htmlspecialchars($line, ENT_QUOTES, 'UTF-8') . '<br>'; // Виведення рядка з екрануванням HTML
                }

                fclose($file); // Закриття файлу
            } else {
                echo "Не вдалося відкрити файл для читання.";
            }
        } else {
            echo "Файл не існує.";
        }
        ?>
    </div>
</body>
</html>
