✅ TODO List API (Symfony 7 + Docker)
REST API для управления задачами (TODO list) с аутентификацией через JWT.

🔧 Стек технологий
Symfony 7+

Docker + Docker Compose

MariaDB 11

Redis

JWT (LexikJWTAuthenticationBundle)

OpenAPI (NelmioApiDocBundle)

Symfony Messenger

Doctrine ORM

🚀 Быстрый старт
1. Клонируй репозиторий
   git clone https://github.com/arthur2050/test_a5_daccel.git
   cd test_a5_daccel
2. Создай .env.local
   cp .env .env.local
   Убедись, что в .env.local указана корректная строка подключения к БД:
   DATABASE_URL="mysql://root:root@db:3306/todo?serverVersion=mariadb-11.7.2"
3. Запусти Docker
   docker-compose up -d --build
   Подождите пару секунд, пока контейнеры поднимутся.

4. Установи зависимости и сделай миграции
   docker exec -it todo_app bash
   composer install
   php bin/console doctrine:migrations:migrate
   php bin/console lexik:jwt:generate-keypair

🧪 Примеры запросов

   🔐 Регистрация
   POST /api/register
   Content-Type: application/json

{
"email": "user@example.com",
"password": "password",
"name": "John Doe"
}

🔐 Авторизация

POST /api/login_check
Content-Type: application/json

{
"username": "user@example.com",
"password": "password"
}
Ответ:

json
{
"token": "eyJ0eXAiOiJKV1QiLCJhbGci..."
}
Используй этот токен в заголовке Authorization: Bearer <token> для всех защищённых маршрутов.

✅ Создание задачи

POST /api/tasks
Authorization: Bearer <token>
Content-Type: application/json

{
"title": "Сделать тестовое",
"description": "Сделать задание для Daccel",
"status": "new",
"deadline": "2025-06-10"
}
📄 Получение списка задач

GET /api/tasks?status=new&deadline=2025-06-10
Authorization: Bearer <token>

✏️ Обновление задачи

PUT /api/tasks/1
Authorization: Bearer <token>
Content-Type: application/json

{
"title": "Обновлённое название",
"status": "in_progress"
}

❌ Удаление задачи

DELETE /api/tasks/1
Authorization: Bearer <token>

📚 Swagger-документация
Swagger доступен по адресу:http://localhost:8080/api/doc

📬 Postman коллекция

Task API PHP.postman_collection.json

🧠 Заметки по реализации
Использован LexikJWTAuthenticationBundle для генерации и проверки токена.

Каждая задача связана с пользователем.

Реализована фильтрация по статусу и дедлайну.

Для уведомлений можно использовать Symfony Messenger.


👤 Автор
Артур – GitHub: arthur2050