<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Test;
use App\Models\TestQuestion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Создаем пользователей с разными ролями
        $admin = User::create([
            'name' => 'Администратор',
            'email' => 'admin@edupoint.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $teacher = User::create([
            'name' => 'Иван Петров',
            'email' => 'teacher@edupoint.com',
            'password' => bcrypt('password'),
            'role' => 'teacher',
        ]);

        $student = User::create([
            'name' => 'Мария Сидорова',
            'email' => 'student@edupoint.com',
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);

        // Создаем несколько курсов
        $course1 = Course::create([
            'title' => 'Основы программирования на PHP',
            'description' => 'Изучите основы PHP с нуля. Курс подходит для начинающих разработчиков.',
            'price' => 0,
            'teacher_id' => $teacher->id,
            'status' => 'published',
        ]);

        $course2 = Course::create([
            'title' => 'Laravel для начинающих',
            'description' => 'Научитесь создавать веб-приложения на Laravel - самом популярном PHP фреймворке.',
            'price' => 2999,
            'teacher_id' => $teacher->id,
            'status' => 'published',
        ]);

        $course3 = Course::create([
            'title' => 'Frontend разработка: HTML, CSS, JavaScript',
            'description' => 'Полный курс по frontend разработке от основ до продвинутых техник.',
            'price' => 1999,
            'teacher_id' => $teacher->id,
            'status' => 'published',
        ]);

        // Создаем уроки для курса 1
        $lessons1 = [
            ['title' => 'Введение в PHP', 'content' => 'В этом уроке мы познакомимся с языком PHP и его возможностями.', 'order' => 1, 'is_free' => true],
            ['title' => 'Переменные и типы данных', 'content' => 'Изучаем переменные, типы данных и операции с ними.', 'order' => 2, 'is_free' => true],
            ['title' => 'Условные операторы', 'content' => 'Учимся использовать if, else, switch для управления потоком выполнения.', 'order' => 3, 'is_free' => false],
            ['title' => 'Циклы', 'content' => 'Циклы for, while, foreach для повторяющихся операций.', 'order' => 4, 'is_free' => false],
            ['title' => 'Функции', 'content' => 'Создание и использование функций в PHP.', 'order' => 5, 'is_free' => false],
        ];

        foreach ($lessons1 as $lessonData) {
            Lesson::create(array_merge(['course_id' => $course1->id], $lessonData));
        }

        // Создаем уроки для курса 2
        $lessons2 = [
            ['title' => 'Введение в Laravel', 'content' => 'Что такое Laravel и почему он популярен.', 'order' => 1, 'is_free' => true],
            ['title' => 'Установка и настройка', 'content' => 'Как установить Laravel и настроить окружение.', 'order' => 2, 'is_free' => false],
            ['title' => 'Роутинг', 'content' => 'Основы маршрутизации в Laravel.', 'order' => 3, 'is_free' => false],
            ['title' => 'Контроллеры', 'content' => 'Создание и использование контроллеров.', 'order' => 4, 'is_free' => false],
            ['title' => 'Eloquent ORM', 'content' => 'Работа с базой данных через Eloquent.', 'order' => 5, 'is_free' => false],
        ];

        foreach ($lessons2 as $lessonData) {
            Lesson::create(array_merge(['course_id' => $course2->id], $lessonData));
        }

        // Создаем тест для курса 1
        $test1 = Test::create([
            'course_id' => $course1->id,
            'title' => 'Проверка знаний PHP',
            'description' => 'Итоговый тест по основам PHP',
            'pass_score' => 70,
        ]);

        // Добавляем вопросы к тесту
        $questions = [
            [
                'question' => 'Какой оператор используется для присваивания значения переменной в PHP?',
                'options' => ['=', '==', '===', ':='],
                'correct_answer' => 0,
                'order' => 1,
            ],
            [
                'question' => 'Как начинается переменная в PHP?',
                'options' => ['@', '#', '$', '&'],
                'correct_answer' => 2,
                'order' => 2,
            ],
            [
                'question' => 'Какая функция используется для вывода текста в PHP?',
                'options' => ['print()', 'echo', 'console.log()', 'Все перечисленные'],
                'correct_answer' => 1,
                'order' => 3,
            ],
        ];

        foreach ($questions as $questionData) {
            TestQuestion::create(array_merge(['test_id' => $test1->id], $questionData));
        }

        $this->command->info('✓ База данных заполнена тестовыми данными!');
        $this->command->info('');
        $this->command->info('Учетные данные для входа:');
        $this->command->info('Администратор: admin@edupoint.com / password');
        $this->command->info('Преподаватель: teacher@edupoint.com / password');
        $this->command->info('Студент: student@edupoint.com / password');
    }
}

