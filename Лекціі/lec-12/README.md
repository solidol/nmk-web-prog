# Лекція 12. Основні засади використання архітектури MVC

[Перелік лекцій](../README.md)

## Вступ

Багато хто починає писати проєкти для вирішення однієї задачі, не підозрюючи, що згодом це може перерости в багатокористувацьку систему управління, наприклад, контентом або навіть виробництвом. І спочатку все виглядає чудово — все працює, поки не усвідомлюєш, що весь код складається з "костилів" і хардкоду. Код змішаний із версткою, запитами і складний для розуміння. Постає проблема: при додаванні нових функцій потрібно витратити багато часу на роботу з цим кодом, намагаючись зрозуміти, що ж там було написано, і проклинаючи себе в минулому.

Можливо, ви чули про шаблони проєктування та навіть переглядали такі книги, як:
- Е. Гамма, Р. Хелм, Р. Джонсон, Дж. Вліссідес «Прийоми об'єктно-орієнтованого проєктування. Шаблони проєктування»;
- М. Фаулер «Архітектура корпоративних програмних додатків».

Багато хто, не злякавшись великих керівництв і документацій, намагався освоїти сучасні фреймворки, але через складність розуміння (через велику кількість архітектурних концепцій, тісно пов’язаних між собою) відкладають їх вивчення на потім.

Ця стаття буде корисна новачкам. Я сподіваюся, що за кілька годин ви отримаєте уявлення про реалізацію патерна MVC, який лежить в основі всіх сучасних веб-фреймворків, а також отримаєте матеріал для подальших роздумів про те, «як варто робити». Наприкінці статті наведено добірку корисних посилань, які допоможуть краще розібратися, з чого складаються веб-фреймворки (крім MVC) та як вони працюють.

Досвідчені PHP-програмісти навряд чи знайдуть у цій статті щось нове, але їхні коментарі та зауваження до основного тексту були б дуже корисні! Оскільки без теорії практика неможлива, а без практики теорія марна, спочатку буде трохи теорії, а потім перейдемо до практики. Якщо ви вже знайомі з концепцією MVC, можете пропустити теоретичну частину й одразу перейти до практики.

![MVC](img/010.png)

Шаблон MVC описує простий спосіб побудови структури застосунку з метою відокремлення бізнес-логіки від користувацького інтерфейсу. Як результат, застосунок легше масштабувати, тестувати, підтримувати та розширювати.

## Архітектура MVC
У моделі MVC модель надає дані й правила бізнес-логіки, представлення відповідає за користувацький інтерфейс, а контролер забезпечує взаємодію між моделлю та представленням.

Типова послідовність роботи MVC-застосунку:
1. Коли користувач заходить на веб-ресурс, ініціалізаційний скрипт створює екземпляр застосунку і запускає його.
2. Відображається вигляд головної сторінки сайту.
3. Застосунок отримує запит від користувача й визначає запитувані контролер і дію. Для головної сторінки виконується дія за замовчуванням (index).
4. Застосунок створює екземпляр контролера й запускає метод дії, де, наприклад, викликаються моделі, які зчитують інформацію з бази даних.
5. Дія формує представлення з даними, отриманими з моделі, і показує результат користувачу.

### Модель
Модель містить бізнес-логіку застосунку, включає методи вибірки (наприклад, ORM), обробки даних (наприклад, правила валідації) і надання конкретних даних.

### Вид
Вид використовується для задання зовнішнього відображення даних, отриманих із контролера й моделі.

### Контролер
Контролер є зв’язуючою ланкою між моделями, видами й іншими компонентами. Він відповідає за обробку запитів користувача.

## 1.1. Front Controller та Page Controller
Є два основних підходи до організації контролерів: **Page Controller** і **Front Controller**.

**Page Controller** використовує кілька різних сценаріїв для кожної сторінки, тоді як **Front Controller** об'єднує всі запити через один файл.

## 1.2. Маршрутизація URL
Маршрутизація дозволяє налаштувати застосунок на прийом запитів із URL, які не відповідають реальним файлам, а також використовувати ЧПУ (читабельні посилання) для кращої оптимізації та зручності користувачів.

Тепер, маючи достатні теоретичні знання, ми можемо перейти до практичної частини.