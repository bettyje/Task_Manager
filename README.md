# 🗂️ Task Manager App

A simple yet powerful task management web application built with **CodeIgniter 4**.  
It allows users to create, track, update, and delete tasks through a clean REST API and dynamic frontend.

---

## ✨ Features

- ➕ Create new tasks  
- 📋 View all tasks in a structured table  
- ✔ Mark tasks as complete or incomplete  
- ✏ Edit existing tasks  
- 🗑 Delete tasks permanently  

---

## ⚙️ Requirements

Before running the project, ensure you have the following installed:

- PHP 8 or higher  
- Composer  
- MySQL  
- XAMPP (or any local development server)  

---

## 🚀 Installation Guide

### 1. Clone the Project

```bash
git clone git@github.com:bettyje/Task_Manager.git
```

Or simply download and extract it into your htdocs folder.

### 2. Navigate to Project Folder
```bash
cd Task_Manager
```
### 3. Install Dependencies
```bash
composer install
```
### 4. Configure Environment
Duplicate .env.example and rename it to .env
```bash
Update your database credentials:
database.default.hostname = localhost
database.default.database = your_db_name
database.default.username = root
database.default.password =
``` 

### 5. Set Up the Database

Run the following SQL in phpMyAdmin:

```bash
CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    completed TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```
### 6. Run the Application

If using XAMPP:

- Place the project inside htdocs
- Start Apache and MySQL
- Open your browser and visit:

```bash
http://localhost/Task_Manager/public
```

## API Endpoints

| Method | Endpoint         | Description         |
|--------|------------------|---------------------|
| GET    | /api/tasks       | Retrieve all tasks  |
| POST   | /api/tasks       | Create a new task   |
| PATCH  | /api/tasks/{id}  | Update a task       |
| DELETE | /api/tasks/{id}  | Delete a task       |

N.B
- Frontend uses Fetch API
- No page reloads required
- Built as a simple RESTful architecture
- Lightweight and beginner-friendly

👤 Author

Betty Jelagat
