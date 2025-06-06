# ✅ TODO List API (Symfony 7 + Docker)

REST API для управления задачами (**TODO list**) с аутентификацией через **JWT**.

---

## 🔧 Стек технологий

- **Symfony 7+**
- **Docker + Docker Compose**
- **MariaDB 11**
- **Redis**
- **JWT** (LexikJWTAuthenticationBundle)
- **OpenAPI** (NelmioApiDocBundle)
- **Symfony Messenger**
- **Doctrine ORM**

---

## 🚀 Быстрый старт

### 1️⃣ Клонировать репозиторий
```bash
git clone https://github.com/arthur2050/test_a5_daccel.git
cd test_a5_daccel
```

### 2️⃣ Создать `.env.local`
```bash
cp .env .env.local
```
Убедись, что строка подключения к базе данных корректна:
```
DATABASE_URL="mysql://root:root@db:3306/todo?serverVersion=mariadb-11.7.2"
```

### 3️⃣ Запустить Docker
```bash
docker-compose up -d --build
```
Подождите пару секунд, пока контейнеры поднимутся.

### 4️⃣ Установить зависимости и выполнить миграции
```bash
docker exec -it todo_app bash
composer install
php bin/console doctrine:migrations:migrate
php bin/console lexik:jwt:generate-keypair
```

---

## 🧪 Примеры запросов

### 🔐 Регистрация
**POST** `/api/register`  
**Headers:**
```
Content-Type: application/json
```
**Body:**
```json
{
  "email": "user@example.com",
  "password": "password",
  "name": "John Doe"
}
```

---

### 🔐 Авторизация
**POST** `/api/login_check`  
**Headers:**
```
Content-Type: application/json
```
**Body:**
```json
{
  "username": "user@example.com",
  "password": "password"
}
```
**Ответ:**
```json
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGci..."
}
```
📌 Используй токен в заголовке:
```
Authorization: Bearer <token>
```

---

### ✅ Создание задачи
**POST** `/api/tasks`  
**Headers:**
```
Authorization: Bearer <token>
Content-Type: application/json
```
**Body:**
```json
{
  "title": "Сделать тестовое",
  "description": "Сделать задание для Daccel",
  "status": "new",
  "deadline": "2025-06-10"
}
```

---

### 📄 Получение списка задач
**GET** `/api/tasks?status=new&deadline=2025-06-10`  
**Headers:**
```
Authorization: Bearer <token>
```

---

### ✏️ Обновление задачи
**PUT** `/api/tasks/1`  
**Headers:**
```
Authorization: Bearer <token>
Content-Type: application/json
```
**Body:**
```json
{
  "title": "Обновлённое название",
  "status": "in_progress"
}
```

---

### ❌ Удаление задачи
**DELETE** `/api/tasks/1`  
**Headers:**
```
Authorization: Bearer <token>
```

---

## 📚 Swagger-документация
📎 Swagger доступен по адресу:  
[http://localhost:8080/api/doc](http://localhost:8080/api/doc)

---

## 📬 Postman коллекция
Файл: `Task API PHP.postman_collection.json`

---

## 🧠 Заметки по реализации

- Использован **LexikJWTAuthenticationBundle** для генерации и проверки токенов.
- Каждая задача привязана к пользователю.
- Реализована фильтрация задач по **статусу** и **дедлайну**.
- Для уведомлений можно использовать **Symfony Messenger**.

---

## 👤 Автор

**Артур**  
🔗 GitHub: [arthur2050](https://github.com/arthur2050)