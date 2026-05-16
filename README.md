🚀 Лабораторная работа №9 – CI/CD для PHP-приложения с использованием GitHub Actions и Docker

👨‍💻 Автор
ФИО: Котляр Михаил Александрович
Группа: ПМ-ИП2
Вариант: «Оформление подписки на журнал»

📌 Описание задания

Настроить CI/CD pipeline для PHP-приложения с использованием GitHub Actions. Автоматически запускать тесты PHPUnit при каждом push в репозиторий. Обеспечить запуск Docker-контейнеров в среде CI и проверку работоспособности приложения.

⚙️ Как запустить проект

Клонировать репозиторий: git clone https://github.com/Mishajj39/web
cd web/lab9
docker-compose up -d --build
docker-compose exec php composer install
docker-compose exec php vendor/bin/phpunit

📂 Структура проекта

lab9/
├── code/                          # Исходный код приложения
│   ├── db.php
│   ├── Subscription.php
│   ├── index.php
│   ├── form.html
│   └── process.php
├── tests/                         # Тесты
│   ├── SubscriptionTest.php
│   ├── SubscriptionMockTest.php
│   ├── HttpTest.php
│   └── IntegrationTest.php
├── docker-compose.yml             # Docker Compose конфигурация
├── Dockerfile                     # Docker образ с PHP и Composer
├── composer.json                  # Зависимости PHP
├── .env.test                      # Переменные окружения для тестов
├── .github/
│   └── workflows/
│       └── ci.yml                 # CI/CD pipeline
└── README.md

✅ Результат выполнения
В ходе лабораторной работы:
✅ Настроен CI/CD pipeline для PHP-приложения

✅ Добавлен автоматический запуск тестов PHPUnit

✅ Настроен Docker для CI среды

✅ Проверена работа pipeline при успешных тестах

✅ Продемонстрировано падение при ошибке в тестах

✅ Выполнено штрафное задание (5 дополнительных пунктов)

📝 Вывод

В ходе выполнения лабораторной работы освоены принципы настройки CI/CD для PHP-приложений с использованием GitHub Actions. Реализован автоматический запуск тестов PHPUnit при каждом push в репозиторий. Настроено Docker-окружение в CI, добавлены проверки контейнеров, логирование ошибок и разделение pipeline на несколько jobs. Полученные знания применимы для автоматизации тестирования и развертывания PHP-приложений.
