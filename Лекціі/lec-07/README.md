# Лекція 7. Асинхронна передача даних

[Перелік лекцій](../README.md)

Що таке AJAX?
AJAX розшифровується як асинхронний JavaScript і XML, і дозволяє асинхронно отримувати контент із сервера без оновлення сторінки. Це дозволяє оновлювати вміст веб-сторінки без перезавантаження.

Розгляньмо приклад, щоб зрозуміти, як можна використовувати AJAX у повсякденній розробці додатків. Припустимо, ви хочете створити сторінку, яка відображає інформацію профілю користувача з різними розділами, такими як особиста інформація, соціальна інформація, сповіщення, повідомлення тощо.

Зазвичай для кожного розділу створюються окремі веб-сторінки. Наприклад, користувачі клікають на посилання з соціальною інформацією, щоб завантажити нову сторінку. Це сповільнює навігацію, оскільки кожен перехід між розділами потребує оновлення сторінки.

Інший підхід полягає у використанні AJAX, який дозволяє завантажувати інформацію без оновлення сторінки. Ви можете зробити вкладки для кожного розділу, і при кліку на вкладку відповідний вміст завантажується із сервера без перезавантаження браузера. Це значно покращує взаємодію користувача.

Загальний процес роботи AJAX виглядає так:
1. Спочатку користувач відкриває веб-сторінку звичайним способом.
2. Потім користувач натискає на елемент DOM — зазвичай кнопку або посилання — що ініціює асинхронний запит до сервера.
3. У відповідь сервер може надіслати дані у форматі XML, JSON або HTML.
4. Дані обробляються за допомогою JavaScript.
5. Нарешті, оброблені дані оновлюються на веб-сторінці без перезавантаження.

Тепер розгляньмо, як реалізувати AJAX за допомогою звичайного JavaScript.

Як працює AJAX на ванільному JavaScript
Для початку розглянемо простий код JavaScript, який виконує виклик AJAX:

```javascript
<script>
var objXMLHttpRequest = new XMLHttpRequest();
objXMLHttpRequest.onreadystatechange = function() {
  if(objXMLHttpRequest.readyState === 4) {
    if(objXMLHttpRequest.status === 200) {
          alert(objXMLHttpRequest.responseText);
    } else {
          alert('Код помилки: ' +  objXMLHttpRequest.status);
          alert('Повідомлення про помилку: ' + objXMLHttpRequest.statusText);
    }
  }
}
objXMLHttpRequest.open('GET', 'request_ajax_data.php');
objXMLHttpRequest.send();
</script>
```

Ось короткий огляд коду:
1. Ініціалізується об'єкт `XMLHttpRequest`, який відповідає за виконання AJAX-викликів.
2. Встановлюється функція-обробник для зміни стану через властивість `onreadystatechange`. Ця функція викликається кожного разу, коли стан змінюється.
3. Якщо `readyState` дорівнює 4 (запит завершений) і статус відповіді 200 (успіх), викликається відповідний блок коду.
4. Нарешті, запит відправляється через метод `send`.

Це основи роботи AJAX із використанням ванільного JavaScript. Тепер перейдемо до прикладу з jQuery.

Як працює AJAX із jQuery
jQuery значно спрощує виконання AJAX-викликів. Ось приклад:

```javascript
<script>
$.ajax(
  'request_ajax_data.php',
  {
      success: function(data) {
        alert('AJAX-запит успішно виконано!');
        alert('Дані з сервера: ' + data);
      },
      error: function() {
        alert('Виникла помилка під час виконання AJAX-запиту!');
      }
   }
);
</script>
```

У цьому коді метод `ajax` викликає сервер і у разі успіху викликає функцію обробки відповіді, а у разі помилки — відповідний обробник.

Приклад AJAX із PHP
Для демонстрації створимо простий додаток для входу в систему за допомогою AJAX та jQuery. Почнемо з файлу `index.php`, який містить форму входу:

```html
<!doctype html>
<html>
<head>
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
</head>
<body>
<form id="loginform" method="post">
    <div>
        Ім'я користувача:
        <input type="text" name="username" id="username" />
        Пароль:
        <input type="password" name="password" id="password" />    
        <input type="submit" name="loginBtn" id="loginBtn" value="Увійти" />
    </div>
</form>
<script type="text/javascript">
$(document).ready(function() {
    $('#loginform').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: 'login.php',
            data: $(this).serialize(),
            success: function(response)
            {
                var jsonData = JSON.parse(response);
                if (jsonData.success == "1")
                {
                    location.href = 'my_profile.php';
                }
                else
                {
                    alert('Невірні дані для входу!');
                }
           }
       });
     });
});
</script>
</body>
</html>
```

Файл `login.php` обробляє дані форми та повертає результат у форматі JSON:

```php
<?php
if (isset($_POST['username']) && $_POST['username'] && isset($_POST['password']) && $_POST['password']) {
    echo json_encode(array('success' => 1));
} else {
    echo json_encode(array('success' => 0));
}
?>
```

Таким чином, AJAX дозволяє створювати більш інтерактивні веб-додатки, не потребуючи оновлення всієї сторінки.