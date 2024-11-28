## Детали реализации
 - Используемый фреймворк: Symfony
 - База данных: PostgresQL
 - Инструменты:
   - ✔ Docker
   - ✔ phpcs
   - ✔ Тесты (для /api/clients/*)

## Шаги для запуска:
 1. Склонируйте репозиторий:
```git pull git@github.com:kekaswork/af_test_task.git```
 2. Перейдите в папку проекта: ```cd af_test_task```
 3. Установите зависимости: ```composer install```
 4. Запустите локальный сервер Symfony: ```symfony server:start```
 5. Поднимите инфраструктуру с помощью Docker: ```docker compose up -d```

## Полезные команды:
 - Запуск тестов: ```php bin/phpunit```
 - Проверка кода phpcs: ```composer phpcs-check```
 - Автоматическое исправление кода phpcs: ```composer phpcs-fix```

## Описание задания
В данном тестовом задании мы ожидаем, что вы продемонстрируете умение применять принципы DDD (Domain-Driven Design) и следовать принципам Clean Architecture при проектировании кода.
Имейте в виду, что мы не требуем полностью рабочего кода, но ваш код должен быть функциональным на базовом уровне.
Это позволит нам оценить ваше понимание принципов программирования, проектирования и качество написанного кода.
Мы предполагаем, что выполнение тестового задания займет до 4 часов.

### Детали задачи
Вам нужно разработать код для процесса выдачи кредита.
Реализуйте функциональность создания нового клиента, проверки возможности выдачи кредита, принятия решения о выдаче или отказе, а также уведомления клиента о результате.

### Условия выдачи кредита
Кредитный рейтинг клиента должен быть выше 500.
Ежемесячный доход клиента должен быть не менее $1000.
Возраст клиента должен быть от 18 до 60 лет.
Кредиты выдаются только в штатах CA, NY, NV.
Клиентам из штата NY отказ производится случайным образом.
Клиентам из штата Калифорния процентная ставка увеличивается на 11.49%.

### Сущности
#### Клиент
- Фамилия
- Имя
- Возраст
- SSN (социальный страховой номер)
- Адрес США (Адрес, Город, Штат, ZIP)
- Кредитный рейтинг FICO (число от 300 до 850)
- Email
- Номер телефона

#### Продукт (Кредит)
- Название продукта
- Срок кредита
- Процентная ставка
- Сумма

### Сценарии
- Создание нового клиента.
- Изменение информации о существующем клиенте.
- Предварительная проверка возможности выдачи кредита.
- Выдача кредита:
- Принятие решения о выдаче или отказе.
- Уведомление клиента о результате через Email или SMS.

### Требования и ограничения
- Код должен быть размещен в Git-репозитории с предоставлением ссылки.
- Напишите код с использованием PHP версии 8+ и соблюдением стандартов PSR.
- Разрешено использование фреймворков Yii2 или Symfony, а также написание кода без фреймворков.
- Приложение должно работать с любой базой данных или без использования базы данных. Допустимо возвращать данные статически, без выполнения операций записи, если это поможет сократить время реализации.
- Интерфейс взаимодействия с приложением может быть любым: CLI, REST API, веб-интерфейс (HTML).
- Наличие тестов не обязательно, но их наличие будет преимуществом.
- Применение инструментов статического анализа и использование Docker также будут считаться преимуществом.
