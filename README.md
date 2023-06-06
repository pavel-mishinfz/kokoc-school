<h1 align="center">
  Система управления задачами «Дела в Порядке»
</h1>

![main](https://github.com/pavel-mishinfz/kokoc-school/blob/assets/img/guest.png)

<h2 align="center">
  <a href="http://pavelms.beget.tech/">Посмотреть демо</a>
</h2>

## Описание
«Дела в порядке» — это веб-приложение для удобного ведения списка дел. Сервис помогает пользователям не забывать о предстоящих важных событиях и задачах. 

После создания аккаунта, пользователь может начать вносить свои дела, деля их по проектам и указывая сроки.
Основные сценарии использования сайта:
+ Создание проектов;
+ Добавление новых задач с привязкой к проекту и дате;
+ Просмотр дел на ближайшие дни;
+ Получение уведомлений о предстоящих задачах.

## О проекте
Для разработки сайта была предоставлена уже готовая вёрстка, необходимо было реализовать возможности по добавлению, просмотру задач и проектов.

Разработка бэкенда велась на языке программирования PHP 7 и выше, база данных — MySQL 5.7 и выше.
### 0. Гостевая страница
Гостевая страница будет показана всем анонимным пользователям, которые впервые попали на сайт. Также на эту страницу происходит перенаправление после выхода из учетной записи. <br>
Эта страница содержит лишь параграф текста с описанием сервиса и кнопку «Зарегистрироваться», которая открывает страницу регистрации нового аккаунта.

<p align="center">
  <img src="https://github.com/pavel-mishinfz/kokoc-school/blob/assets/img/guest.png" alt="guest">
</p>

### 1. Главная страница
Страница делится на две колонки в соотношении 1/3. <br>
Левая часть состоит из блока со списком проектов. Под списком проектов находится кнопка «Добавить проект», открывающая страницу добавления проекта.<br>
Правая часть относится к просмотру и управлению задачами.<br>
Состоит последовательно из следующих элементов:
  - Поиск задачи — поисковая строка, выполняющая операцию поиска по имени задачи.
  - Блок фильтров — блок, состоящий из ссылок для быстрой фильтрации задач:
    - Все задачи — показывает все задачи в выбранном проекте;
    - Повестка дня — показывает все задачи на сегодня;
    - Завтра — показывает все задачи на завтра;
    - Просроченные — показывает все задачи, которые не были выполнены и у которых истёк срок.
  - Блок задач — основной блок, который содержит список всех невыполненных задач в выбранном представлении. <br> 
    Каждая задача в блоке состоит из таких элементов (слева направо):
    - Чекбокс — показывает статус задачи: выполнена или нет;
    - Название задачи — клик по названию задачи меняет её статус;
    - Приложенный файл (если есть) — иконка-ссылка, ведущая на скачивание файла;
    - Срок выполнения (если он указан).
  - Над блоком задач должна быть ссылка «Показать выполненные», клик по которой обновляет страницу и дополняет список в выбранном представлении выполненными задачами.
  - Кнопка добавления задачи — кнопка, которая открывает страницу для добавления новой задачи.

<p align="center">
  <img src="https://github.com/pavel-mishinfz/kokoc-school/blob/assets/img/main.png" alt="main">
</p>

### 2. Регистрация аккаунта
Чтобы пользователь имел возможность пользоваться сайтом и управлять своими задачами, ему необходимо пройти процедуру регистрации на этой странице. <br>
Форма состоит из трёх обязательных полей и кнопки «Зарегистрироваться». <br>
После заполнения формы, пользователь нажимает кнопку «Зарегистрироваться» для отправки данных формы на сервер. <br>
Если по итогам выполнения процесса регистрации возникли ошибки заполнения формы, то эти ошибки должны быть показаны красным текстом под необходимыми полями, а над самой кнопкой отправки формы появится сообщение «Пожалуйста, исправьте ошибки в форме».

<p align="center">
  <img src="https://github.com/pavel-mishinfz/kokoc-school/blob/assets/img/registration.png" alt="registration">
</p>

### 3. Авторизация на сайте
Все поля обязательны к заполнению. <br>
Если по итогам выполнения процесса авторизации возникли ошибки заполнения формы, то эти ошибки должны быть показаны красным текстом под необходимыми полями, а под самой формой появится сообщение «Пожалуйста, исправьте ошибки в форме». <br>
Если пользователь ввёл неверные данные аккаунта (несуществующий пользователь или неверный пароль), то в сообщении под формой должен быть текст «Вы ввели неверный email/пароль».

<p align="center">
  <img src="https://github.com/pavel-mishinfz/kokoc-school/blob/assets/img/auth.png" alt="auth">
</p>

### 4. Добавление проекта
Страница с формой для добавления нового проекта. Форма содержит только одно (обязательное) поле для указания имени нового проекта и кнопку отправки.

<p align="center">
  <img src="https://github.com/pavel-mishinfz/kokoc-school/blob/assets/img/add_project.png" alt="project">
</p>

### 5. Добавление задачи
Страница с формой для добавления новой задачи. Содержит форму из четырёх полей и кнопку «Добавить». <br>
После заполнения формы, пользователь нажимает кнопку «Добавить» для отправки данных формы на сервер. <br>
Если по итогам выполнения процесса отправки возникли ошибки заполнения формы, то эти ошибки должны быть показаны красным текстом под необходимыми полями, а под самой формой появится сообщение «Пожалуйста, исправьте ошибки в форме».

<p align="center">
  <img src="https://github.com/pavel-mishinfz/kokoc-school/blob/assets/img/add_task.png" alt="task">
</p>

### 6. Роли пользователей
Сайт могут использовать только зарегистрированные пользователи. <br>
Анонимный пользователь всегда видит только страницу входа на сайт.
### Основные сущности
Список всех сущностей
  - Проект;
  - Задача;
  - Пользователь.
#### 1. Проект
Состоит только из названия. Каждая задача может быть привязана к одному из проектов. Проект имеет связь с пользователем, который его создал. <br>
Связи:
  - автор: пользователь, создавший проект;
#### 2. Задача
Центральная сущность всего сайта. <br>
##### Поля:
  - дата создания: дата и время, когда задача была создана;
  - статус: число (1 или 0), означающее, была ли выполнена задача. По умолчанию ноль;
  - название: задаётся пользователем;
  - файл: ссылка на файл, загруженный пользователем;
  - срок: дата, до которой задача должна быть выполнена.
##### Связи:
  - автор: пользователь, создавший задачу;
  - проект: проект, которому принадлежит задача.
#### 3. Пользователь
Представляет зарегистрированного пользователя. <br>
##### Поля:
  - дата регистрации: дата и время, когда этот пользователь завел аккаунт;
  - email;
  - имя;
  - пароль: хэшированный пароль пользователя.
## Как запустить?
+ В файле templates/layout.php прописать путь до файла index.php <br>
```<?php $classname = (empty($_SESSION['user']) && $_SERVER['PHP_SELF'] == ...```
+ В файле templates/main.php прописать путь до директории uploads <br>
```<td class="task__file"><a href= ...```
+ [Скачать](https://github.com/pavel-mishinfz/kokoc-school/blob/assets/todo_list.sql) дамп БД
