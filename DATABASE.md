# Структура базы данных EduPoint LMS

## Описание

База данных для онлайн-платформы курсов (LMS) с поддержкой ролей пользователей, курсов, уроков, тестов и сертификатов.

## Таблицы

### 1. users
Таблица пользователей с расширенной ролевой системой.

| Поле | Тип | Описание |
|------|-----|----------|
| id | BIGINT UNSIGNED | Первичный ключ |
| name | VARCHAR(255) | Имя пользователя |
| email | VARCHAR(255) | Email (уникальный) |
| password | VARCHAR(255) | Хешированный пароль |
| role | ENUM('student', 'teacher', 'admin') | Роль пользователя |
| email_verified_at | TIMESTAMP | Дата подтверждения email |
| remember_token | VARCHAR(100) | Токен для "запомнить меня" |
| created_at | TIMESTAMP | Дата создания |
| updated_at | TIMESTAMP | Дата обновления |

**Индексы:**
- PRIMARY KEY (id)
- UNIQUE (email)

---

### 2. courses
Таблица курсов.

| Поле | Тип | Описание |
|------|-----|----------|
| id | BIGINT UNSIGNED | Первичный ключ |
| title | VARCHAR(255) | Название курса |
| description | TEXT | Описание курса |
| thumbnail | TEXT | Путь к обложке |
| price | DECIMAL(8,2) | Цена курса (0 = бесплатно) |
| teacher_id | BIGINT UNSIGNED | ID преподавателя (внешний ключ к users) |
| status | ENUM('draft', 'published', 'archived') | Статус курса |
| created_at | TIMESTAMP | Дата создания |
| updated_at | TIMESTAMP | Дата обновления |

**Индексы:**
- PRIMARY KEY (id)
- FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE CASCADE
- INDEX (status)

---

### 3. lessons
Таблица уроков курса.

| Поле | Тип | Описание |
|------|-----|----------|
| id | BIGINT UNSIGNED | Первичный ключ |
| course_id | BIGINT UNSIGNED | ID курса (внешний ключ) |
| title | VARCHAR(255) | Название урока |
| content | TEXT | Текстовое содержание |
| video_url | VARCHAR(255) | URL видео (YouTube и т.д.) |
| file_path | VARCHAR(255) | Путь к файлам урока |
| order | INT | Порядок урока в курсе |
| is_free | BOOLEAN | Бесплатный просмотр |
| created_at | TIMESTAMP | Дата создания |
| updated_at | TIMESTAMP | Дата обновления |

**Индексы:**
- PRIMARY KEY (id)
- FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
- INDEX (course_id, order)

---

### 4. enrollments
Таблица записей на курсы (связь many-to-many между users и courses).

| Поле | Тип | Описание |
|------|-----|----------|
| id | BIGINT UNSIGNED | Первичный ключ |
| user_id | BIGINT UNSIGNED | ID студента |
| course_id | BIGINT UNSIGNED | ID курса |
| paid_amount | DECIMAL(8,2) | Оплаченная сумма |
| enrolled_at | TIMESTAMP | Дата записи |
| completed_at | TIMESTAMP | Дата завершения курса |

**Индексы:**
- PRIMARY KEY (id)
- FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
- FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
- UNIQUE (user_id, course_id)

---

### 5. lesson_progress
Прогресс прохождения уроков студентами.

| Поле | Тип | Описание |
|------|-----|----------|
| id | BIGINT UNSIGNED | Первичный ключ |
| user_id | BIGINT UNSIGNED | ID студента |
| lesson_id | BIGINT UNSIGNED | ID урока |
| completed | BOOLEAN | Завершен ли урок |
| completed_at | TIMESTAMP | Дата завершения |

**Индексы:**
- PRIMARY KEY (id)
- FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
- FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE
- UNIQUE (user_id, lesson_id)

---

### 6. tests
Таблица тестов для курсов.

| Поле | Тип | Описание |
|------|-----|----------|
| id | BIGINT UNSIGNED | Первичный ключ |
| course_id | BIGINT UNSIGNED | ID курса |
| title | VARCHAR(255) | Название теста |
| description | TEXT | Описание теста |
| pass_score | INT | Проходной балл (%) |
| created_at | TIMESTAMP | Дата создания |
| updated_at | TIMESTAMP | Дата обновления |

**Индексы:**
- PRIMARY KEY (id)
- FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE

---

### 7. test_questions
Вопросы для тестов.

| Поле | Тип | Описание |
|------|-----|----------|
| id | BIGINT UNSIGNED | Первичный ключ |
| test_id | BIGINT UNSIGNED | ID теста |
| question | TEXT | Текст вопроса |
| options | JSON | Варианты ответов (массив) |
| correct_answer | INT | Индекс правильного ответа (0-3) |
| order | INT | Порядок вопроса |
| created_at | TIMESTAMP | Дата создания |
| updated_at | TIMESTAMP | Дата обновления |

**Индексы:**
- PRIMARY KEY (id)
- FOREIGN KEY (test_id) REFERENCES tests(id) ON DELETE CASCADE

**Пример JSON для options:**
```json
["Вариант 1", "Вариант 2", "Вариант 3", "Вариант 4"]
```

---

### 8. test_results
Результаты прохождения тестов студентами.

| Поле | Тип | Описание |
|------|-----|----------|
| id | BIGINT UNSIGNED | Первичный ключ |
| user_id | BIGINT UNSIGNED | ID студента |
| test_id | BIGINT UNSIGNED | ID теста |
| score | INT | Количество правильных ответов |
| total_questions | INT | Общее количество вопросов |
| passed | BOOLEAN | Прошел ли тест |
| answers | JSON | Ответы пользователя |
| completed_at | TIMESTAMP | Дата прохождения |

**Индексы:**
- PRIMARY KEY (id)
- FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
- FOREIGN KEY (test_id) REFERENCES tests(id) ON DELETE CASCADE

---

### 9. certificates
Таблица сертификатов о прохождении курсов.

| Поле | Тип | Описание |
|------|-----|----------|
| id | BIGINT UNSIGNED | Первичный ключ |
| user_id | BIGINT UNSIGNED | ID студента |
| course_id | BIGINT UNSIGNED | ID курса |
| certificate_number | VARCHAR(255) | Уникальный номер сертификата |
| issued_at | TIMESTAMP | Дата выдачи |

**Индексы:**
- PRIMARY KEY (id)
- FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
- FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
- UNIQUE (user_id, course_id)
- UNIQUE (certificate_number)

---

## Связи между таблицами

### User → Courses (как преподаватель)
- **Тип:** One-to-Many
- **Связь:** users.id → courses.teacher_id

### User ↔ Courses (как студент)
- **Тип:** Many-to-Many
- **Через:** enrollments (user_id, course_id)

### Course → Lessons
- **Тип:** One-to-Many
- **Связь:** courses.id → lessons.course_id

### Course → Tests
- **Тип:** One-to-Many
- **Связь:** courses.id → tests.course_id

### Test → TestQuestions
- **Тип:** One-to-Many
- **Связь:** tests.id → test_questions.test_id

### User → LessonProgress
- **Тип:** One-to-Many
- **Связь:** users.id → lesson_progress.user_id

### User → TestResults
- **Тип:** One-to-Many
- **Связь:** users.id → test_results.user_id

### User ↔ Certificates
- **Тип:** One-to-Many
- **Связь:** users.id → certificates.user_id

---

## ER-диаграмма (текстовое представление)

```
┌─────────┐
│  USERS  │
└────┬────┘
     │
     ├──(teacher)──→ ┌─────────┐
     │               │ COURSES │
     │               └────┬────┘
     │                    │
     │                    ├──→ ┌─────────┐
     │                    │    │ LESSONS │
     │                    │    └────┬────┘
     │                    │         │
     │                    │         ↓
     │                    │    ┌──────────────────┐
     │                    │    │ LESSON_PROGRESS  │←─┐
     │                    │    └──────────────────┘  │
     │                    │                          │
     │                    ├──→ ┌───────┐            │
     │                    │    │ TESTS │            │
     │                    │    └───┬───┘            │
     │                    │        │                │
     │                    │        ├──→ ┌────────────────┐
     │                    │        │    │ TEST_QUESTIONS │
     │                    │        │    └────────────────┘
     │                    │        │
     │                    │        ↓
     │                    │    ┌──────────────┐
     │                    │    │ TEST_RESULTS │←──┐
     │                    │    └──────────────┘   │
     │                    │                       │
     ├──(student)─────────┼──→ ┌─────────────┐  │
     │                    │    │ ENROLLMENTS │  │
     │                    │    └─────────────┘  │
     │                    │                     │
     │                    └──→ ┌──────────────┐ │
     │                         │ CERTIFICATES │ │
     │                         └──────────────┘ │
     │                                          │
     └──────────────────────────────────────────┘
```

---

## Примеры SQL-запросов

### 1. Получить все курсы с количеством студентов
```sql
SELECT c.id, c.title, c.price, u.name as teacher_name, 
       COUNT(e.id) as students_count
FROM courses c
LEFT JOIN users u ON c.teacher_id = u.id
LEFT JOIN enrollments e ON c.id = e.course_id
WHERE c.status = 'published'
GROUP BY c.id
ORDER BY students_count DESC;
```

### 2. Прогресс студента по курсу
```sql
SELECT 
    c.title as course_name,
    COUNT(l.id) as total_lessons,
    COUNT(lp.id) as completed_lessons,
    ROUND((COUNT(lp.id) / COUNT(l.id)) * 100, 2) as progress_percent
FROM courses c
JOIN lessons l ON c.id = l.course_id
LEFT JOIN lesson_progress lp ON l.id = lp.lesson_id AND lp.user_id = ? AND lp.completed = 1
WHERE c.id = ?
GROUP BY c.id;
```

### 3. Топ-10 популярных курсов
```sql
SELECT c.title, c.price, u.name as teacher,
       COUNT(e.id) as enrollments
FROM courses c
JOIN users u ON c.teacher_id = u.id
LEFT JOIN enrollments e ON c.id = e.course_id
WHERE c.status = 'published'
GROUP BY c.id
ORDER BY enrollments DESC
LIMIT 10;
```

### 4. Студенты, завершившие курс
```sql
SELECT u.name, u.email, e.completed_at
FROM enrollments e
JOIN users u ON e.user_id = u.id
WHERE e.course_id = ? AND e.completed_at IS NOT NULL
ORDER BY e.completed_at DESC;
```

### 5. Результаты теста студента
```sql
SELECT t.title, tr.score, tr.total_questions, 
       ROUND((tr.score / tr.total_questions) * 100, 2) as percentage,
       tr.passed, tr.completed_at
FROM test_results tr
JOIN tests t ON tr.test_id = t.id
WHERE tr.user_id = ?
ORDER BY tr.completed_at DESC;
```

---

## Индексирование для производительности

Рекомендуемые индексы для оптимизации запросов:

```sql
-- Для быстрого поиска курсов по статусу
CREATE INDEX idx_courses_status ON courses(status);

-- Для запросов по преподавателям
CREATE INDEX idx_courses_teacher ON courses(teacher_id);

-- Для сортировки уроков
CREATE INDEX idx_lessons_order ON lessons(course_id, order);

-- Для поиска незавершенных курсов
CREATE INDEX idx_enrollments_completed ON enrollments(user_id, completed_at);
```

---

## Миграции

Все таблицы создаются через Laravel миграции в папке `database/migrations/`.

Для запуска миграций:
```bash
php artisan migrate
```

Для заполнения тестовыми данными:
```bash
php artisan db:seed
```

---

**Дата создания:** 2024
**Версия:** 1.0
**СУБД:** MySQL 5.7+
